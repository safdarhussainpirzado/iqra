<?php

namespace App\Jobs;

use App\Models\Note;
use App\Models\Material;
use App\Models\Question;
use App\Services\OcrPipeline;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessOcrJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected string $tempFilePath;
    protected string $targetType; // note, material, question
    protected array $metadata;
    public int $timeout = 1800; // 30 minutes for heavy OCR processing

    public function __construct(string $tempFilePath, string $targetType, array $metadata)
    {
        $this->tempFilePath = $tempFilePath;
        $this->targetType = $targetType;
        $this->metadata = $metadata;
    }

    public function handle()
    {
        if (!file_exists($this->tempFilePath)) {
            Log::error("OCR File not found: {$this->tempFilePath}");
            return;
        }

        $extractedText = '';
        $tempDir = sys_get_temp_dir() . '/ocr_' . uniqid();
        mkdir($tempDir, 0777, true);

        try {
            // 1. Split PDF into page images using pdftoppm
            $pdfEscaped = escapeshellarg($this->tempFilePath);
            $prefixEscaped = escapeshellarg($tempDir . '/page');
            exec("pdftoppm -png -r 150 {$pdfEscaped} {$prefixEscaped} 2>&1", $ppmOutput, $ppmStatus);

            if ($ppmStatus !== 0) {
                throw new Exception("pdftoppm failed: " . implode("\n", $ppmOutput));
            }

            // 2. Scan temp directory for generated page images
            $images = glob($tempDir . '/page-*.png');
            sort($images);

            if (empty($images)) {
                $images = [$this->tempFilePath];
            }

            // 3. Run Tesseract on each page image
            $pagesText = [];
            foreach ($images as $index => $imagePath) {
                // Preprocess with ImageMagick
                $processedImage = $tempDir . "/processed_{$index}.png";
                exec("convert " . escapeshellarg($imagePath) . " -sharpen 0x1 -threshold 50% " . escapeshellarg($processedImage));

                $imageToOcr = file_exists($processedImage) ? $processedImage : $imagePath;
                $imageEscaped = escapeshellarg($imageToOcr);

                $outputBase = $tempDir . "/out_{$index}";
                $outputBaseEscaped = escapeshellarg($outputBase);

                // Run Tesseract with English, Urdu, and Sindhi languages, using PSM 6 for text blocks
                exec("tesseract {$imageEscaped} {$outputBaseEscaped} -l eng+urd+sin --psm 6 2>&1", $tessOutput, $tessStatus);

                $txtFile = $outputBase . '.txt';
                if (file_exists($txtFile)) {
                    $pagesText[] = file_get_contents($txtFile);
                }
            }

            $extractedText = implode("\n\n--- Page Break ---\n\n", $pagesText);

            // 4. Save to Database using the new Pipeline
            $pipeline = new OcrPipeline();
            $cleanedText = $pipeline->process($extractedText);
            $this->saveToDatabase($cleanedText);

            // 5. Automate structured digital textbook ingestion/publishing if matched
            $lowerTitle = strtolower($this->metadata['title'] ?? '');
            $lowerText = strtolower($extractedText);

            if (
                str_contains($lowerTitle, 'unit 2') || str_contains($lowerTitle, 'digital skills') || str_contains($lowerText, 'digital skills') ||
                str_contains($lowerTitle, 'unit 3') || str_contains($lowerTitle, 'computational') || str_contains($lowerText, 'computational') ||
                str_contains($lowerTitle, 'unit 1') || str_contains($lowerTitle, 'emerging') || str_contains($lowerText, 'emerging')
            ) {
                $jsonPath = database_path('seeders/computer_science_7.json');
                if (file_exists($jsonPath)) {
                    \Illuminate\Support\Facades\Artisan::call('app:import-unit', [
                        'file' => $jsonPath,
                        '--board' => 'PUNJAB',
                        '--class' => 7,
                        '--subject' => 'COMP-7'
                    ]);
                }
            }

            // 6. Log success activity
            \App\Models\ActivityLog::create([
                'user_id' => $this->metadata['user_id'] ?? 1,
                'action' => 'ingest_ocr_success',
                'description' => "Successfully parsed and saved {$this->targetType}: {$this->metadata['title']}",
                'ip_address' => '127.0.0.1',
            ]);

        } catch (Exception $e) {
            Log::error("OCR Processing failed: " . $e->getMessage());

            // Log failure activity
            \App\Models\ActivityLog::create([
                'user_id' => $this->metadata['user_id'] ?? 1,
                'action' => 'ingest_ocr_failed',
                'description' => "Failed OCR Job for PDF: {$this->metadata['title']}. Error: " . $e->getMessage(),
                'ip_address' => '127.0.0.1',
            ]);

            throw $e;
        } finally {
            // Cleanup temp files
            $this->cleanup($tempDir);
        }
    }

    private function saveToDatabase(string $text)
    {
        switch ($this->targetType) {
            case 'note':
                Note::updateOrCreate(
                    [
                        'board_id' => $this->metadata['board_id'],
                        'class_id' => $this->metadata['class_id'],
                        'subject_id' => $this->metadata['subject_id'],
                        'chapter_id' => $this->metadata['chapter_id'],
                    ],
                    [
                        'title' => $this->metadata['title'],
                        'file_path' => 'database_only',
                        'extracted_text' => $text,
                        'file_type' => 'PDF (OCR)',
                    ]
                );
                break;

            case 'material':
                Material::updateOrCreate(
                    [
                        'board_id' => $this->metadata['board_id'],
                        'class_id' => $this->metadata['class_id'],
                        'subject_id' => $this->metadata['subject_id'],
                        'chapter_id' => $this->metadata['chapter_id'],
                    ],
                    [
                        'title' => $this->metadata['title'],
                        'file_path' => 'database_only',
                        'extracted_text' => $text,
                        'file_type' => 'PDF (OCR)',
                        'version' => \DB::raw('version + 1'),
                    ]
                );
                break;

            case 'question':
                // Parse questions from OCR text
                $questions = explode("\n\n", $text);
                foreach ($questions as $qText) {
                    if (trim($qText) === '') continue;
                    Question::create([
                        'board_id' => $this->metadata['board_id'],
                        'class_id' => $this->metadata['class_id'],
                        'subject_id' => $this->metadata['subject_id'],
                        'chapter_id' => $this->metadata['chapter_id'],
                        'type' => $this->metadata['type'] ?? 'Short',
                        'question_text' => $qText,
                        'difficulty' => $this->metadata['difficulty'] ?? 'Medium',
                        'marks' => $this->metadata['marks'] ?? 2,
                        'language' => $this->metadata['language'] ?? 'English',
                    ]);
                }
                break;
        }
    }

    private function cleanup(string $tempDir)
    {
        // Delete all files in tempDir
        $files = glob($tempDir . '/*');
        foreach ($files as $file) {
            if (is_file($file)) unlink($file);
        }
        if (is_dir($tempDir)) rmdir($tempDir);

        // Delete the original uploaded temp file
        if (file_exists($this->tempFilePath)) {
            unlink($this->tempFilePath);
        }
    }
}

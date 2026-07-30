<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Models\Material;
use App\Models\ActivityLog;
use App\Services\DocumentParserService;
use App\Services\WebImporterService;
use App\Jobs\ProcessOcrJob;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class IngestionController extends Controller
{
    protected DocumentParserService $parser;
    protected WebImporterService $scraper;

    public function __construct(DocumentParserService $parser, WebImporterService $scraper)
    {
        $this->parser = $parser;
        $this->scraper = $scraper;
    }

    public function ingest(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:10240', // 10MB limit
            'target_type' => 'required|in:note,material,question',
            'board_id' => 'required|exists:boards,id',
            'class_id' => 'required|exists:classes,id',
            'subject_id' => 'required|exists:subjects,id',
            'chapter_id' => 'required|exists:chapters,id',
            'title' => 'required|string|max:255',
            'run_ocr' => 'nullable|string', // "true" or "false"
        ]);

        $file = $request->file('file');
        $ext = $file->getClientOriginalExtension();
        
        // Save file to a secure temporary path
        $tempName = uniqid('upload_') . '.' . $ext;
        $tempPath = sys_get_temp_dir() . '/' . $tempName;
        move_uploaded_file($file->getRealPath(), $tempPath);

        $shouldRunOcr = $request->input('run_ocr') === 'true';

        // If it's a PDF and OCR is requested, queue the OCR Job
        if ($shouldRunOcr && strtolower($ext) === 'pdf') {
            ProcessOcrJob::dispatch($tempPath, $request->target_type, $request->all());
            
            ActivityLog::create([
                'user_id' => $request->user()->id,
                'action' => 'ingest_ocr_queued',
                'description' => "Queued OCR Job for PDF: {$request->title}",
                'ip_address' => $request->ip(),
            ]);

            return response()->json([
                'message' => 'OCR processing has been scheduled in the background.',
                'status' => 'queued'
            ], 202);
        }

        try {
            // Otherwise, parse the text directly
            $extractedText = $this->parser->parse($tempPath, $ext);

            // Clean up temporary file immediately after text extraction
            if (file_exists($tempPath)) {
                unlink($tempPath);
            }

            // Save to database according to target type
            $item = null;
            if ($request->target_type === 'note') {
                $item = Note::create([
                    'board_id' => $request->board_id,
                    'class_id' => $request->class_id,
                    'subject_id' => $request->subject_id,
                    'chapter_id' => $request->chapter_id,
                    'title' => $request->title,
                    'file_path' => 'database_only', // we do not keep original files
                    'extracted_text' => $extractedText,
                    'file_type' => strtoupper($ext),
                ]);
            } else if ($request->target_type === 'material') {
                $item = Material::create([
                    'board_id' => $request->board_id,
                    'class_id' => $request->class_id,
                    'subject_id' => $request->subject_id,
                    'chapter_id' => $request->chapter_id,
                    'title' => $request->title,
                    'file_path' => 'database_only',
                    'extracted_text' => $extractedText,
                    'file_type' => strtoupper($ext),
                    'version' => 1,
                ]);
            }

            ActivityLog::create([
                'user_id' => $request->user()->id,
                'action' => 'ingest_success',
                'description' => "Ingested {$request->target_type}: {$request->title} from {$ext} file",
                'ip_address' => $request->ip(),
            ]);

            return response()->json([
                'message' => 'File ingested and text extracted successfully.',
                'text' => $extractedText,
                'item' => $item
            ], 201);

        } catch (Exception $e) {
            if (file_exists($tempPath)) {
                unlink($tempPath);
            }
            return response()->json([
                'message' => 'Ingestion failed: ' . $e->getMessage()
            ], 500);
        }
    }

    public function scrape(Request $request)
    {
        $request->validate([
            'url' => 'required|url',
            'target_type' => 'required|in:note,material',
            'board_id' => 'required|exists:boards,id',
            'class_id' => 'required|exists:classes,id',
            'subject_id' => 'required|exists:subjects,id',
            'chapter_id' => 'required|exists:chapters,id',
            'title' => 'required|string|max:255',
        ]);

        try {
            $extractedText = $this->scraper->import($request->url);

            $item = null;
            if ($request->target_type === 'note') {
                $item = Note::create([
                    'board_id' => $request->board_id,
                    'class_id' => $request->class_id,
                    'subject_id' => $request->subject_id,
                    'chapter_id' => $request->chapter_id,
                    'title' => $request->title,
                    'file_path' => $request->url,
                    'extracted_text' => $extractedText,
                    'file_type' => 'WEB',
                ]);
            } else {
                $item = Material::create([
                    'board_id' => $request->board_id,
                    'class_id' => $request->class_id,
                    'subject_id' => $request->subject_id,
                    'chapter_id' => $request->chapter_id,
                    'title' => $request->title,
                    'file_path' => $request->url,
                    'extracted_text' => $extractedText,
                    'file_type' => 'WEB',
                    'version' => 1,
                    'source_url' => $request->url,
                ]);
            }

            ActivityLog::create([
                'user_id' => $request->user()->id,
                'action' => 'scrape_success',
                'description' => "Scraped URL: {$request->url} and saved as {$request->target_type}",
                'ip_address' => $request->ip(),
            ]);

            return response()->json([
                'message' => 'Web page scraped successfully.',
                'text' => $extractedText,
                'item' => $item
            ], 201);

        } catch (Exception $e) {
            return response()->json([
                'message' => 'Scraping failed: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getNotes()
    {
        return Note::with(['board', 'class', 'subject', 'chapter'])->get();
    }

    public function getMaterials()
    {
        return Material::with(['board', 'class', 'subject', 'chapter'])->get();
    }

    public function updateNote(Request $request, Note $note)
    {
        $request->validate(['extracted_text' => 'required|string']);
        $note->update(['extracted_text' => $request->extracted_text]);
        return response()->json($note);
    }

    public function updateMaterial(Request $request, Material $material)
    {
        $request->validate(['extracted_text' => 'required|string']);
        $material->update([
            'extracted_text' => $request->extracted_text,
            'version' => $material->version + 1
        ]);
        return response()->json($material);
    }
}

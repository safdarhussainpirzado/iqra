<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Step 1: Delete corrupted note
$note = \App\Models\Note::where('chapter_id', 771)->first();
if ($note) {
    $note->forceDelete();
    echo "Deleted corrupted Unit 5 note\n";
}

// Step 2: Remove old sections to allow fresh structure
$chapter = \App\Models\Chapter::find(771);
if ($chapter) {
    $chapter->sections()->delete();
    echo "Cleared old sections for chapter\n";
}

// Step 3: Find the actual PDF file
$pdfPath = storage_path('app/private/' . basename($note->file_path ?? ''));
if (!$pdfPath || !file_exists($pdfPath)) {
    // Try to find any PDF in private dir
    $files = glob(storage_path('app/private/*.pdf'));
    foreach ($files as $f) {
        if (strpos($f, 'Unit 5') !== false || strpos($f, 'unit-5') !== false) {
            $pdfPath = $f;
            break;
        }
    }
}

if (!$pdfPath || !file_exists($pdfPath)) {
    echo "ERROR: No PDF found for Unit 5. Please upload via Ingestion panel.\n";
    exit;
}

echo "Found PDF: " . basename($pdfPath) . "\n";

// Step 4: Run pdftotext to extract clean text
exec("pdftotext " . escapeshellarg($pdfPath) . " - 2>&1", $output, $returnVar);
$cleanText = trim(implode("\n", $output));

if (empty($cleanText)) {
    echo "ERROR: pdftotext returned empty. PDF may be scanned/image-based.\n";
    echo "Try re-uploading with OCR enabled.\n";
    exit;
}

echo "Extracted text length: " . strlen($cleanText) . "\n";

// Step 5: Save clean text as new note
$newNote = \App\Models\Note::create([
    'chapter_id' => 771,
    'title' => 'Unit 5 - Digital Citizenship',
    'extracted_text' => $cleanText,
    'file_type' => 'PDF (OCR)',
]);

// Step 6: Auto-structure into proper sections
$parser = new \App\Services\TextStructureAutoParser();
$parser->autoStructureChapter($chapter, $cleanText);

// Step 7: Mark chapter as published
$chapter->update(['is_published' => true]);

echo "SUCCESS: Unit 5 re-processed!\n";
echo "URL: /punjab/punjab-board/class-7/computer/unit-5\n";
echo "Sections: " . $chapter->sections()->count() . "\n";
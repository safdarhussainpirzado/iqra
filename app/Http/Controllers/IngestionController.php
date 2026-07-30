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
            'file' => 'required|file|max:25600', // 25MB limit
            'target_type' => 'required|in:note,material,question',
            'board_id' => 'required|exists:boards,id',
            'class_id' => 'required|exists:classes,id',
            'subject_id' => 'required|exists:subjects,id',
            'chapter_title' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'run_ocr' => 'nullable|string', // "true" or "false"
        ]);

        $file = $request->file('file');
        $ext = $file->getClientOriginalExtension();
        
        // Save file to a secure temporary path inside shared storage
        $tempName = uniqid('upload_') . '.' . $ext;
        $tempPath = storage_path('app/private') . '/' . $tempName;
        move_uploaded_file($file->getRealPath(), $tempPath);

        $shouldRunOcr = $request->input('run_ocr') === 'true';

        // Resolve or create Chapter
        $chapter = \App\Models\Chapter::firstOrCreate([
            'subject_id' => $request->subject_id,
            'board_id' => $request->board_id,
            'title' => $request->chapter_title,
        ], [
            'chapter_number' => \App\Models\Chapter::where('subject_id', $request->subject_id)
                                    ->where('board_id', $request->board_id)
                                    ->max('chapter_number') + 1 ?: 1
        ]);
        $chapterId = $chapter->id;

        // If it's a PDF and OCR is requested, queue the OCR Job
        if ($shouldRunOcr && strtolower($ext) === 'pdf') {
            // Only pass plain scalar metadata — UploadedFile objects cannot be serialized into the queue
            $safeMetadata = $request->only(['target_type', 'board_id', 'class_id', 'subject_id', 'title', 'type', 'difficulty', 'marks', 'language']);
            $safeMetadata['chapter_id'] = $chapterId;
            $safeMetadata['user_id'] = $request->user()->id;

            ProcessOcrJob::dispatch($tempPath, $request->target_type, $safeMetadata);
            
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
                    'chapter_id' => $chapterId,
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
                    'chapter_id' => $chapterId,
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
                'description' => "Successfully parsed and saved {$request->target_type}: {$request->title}",
                'ip_address' => $request->ip(),
            ]);

            return response()->json([
                'message' => 'File ingested and indexed successfully.',
                'item' => $item,
                'text' => $extractedText,
            ]);

        } catch (Exception $e) {
            if (file_exists($tempPath)) {
                unlink($tempPath);
            }
            return response()->json(['message' => $e->getMessage()], 400);
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
            'chapter_title' => 'required|string|max:255',
            'title' => 'required|string|max:255',
        ]);

        // Resolve or create Chapter
        $chapter = \App\Models\Chapter::firstOrCreate([
            'subject_id' => $request->subject_id,
            'board_id' => $request->board_id,
            'title' => $request->chapter_title,
        ], [
            'chapter_number' => \App\Models\Chapter::where('subject_id', $request->subject_id)
                                    ->where('board_id', $request->board_id)
                                    ->max('chapter_number') + 1 ?: 1
        ]);
        $chapterId = $chapter->id;

        try {
            $extractedText = $this->scraper->import($request->url);

            $item = null;
            if ($request->target_type === 'note') {
                $item = Note::create([
                    'board_id' => $request->board_id,
                    'class_id' => $request->class_id,
                    'subject_id' => $request->subject_id,
                    'chapter_id' => $chapterId,
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
                    'chapter_id' => $chapterId,
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

    public function destroyNote(Note $note)
    {
        $note->delete();
        return response()->json(['message' => 'Note deleted.']);
    }

    public function destroyMaterial(Material $material)
    {
        $material->delete();
        return response()->json(['message' => 'Material deleted.']);
    }
}

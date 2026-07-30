<?php

namespace App\Http\Controllers;

use App\Models\Chapter;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ChapterController extends Controller
{
    public function index(Request $request)
    {
        $query = Chapter::with(['subject', 'board']);

        if ($request->has('board_id')) {
            $query->where('board_id', $request->board_id);
        }
        if ($request->has('subject_id')) {
            $query->where('subject_id', $request->subject_id);
        }

        return $query->get();
    }

    public function store(Request $request)
    {
        Gate::authorize('create', Chapter::class);

        $validated = $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'board_id' => 'required|exists:boards,id',
            'title' => 'required|string|max:255',
            'chapter_number' => 'required|integer',
        ]);

        // Check uniqueness constraint before creating to prevent duplicate SQL errors
        $exists = Chapter::where('subject_id', $request->subject_id)
            ->where('board_id', $request->board_id)
            ->where('chapter_number', $request->chapter_number)
            ->exists();

        if ($exists) {
            return response()->json([
                'message' => 'A chapter with this number already exists for the selected subject and board.'
            ], 422);
        }

        $chapter = Chapter::create($validated);

        ActivityLog::create([
            'user_id' => $request->user()->id,
            'action' => 'chapter_create',
            'description' => "Created Chapter: {$chapter->title} (Ch: {$chapter->chapter_number})",
            'ip_address' => $request->ip(),
        ]);

        return response()->json($chapter, 201);
    }

    public function show(Chapter $chapter)
    {
        return $chapter->load(['subject', 'board', 'topics']);
    }

    public function update(Request $request, Chapter $chapter)
    {
        Gate::authorize('update', $chapter);

        $validated = $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'board_id' => 'required|exists:boards,id',
            'title' => 'required|string|max:255',
            'chapter_number' => 'required|integer',
        ]);

        // Check uniqueness constraint
        $exists = Chapter::where('subject_id', $request->subject_id)
            ->where('board_id', $request->board_id)
            ->where('chapter_number', $request->chapter_number)
            ->where('id', '!=', $chapter->id)
            ->exists();

        if ($exists) {
            return response()->json([
                'message' => 'A chapter with this number already exists for the selected subject and board.'
            ], 422);
        }

        $chapter->update($validated);

        ActivityLog::create([
            'user_id' => $request->user()->id,
            'action' => 'chapter_update',
            'description' => "Updated Chapter ID {$chapter->id}: {$chapter->title}",
            'ip_address' => $request->ip(),
        ]);

        return response()->json($chapter);
    }

    public function destroy(Request $request, Chapter $chapter)
    {
        Gate::authorize('delete', $chapter);

        $chapter->delete();

        ActivityLog::create([
            'user_id' => $request->user()->id,
            'action' => 'chapter_delete',
            'description' => "Deleted Chapter ID {$chapter->id}",
            'ip_address' => $request->ip(),
        ]);

        return response()->json([
            'message' => 'Chapter deleted successfully.'
        ]);
    }
}

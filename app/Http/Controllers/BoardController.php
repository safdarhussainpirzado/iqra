<?php

namespace App\Http\Controllers;

use App\Models\Board;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class BoardController extends Controller
{
    public function index(Request $request)
    {
        return Board::all();
    }

    public function store(Request $request)
    {
        Gate::authorize('create', Board::class);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:boards,code|max:255',
        ]);

        $board = Board::create($validated);

        ActivityLog::create([
            'user_id' => $request->user()->id,
            'action' => 'board_create',
            'description' => "Created Board: {$board->name} ({$board->code})",
            'ip_address' => $request->ip(),
        ]);

        return response()->json($board, 201);
    }

    public function show(Board $board)
    {
        return $board;
    }

    public function update(Request $request, Board $board)
    {
        Gate::authorize('update', $board);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:boards,code,' . $board->id,
        ]);

        $board->update($validated);

        ActivityLog::create([
            'user_id' => $request->user()->id,
            'action' => 'board_update',
            'description' => "Updated Board ID {$board->id}: {$board->name}",
            'ip_address' => $request->ip(),
        ]);

        return response()->json($board);
    }

    public function destroy(Request $request, Board $board)
    {
        Gate::authorize('delete', $board);

        $board->delete();

        ActivityLog::create([
            'user_id' => $request->user()->id,
            'action' => 'board_delete',
            'description' => "Deleted Board ID {$board->id}",
            'ip_address' => $request->ip(),
        ]);

        return response()->json([
            'message' => 'Board deleted successfully.'
        ]);
    }
}

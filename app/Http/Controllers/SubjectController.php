<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class SubjectController extends Controller
{
    public function index()
    {
        return Subject::with('class')->get();
    }

    public function store(Request $request)
    {
        // Simple inline gate/role check for Admin/Super Admin
        if (!$request->user()->hasPermission('manage-subjects')) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'class_id' => 'required|exists:classes,id',
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:subjects,code|max:255',
        ]);

        $subject = Subject::create($validated);

        ActivityLog::create([
            'user_id' => $request->user()->id,
            'action' => 'subject_create',
            'description' => "Created Subject: {$subject->name} ({$subject->code})",
            'ip_address' => $request->ip(),
        ]);

        return response()->json($subject, 201);
    }

    public function show(Subject $subject)
    {
        return $subject->load('class');
    }

    public function update(Request $request, Subject $subject)
    {
        if (!$request->user()->hasPermission('manage-subjects')) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'class_id' => 'required|exists:classes,id',
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:subjects,code,' . $subject->id,
        ]);

        $subject->update($validated);

        ActivityLog::create([
            'user_id' => $request->user()->id,
            'action' => 'subject_update',
            'description' => "Updated Subject ID {$subject->id}: {$subject->name}",
            'ip_address' => $request->ip(),
        ]);

        return response()->json($subject);
    }

    public function destroy(Request $request, Subject $subject)
    {
        if (!$request->user()->hasPermission('manage-subjects')) {
            abort(403, 'Unauthorized action.');
        }

        $subject->delete();

        ActivityLog::create([
            'user_id' => $request->user()->id,
            'action' => 'subject_delete',
            'description' => "Deleted Subject ID {$subject->id}",
            'ip_address' => $request->ip(),
        ]);

        return response()->json([
            'message' => 'Subject deleted successfully.'
        ]);
    }
}

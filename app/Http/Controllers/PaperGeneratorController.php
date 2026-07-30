<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\MCQOption;
use App\Models\GeneratedPaper;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class PaperGeneratorController extends Controller
{
    public function getQuestions()
    {
        return Question::with(['board', 'class', 'subject', 'chapter', 'options'])->get();
    }

    public function storeQuestion(Request $request)
    {
        $request->validate([
            'board_id' => 'required|exists:boards,id',
            'class_id' => 'required|exists:classes,id',
            'subject_id' => 'required|exists:subjects,id',
            'chapter_id' => 'required|exists:chapters,id',
            'type' => 'required|string', // MCQ, Short, Long
            'question_text' => 'required|string',
            'difficulty' => 'required|string', // Easy, Medium, Hard
            'marks' => 'required|integer',
            'language' => 'required|string',
            'options' => 'nullable|array',
            'options.*.option_text' => 'required|string',
            'options.*.is_correct' => 'required|boolean',
        ]);

        $question = Question::create($request->only([
            'board_id', 'class_id', 'subject_id', 'chapter_id', 'type',
            'question_text', 'difficulty', 'marks', 'language'
        ]));

        if ($request->type === 'MCQ' && !empty($request->options)) {
            foreach ($request->options as $opt) {
                MCQOption::create([
                    'question_id' => $question->id,
                    'option_text' => $opt['option_text'],
                    'is_correct' => $opt['is_correct'],
                ]);
            }
        }

        ActivityLog::create([
            'user_id' => $request->user()->id,
            'action' => 'question_create',
            'description' => "Created {$request->type} Question: " . substr($request->question_text, 0, 50),
            'ip_address' => $request->ip(),
        ]);

        return response()->json($question->load('options'), 201);
    }

    public function destroyQuestion(Request $request, Question $question)
    {
        $question->delete();

        ActivityLog::create([
            'user_id' => $request->user()->id,
            'action' => 'question_delete',
            'description' => "Deleted Question ID {$question->id}",
            'ip_address' => $request->ip(),
        ]);

        return response()->json(['message' => 'Question deleted successfully.']);
    }

    public function generatePaper(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'board_id' => 'required|exists:boards,id',
            'class_id' => 'required|exists:classes,id',
            'subject_id' => 'required|exists:subjects,id',
            'chapter_ids' => 'required|array',
            'chapter_ids.*' => 'exists:chapters,id',
            'difficulty' => 'nullable|string', // Easy, Medium, Hard, All
            'total_marks' => 'required|integer',
        ]);

        $query = Question::where('board_id', $request->board_id)
            ->where('class_id', $request->class_id)
            ->where('subject_id', $request->subject_id)
            ->whereIn('chapter_id', $request->chapter_ids);

        if ($request->difficulty && $request->difficulty !== 'All') {
            $query->where('difficulty', $request->difficulty);
        }

        $allQuestions = $query->with('options')->get()->shuffle();

        $selectedQuestions = [];
        $currentMarks = 0;

        foreach ($allQuestions as $q) {
            if ($currentMarks + $q->marks <= $request->total_marks) {
                $selectedQuestions[] = $q;
                $currentMarks += $q->marks;
            }
            if ($currentMarks >= $request->total_marks) {
                break;
            }
        }

        if (empty($selectedQuestions)) {
            return response()->json([
                'message' => 'No questions found matching the selected criteria.'
            ], 422);
        }

        // Save generated paper record
        $paper = GeneratedPaper::create([
            'user_id' => $request->user()->id,
            'title' => $request->title,
            'paper_structure_json' => [
                'criteria' => $request->all(),
                'total_marks_reached' => $currentMarks,
                'questions' => $selectedQuestions
            ],
        ]);

        ActivityLog::create([
            'user_id' => $request->user()->id,
            'action' => 'paper_generate',
            'description' => "Generated Paper: {$request->title} (Marks: {$currentMarks})",
            'ip_address' => $request->ip(),
        ]);

        return response()->json($paper);
    }

    public function getPapers()
    {
        return GeneratedPaper::all();
    }
}

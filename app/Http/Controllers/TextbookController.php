<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BoardGroup;
use App\Models\Board;
use App\Models\SubjectClass;
use App\Models\Subject;
use App\Models\Chapter;
use App\Models\Section;

class TextbookController extends Controller
{
    public function index()
    {
        // Redirect to first board group if exists, or show listing
        $firstGroup = BoardGroup::orderBy('sort_order')->first();
        if ($firstGroup) {
            $firstBoard = $firstGroup->boards()->first();
            if ($firstBoard) {
                return redirect("/{$firstGroup->slug}/{$firstBoard->slug}");
            }
            return redirect("/{$firstGroup->slug}");
        }
        return response("No textbook content available yet.", 200);
    }

    public function boardGroup($boardGroupSlug)
    {
        $group = BoardGroup::where('slug', $boardGroupSlug)->firstOrFail();
        $board = $group->boards()->first();
        if ($board) {
            return redirect("/{$group->slug}/{$board->slug}");
        }
        return $this->renderViewer($group);
    }

    public function board($boardGroupSlug, $boardSlug)
    {
        $group = BoardGroup::where('slug', $boardGroupSlug)->firstOrFail();
        $board = Board::where('slug', $boardSlug)->where('board_group_id', $group->id)->firstOrFail();
        
        // Find first class and subject with published units
        $chapters = Chapter::where('board_id', $board->id)
            ->where('is_published', true)
            ->first();

        if ($chapters && $chapters->subject) {
            $subject = $chapters->subject;
            $class = $subject->class;
            return redirect("/{$group->slug}/{$board->slug}/{$class->slug}/{$subject->slug}/unit-{$chapters->chapter_number}");
        }

        // Fallback: look for any subject in class 7
        $class7 = SubjectClass::where('level', 7)->first();
        if ($class7) {
            $compSubj = Subject::where('class_id', $class7->id)->where('slug', 'computer-science')->first();
            if ($compSubj) {
                return redirect("/{$group->slug}/{$board->slug}/{$class7->slug}/{$compSubj->slug}/unit-1");
            }
        }

        return $this->renderViewer($group, $board);
    }

    public function class($boardGroupSlug, $boardSlug, $classSlug)
    {
        $group = BoardGroup::where('slug', $boardGroupSlug)->firstOrFail();
        $board = Board::where('slug', $boardSlug)->where('board_group_id', $group->id)->firstOrFail();
        $class = SubjectClass::where('slug', $classSlug)->firstOrFail();

        $subject = Subject::where('class_id', $class->id)->first();
        if ($subject) {
            return redirect("/{$group->slug}/{$board->slug}/{$class->slug}/{$subject->slug}/unit-1");
        }

        return $this->renderViewer($group, $board, $class);
    }

    public function subject($boardGroupSlug, $boardSlug, $classSlug, $subjectSlug)
    {
        return redirect("/{$boardGroupSlug}/{$boardSlug}/{$classSlug}/{$subjectSlug}/unit-1");
    }

    public function unit($boardGroupSlug, $boardSlug, $classSlug, $subjectSlug, $unitNumber)
    {
        $group = BoardGroup::where('slug', $boardGroupSlug)->firstOrFail();
        $board = Board::where('slug', $boardSlug)->where('board_group_id', $group->id)->firstOrFail();
        $class = SubjectClass::where('slug', $classSlug)->firstOrFail();
        
        $subject = Subject::where('slug', $subjectSlug)
            ->where('class_id', $class->id)
            ->firstOrFail();

        $chapter = Chapter::where('board_id', $board->id)
            ->where('subject_id', $subject->id)
            ->where('chapter_number', $unitNumber)
            ->first();

        return $this->renderViewer($group, $board, $class, $subject, $chapter, $unitNumber);
    }

    protected function renderViewer($group = null, $board = null, $class = null, $subject = null, $chapter = null, $unitNumber = 1)
    {
        $boardGroups = BoardGroup::with('boards')->orderBy('sort_order')->get();
        
        // Load all units/chapters for this board and subject if resolved
        $units = [];
        if ($board && $subject) {
            $units = Chapter::where('board_id', $board->id)
                ->where('subject_id', $subject->id)
                ->orderBy('chapter_number')
                ->get();
        }

        // If active chapter is found, load its sections and items
        $structuredData = null;
        $note = null;
        if ($chapter) {
            $note = \App\Models\Note::where('chapter_id', $chapter->id)->orderBy('id', 'desc')->first();
        }

        if ($chapter && ($chapter->is_published || $note)) {
            $sections = Section::with(['items.options', 'items.paragraphs', 'items.table.columns', 'items.table.rows.cells'])
                ->where('chapter_id', $chapter->id)
                ->orderBy('sort_order')
                ->get();

            $structuredData = [
                'num' => $chapter->chapter_number,
                'title' => $chapter->title,
                'blurb' => $chapter->blurb,
                'color' => $chapter->color_hex ?: '#10B981',
                'available' => true,
                'sections' => $sections->map(function($sec) {
                    return [
                        'key' => $sec->type,
                        'label' => $sec->label,
                        'count' => $sec->items->count(),
                    ];
                })->toArray(),
            ];

            // Add raw section items arrays matching frontend expectation
            foreach ($sections as $sec) {
                $secKey = $sec->type;
                $structuredData[$secKey] = $sec->items->map(function($item) {
                    $itemArr = [
                        'q' => $item->question,
                    ];

                    if ($item->item_type === 'choice') {
                        $itemArr['options'] = $item->options->pluck('option_text')->toArray();
                        $itemArr['correct'] = $item->correct_option_index;
                    } elseif ($item->item_type === 'definition') {
                        $itemArr['a'] = $item->paragraphs->first()->content_html ?? '';
                    } else {
                        $itemArr['a'] = $item->paragraphs->pluck('content_html')->toArray();
                    }

                    if ($item->table) {
                        $itemArr['table'] = [
                            'caption' => $item->table->caption,
                            'head' => $item->table->columns->pluck('heading')->toArray(),
                            'rows' => $item->table->rows->map(function($row) {
                                return $row->cells->pluck('value')->toArray();
                            })->toArray(),
                        ];
                    }

                    return $itemArr;
                })->toArray();
            }
        }



        return view('iqra.textbook', [
            'boardGroups' => $boardGroups,
            'activeGroup' => $group,
            'activeBoard' => $board,
            'activeClass' => $class,
            'activeSubject' => $subject,
            'activeChapter' => $chapter,
            'activeUnitNumber' => $unitNumber,
            'units' => $units,
            'structuredData' => $structuredData,
            'note' => $note,
        ]);
    }
}

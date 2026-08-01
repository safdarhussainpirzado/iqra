<?php

namespace App\Services;

use App\Models\Chapter;
use App\Models\Section;
use App\Models\Item;
use App\Models\ItemOption;
use App\Models\ItemAnswerParagraph;
use Illuminate\Support\Str;

class TextStructureAutoParser
{
    /**
     * Parse unstructured raw OCR text and convert it into structured textbook sections.
     */
    public function autoStructureChapter(Chapter $chapter, string $rawText): void
    {
        // Delete old auto-parsed sections to avoid duplication
        $chapter->sections()->delete();

        $lines = explode("\n", $rawText);
        $currentSectionKey = 'brief';
        $currentSectionLabel = 'Brief Q&A';
        $sections = [
            'tick' => ['label' => 'Tick the Answer', 'items' => []],
            'brief' => ['label' => 'Brief Q&A', 'items' => []],
            'detailed' => ['label' => 'Detailed Q&A', 'items' => []],
            'mcq' => ['label' => 'MCQs', 'items' => []],
            'crq' => ['label' => 'Constructed Response Questions (CRQs)', 'items' => []],
        ];

        $currentQuestion = null;
        $currentOptions = [];
        $currentAnswers = [];

        foreach ($lines as $line) {
            $trimmed = trim($line);
            if (empty($trimmed)) continue;

            $lower = strtolower($trimmed);

            // Detect section markers
            if (str_contains($lower, 'tick') || str_contains($lower, 'choose the correct')) {
                $currentSectionKey = 'tick';
                $currentSectionLabel = 'Tick the Answer';
                continue;
            } elseif (str_contains($lower, 'mcq') || str_contains($lower, 'multiple choice')) {
                $currentSectionKey = 'mcq';
                $currentSectionLabel = 'MCQs';
                continue;
            } elseif (str_contains($lower, 'brief') || str_contains($lower, 'short question')) {
                $currentSectionKey = 'brief';
                $currentSectionLabel = 'Brief Q&A';
                continue;
            } elseif (str_contains($lower, 'detail') || str_contains($lower, 'long question')) {
                $currentSectionKey = 'detailed';
                $currentSectionLabel = 'Detailed Q&A';
                continue;
            } elseif (str_contains($lower, 'crq') || str_contains($lower, 'constructed response')) {
                $currentSectionKey = 'crq';
                $currentSectionLabel = 'Constructed Response Questions (CRQs)';
                continue;
            }

            // Question detection pattern: starts with a number followed by . or )
            if (preg_match('/^(?:Q|q)?\d+[\.\)]\s*(.+)/', $trimmed, $matches)) {
                // Save previous question
                if ($currentQuestion) {
                    $sections[$currentSectionKey]['items'][] = [
                        'q' => $currentQuestion,
                        'options' => $currentOptions,
                        'a' => $currentAnswers,
                    ];
                }
                $currentQuestion = $matches[1];
                $currentOptions = [];
                $currentAnswers = [];
            } elseif (preg_match('/^[a-dA-D][\.\)]\s*(.+)/', $trimmed, $optMatches)) {
                // Option detection pattern
                $currentOptions[] = $optMatches[1];
            } elseif (str_starts_with($lower, 'ans:') || str_starts_with($lower, 'answer:')) {
                $ansText = trim(preg_replace('/^(ans:|answer:)/i', '', $trimmed));
                if (!empty($ansText)) {
                    $currentAnswers[] = $ansText;
                }
            } else {
                if ($currentQuestion && empty($currentOptions)) {
                    $currentAnswers[] = $trimmed;
                }
            }
        }

        // Save last question
        if ($currentQuestion) {
            $sections[$currentSectionKey]['items'][] = [
                'q' => $currentQuestion,
                'options' => $currentOptions,
                'a' => $currentAnswers,
            ];
        }

        // Store into database tables
        $secOrder = 1;
        foreach ($sections as $key => $secData) {
            if (empty($secData['items'])) continue;

            $sectionModel = Section::create([
                'chapter_id' => $chapter->id,
                'type' => $key,
                'label' => $secData['label'],
                'sort_order' => $secOrder++,
            ]);

            foreach ($secData['items'] as $itemIndex => $item) {
                $itemType = !empty($item['options']) ? 'choice' : 'qa';

                $itemModel = Item::create([
                    'section_id' => $sectionModel->id,
                    'question' => $item['q'],
                    'item_type' => $itemType,
                    'correct_option_index' => 0, // default first
                    'sort_order' => $itemIndex + 1,
                ]);

                foreach ($item['options'] as $optIdx => $optText) {
                    ItemOption::create([
                        'item_id' => $itemModel->id,
                        'option_index' => $optIdx,
                        'option_text' => $optText,
                    ]);
                }

                foreach ($item['a'] as $pIdx => $pText) {
                    ItemAnswerParagraph::create([
                        'item_id' => $itemModel->id,
                        'paragraph_order' => $pIdx + 1,
                        'content_html' => $pText,
                    ]);
                }
            }
        }

        // Mark chapter as published once structured
        $chapter->update(['is_published' => true]);
    }
}

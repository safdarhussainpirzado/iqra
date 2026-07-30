<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use App\Models\Board;
use App\Models\SubjectClass;
use App\Models\Subject;
use App\Models\Chapter;
use App\Models\Section;
use App\Models\Item;
use App\Models\ItemOption;
use App\Models\ItemAnswerParagraph;
use App\Models\ItemTable;
use App\Models\ItemTableColumn;
use App\Models\ItemTableRow;
use App\Models\ItemTableCell;

class ImportUnit extends Command
{
    protected $signature = 'app:import-unit {file} {--board=} {--class=} {--subject=}';
    protected $description = 'Import textbook unit content from JSON file into structured tables';

    public function handle()
    {
        $filePath = $this->argument('file');
        if (!file_exists($filePath)) {
            $this->error("JSON file not found: {$filePath}");
            return 1;
        }

        $jsonStr = file_get_contents($filePath);
        $data = json_decode($jsonStr, true);
        if (!$data) {
            $this->error("Invalid JSON data in file: {$filePath}");
            return 1;
        }

        // Handle single unit or array of units
        $units = isset($data['num']) || isset($data['unit_number']) ? [$data] : $data;

        $boardCode = $this->option('board') ?: 'PUNJAB';
        $classLevel = $this->option('class') ?: 7;
        $subjectCode = $this->option('subject') ?: 'COMP-7';

        $board = Board::where('code', $boardCode)->first();
        if (!$board) {
            $this->error("Board not found with code: {$boardCode}");
            return 1;
        }

        $class = SubjectClass::where('level', $classLevel)->first();
        if (!$class) {
            $this->error("Class not found with level: {$classLevel}");
            return 1;
        }

        $subject = Subject::where('code', $subjectCode)->first();
        if (!$subject) {
            // Find by name matching or class mapping
            $subject = Subject::where('class_id', $class->id)
                ->where(function($q) {
                    $q->where('name', 'like', '%Computer%')
                      ->orWhere('name', 'like', '%CS%');
                })->first();
            
            if (!$subject) {
                $this->error("Subject not found with code: {$subjectCode}");
                return 1;
            }
        }

        foreach ($units as $unitData) {
            $unitNum = $unitData['num'] ?? $unitData['unit_number'] ?? 1;
            $title = $unitData['title'] ?? 'Emerging Technologies';
            $blurb = $unitData['blurb'] ?? '';
            $color = $unitData['color'] ?? $unitData['color_hex'] ?? '#10B981';
            $available = isset($unitData['available']) ? (bool)$unitData['available'] : true;

            $this->info("Importing Unit {$unitNum}: {$title}...");

            // Create or update Chapter (representing the Unit)
            $chapter = Chapter::updateOrCreate(
                [
                    'subject_id' => $subject->id,
                    'board_id' => $board->id,
                    'chapter_number' => $unitNum,
                ],
                [
                    'title' => $title,
                    'slug' => Str::slug($title),
                    'blurb' => $blurb,
                    'color_hex' => $color,
                    'is_published' => $available,
                    'source_file_name' => basename($filePath),
                    'sort_order' => $unitNum,
                ]
            );

            // Clean existing sections to prevent duplication on re-run
            $chapter->sections()->delete();

            // Sections array
            $sections = $unitData['sections'] ?? [];

            foreach ($sections as $secIndex => $sec) {
                $secKey = $sec['key'] ?? $sec['type'] ?? 'tick';
                $secLabel = $sec['label'] ?? 'Section';

                $sectionModel = Section::create([
                    'chapter_id' => $chapter->id,
                    'type' => $secKey,
                    'label' => $secLabel,
                    'sort_order' => $secIndex + 1,
                ]);

                // Look up items from the matching key in unitData (e.g. unitData['tick'], unitData['brief'], etc.)
                $items = $unitData[$secKey] ?? $sec['items'] ?? [];

                foreach ($items as $itemIndex => $itemData) {
                    $questionText = $itemData['q'] ?? $itemData['question'] ?? '';
                    if (empty($questionText)) continue;

                    // Determine item type
                    $itemType = 'qa';
                    if (isset($itemData['options'])) {
                        $itemType = 'choice';
                    } elseif ($secKey === 'functions') {
                        $itemType = 'definition';
                    }

                    $correctIndex = $itemData['correct'] ?? $itemData['correct_index'] ?? null;

                    $itemModel = Item::create([
                        'section_id' => $sectionModel->id,
                        'question' => $questionText,
                        'item_type' => $itemType,
                        'correct_option_index' => $correctIndex,
                        'sort_order' => $itemIndex + 1,
                    ]);

                    // If choice/options type
                    if (isset($itemData['options']) && is_array($itemData['options'])) {
                        foreach ($itemData['options'] as $optIndex => $optText) {
                            ItemOption::create([
                                'item_id' => $itemModel->id,
                                'option_index' => $optIndex,
                                'option_text' => $optText,
                            ]);
                        }
                    }

                    // If single string answer (like in functions)
                    if (isset($itemData['a']) && is_string($itemData['a'])) {
                        ItemAnswerParagraph::create([
                            'item_id' => $itemModel->id,
                            'paragraph_order' => 1,
                            'content_html' => $itemData['a'],
                        ]);
                    }

                    // If multiple paragraph answers
                    if (isset($itemData['a']) && is_array($itemData['a'])) {
                        foreach ($itemData['a'] as $pIndex => $pText) {
                            ItemAnswerParagraph::create([
                                'item_id' => $itemModel->id,
                                'paragraph_order' => $pIndex + 1,
                                'content_html' => $pText,
                            ]);
                        }
                    }

                    // If table attached to this item
                    if (isset($itemData['table']) && is_array($itemData['table'])) {
                        $tableData = $itemData['table'];
                        $tableModel = ItemTable::create([
                            'item_id' => $itemModel->id,
                            'caption' => $tableData['caption'] ?? null,
                        ]);

                        // Columns
                        $cols = $tableData['head'] ?? [];
                        foreach ($cols as $colIndex => $colHeading) {
                            ItemTableColumn::create([
                                'item_table_id' => $tableModel->id,
                                'column_index' => $colIndex,
                                'heading' => $colHeading,
                            ]);
                        }

                        // Rows & Cells
                        $rows = $tableData['rows'] ?? [];
                        foreach ($rows as $rowIndex => $rowData) {
                            $rowModel = ItemTableRow::create([
                                'item_table_id' => $tableModel->id,
                                'row_index' => $rowIndex,
                            ]);

                            foreach ($rowData as $colIndex => $cellValue) {
                                ItemTableCell::create([
                                    'item_table_row_id' => $rowModel->id,
                                    'column_index' => $colIndex,
                                    'value' => $cellValue,
                                ]);
                            }
                        }
                    }
                }
            }
        }

        $this->info("Import completed successfully!");
        return 0;
    }
}

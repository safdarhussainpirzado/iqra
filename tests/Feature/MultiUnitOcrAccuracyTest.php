<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Services\OcrPipeline;
use App\Services\TextStructureAutoParser;
use App\Models\Chapter;
use App\Models\Board;
use App\Models\Subject;
use App\Models\SubjectClass;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MultiUnitOcrAccuracyTest extends TestCase
{
    use RefreshDatabase;

    public function test_units_2_to_6_processing_simulation()
    {
        $board = Board::create(['name' => 'Punjab', 'code' => 'PUNJAB', 'slug' => 'punjab']);
        $class = SubjectClass::create(['name' => 'Class 7', 'level' => 7, 'slug' => 'class-7']);
        $subject = Subject::create(['class_id' => $class->id, 'name' => 'Computer', 'code' => 'COMP-7', 'slug' => 'computer']);

        $units = [
            2 => 'Digital Skills',
            3 => 'Computational Thinking',
            4 => 'Spreadsheets in Excel',
            5 => 'Digital Citizenship',
            6 => 'Problem Solving & Algorithms'
        ];

        $pipeline = new OcrPipeline();
        $parser = new TextStructureAutoParser();

        foreach ($units as $num => $title) {
            $chapter = Chapter::create([
                'subject_id' => $subject->id,
                'board_id' => $board->id,
                'title' => $title,
                'chapter_number' => $num,
            ]);

            $simulatedRawText = "downloadclassnotes.com\nUnit {$num}: {$title}\nBrief Q&A\n1. What is covered in {$title}?\nAns: Core concepts and practical exercises.\n2. Why is {$title} important?\nAns: Essential for modern computing.";

            $cleaned = $pipeline->process($simulatedRawText);
            $parser->autoStructureChapter($chapter, $cleaned);

            $this->assertTrue((bool)$chapter->fresh()->is_published);
            $this->assertGreaterThan(0, $chapter->sections()->count());
        }
    }
}

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

class OcrPipelineAccuracyTest extends TestCase
{
    use RefreshDatabase;

    public function test_ocr_pipeline_noise_and_typo_correction()
    {
        $pipeline = new OcrPipeline();
        $input = "downloadclassnotes.com\nComputer Science Notes for Page 1 of 2\n001+5 and Micfosoft Word\nComputational Thinking";
        $output = $pipeline->process($input);

        $this->assertStringNotContainsString('downloadclassnotes.com', $output);
        $this->assertStringContainsString('Ctrl + S', $output);
        $this->assertStringContainsString('Microsoft Word', $output);
    }

    public function test_auto_structure_parser()
    {
        $board = Board::create(['name' => 'Punjab', 'code' => 'PUNJAB', 'slug' => 'punjab']);
        $class = SubjectClass::create(['name' => 'Class 7', 'level' => 7, 'slug' => 'class-7']);
        $subject = Subject::create(['class_id' => $class->id, 'name' => 'Computer', 'code' => 'COMP-7', 'slug' => 'computer']);

        $chapter = Chapter::create([
            'subject_id' => $subject->id,
            'board_id' => $board->id,
            'title' => 'Digital Citizenship',
            'chapter_number' => 5,
        ]);

        $rawText = "Brief Q&A\n1. What is digital citizenship?\nAns: Responsible use of technology.\n2. What is cyberbullying?\nAns: Bullying using digital devices.";

        $parser = new TextStructureAutoParser();
        $parser->autoStructureChapter($chapter, $rawText);

        $this->assertTrue((bool)$chapter->fresh()->is_published);
        $this->assertGreaterThan(0, $chapter->sections()->count());
    }
}

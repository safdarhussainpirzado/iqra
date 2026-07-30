<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('board_id')->constrained('boards')->onDelete('cascade');
            $table->foreignId('class_id')->constrained('classes')->onDelete('cascade');
            $table->foreignId('subject_id')->constrained('subjects')->onDelete('cascade');
            $table->foreignId('chapter_id')->constrained('chapters')->onDelete('cascade');
            $table->string('title');
            $table->string('file_path');
            $table->longText('extracted_text')->nullable();
            $table->string('file_type');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('materials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('board_id')->constrained('boards')->onDelete('cascade');
            $table->foreignId('class_id')->constrained('classes')->onDelete('cascade');
            $table->foreignId('subject_id')->constrained('subjects')->onDelete('cascade');
            $table->foreignId('chapter_id')->constrained('chapters')->onDelete('cascade');
            $table->string('title');
            $table->string('file_path');
            $table->longText('extracted_text')->nullable();
            $table->string('file_type');
            $table->integer('version')->default(1);
            $table->string('source_url')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('question_bank', function (Blueprint $table) {
            $table->id();
            $table->foreignId('board_id')->constrained('boards')->onDelete('cascade');
            $table->foreignId('class_id')->constrained('classes')->onDelete('cascade');
            $table->foreignId('subject_id')->constrained('subjects')->onDelete('cascade');
            $table->foreignId('chapter_id')->constrained('chapters')->onDelete('cascade');
            $table->string('type'); // MCQ, Short, Long, FillInTheBlanks, TrueFalse, Matching
            $table->text('question_text');
            $table->string('difficulty'); // Easy, Medium, Hard
            $table->integer('marks');
            $table->string('language')->default('English'); // English, Urdu, Sindhi
            $table->string('source')->nullable();
            $table->integer('page_number')->nullable();
            $table->json('tags')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('mcq_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('question_id')->constrained('question_bank')->onDelete('cascade');
            $table->text('option_text');
            $table->boolean('is_correct')->default(false);
            $table->text('explanation')->nullable();
            $table->timestamps();
        });

        Schema::create('generated_papers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('title');
            $table->json('paper_structure_json');
            $table->string('pdf_path')->nullable();
            $table->string('answer_key_path')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('generated_papers');
        Schema::dropIfExists('mcq_options');
        Schema::dropIfExists('question_bank');
        Schema::dropIfExists('materials');
        Schema::dropIfExists('notes');
    }
};

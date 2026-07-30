<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('board_groups', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::table('boards', function (Blueprint $table) {
            $table->foreignId('board_group_id')->nullable()->constrained('board_groups')->onDelete('set null');
            $table->string('slug')->nullable();
            $table->boolean('is_active')->default(true);
        });

        Schema::table('classes', function (Blueprint $table) {
            $table->string('slug')->nullable();
            $table->integer('sort_order')->default(0);
        });

        Schema::table('subjects', function (Blueprint $table) {
            $table->string('slug')->nullable();
            $table->string('icon')->nullable();
            $table->string('color_hex')->nullable();
        });

        Schema::table('chapters', function (Blueprint $table) {
            $table->string('slug')->nullable();
            $table->text('blurb')->nullable();
            $table->string('color_hex')->nullable();
            $table->boolean('is_published')->default(false);
            $table->string('source_file_name')->nullable();
            $table->integer('sort_order')->default(0);
        });

        Schema::create('sections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('chapter_id')->constrained('chapters')->onDelete('cascade');
            $table->string('type'); // tick, brief_qa, detailed_qa, functions_qa, mcq, crq, etc.
            $table->string('label');
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('section_id')->constrained('sections')->onDelete('cascade');
            $table->text('question');
            $table->string('item_type'); // choice, qa, definition
            $table->integer('correct_option_index')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('item_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained('items')->onDelete('cascade');
            $table->integer('option_index');
            $table->text('option_text');
            $table->timestamps();
        });

        Schema::create('item_answer_paragraphs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained('items')->onDelete('cascade');
            $table->integer('paragraph_order');
            $table->text('content_html');
            $table->timestamps();
        });

        Schema::create('item_tables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained('items')->onDelete('cascade');
            $table->string('caption')->nullable();
            $table->timestamps();
        });

        Schema::create('item_table_columns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_table_id')->constrained('item_tables')->onDelete('cascade');
            $table->integer('column_index');
            $table->string('heading');
            $table->timestamps();
        });

        Schema::create('item_table_rows', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_table_id')->constrained('item_tables')->onDelete('cascade');
            $table->integer('row_index');
            $table->timestamps();
        });

        Schema::create('item_table_cells', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_table_row_id')->constrained('item_table_rows')->onDelete('cascade');
            $table->integer('column_index');
            $table->text('value');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('item_table_cells');
        Schema::dropIfExists('item_table_rows');
        Schema::dropIfExists('item_table_columns');
        Schema::dropIfExists('item_tables');
        Schema::dropIfExists('item_answer_paragraphs');
        Schema::dropIfExists('item_options');
        Schema::dropIfExists('items');
        Schema::dropIfExists('sections');

        Schema::table('chapters', function (Blueprint $table) {
            $table->dropColumn(['slug', 'blurb', 'color_hex', 'is_published', 'source_file_name', 'sort_order']);
        });

        Schema::table('subjects', function (Blueprint $table) {
            $table->dropColumn(['slug', 'icon', 'color_hex']);
        });

        Schema::table('classes', function (Blueprint $table) {
            $table->dropColumn(['slug', 'sort_order']);
        });

        Schema::table('boards', function (Blueprint $table) {
            $table->dropForeign(['board_group_id']);
            $table->dropColumn(['board_group_id', 'slug', 'is_active']);
        });

        Schema::dropIfExists('board_groups');
    }
};

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Question extends Model
{
    use SoftDeletes;

    protected $table = 'question_bank';

    protected $fillable = ['board_id', 'class_id', 'subject_id', 'chapter_id', 'type', 'question_text', 'difficulty', 'marks', 'language', 'source', 'page_number', 'tags'];

    protected $casts = [
        'tags' => 'array',
    ];

    public function board()
    {
        return $this->belongsTo(Board::class);
    }

    public function class()
    {
        return $this->belongsTo(SubjectClass::class, 'class_id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function chapter()
    {
        return $this->belongsTo(Chapter::class);
    }

    public function options()
    {
        return $this->hasMany(MCQOption::class, 'question_id');
    }
}

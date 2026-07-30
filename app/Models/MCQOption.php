<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MCQOption extends Model
{
    protected $table = 'mcq_options';

    protected $fillable = ['question_id', 'option_text', 'is_correct', 'explanation'];

    protected $casts = [
        'is_correct' => 'boolean',
    ];

    public function question()
    {
        return $this->belongsTo(Question::class, 'question_id');
    }
}

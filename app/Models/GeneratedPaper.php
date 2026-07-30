<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GeneratedPaper extends Model
{
    use SoftDeletes;

    protected $fillable = ['user_id', 'title', 'paper_structure_json', 'pdf_path', 'answer_key_path'];

    protected $casts = [
        'paper_structure_json' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

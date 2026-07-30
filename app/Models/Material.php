<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Material extends Model
{
    use SoftDeletes;

    protected $fillable = ['board_id', 'class_id', 'subject_id', 'chapter_id', 'title', 'file_path', 'extracted_text', 'file_type', 'version', 'source_url'];

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
}

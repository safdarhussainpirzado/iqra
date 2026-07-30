<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Chapter extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'subject_id', 'board_id', 'title', 'chapter_number',
        'slug', 'blurb', 'color_hex', 'is_published', 'source_file_name', 'sort_order'
    ];

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function board()
    {
        return $this->belongsTo(Board::class);
    }

    public function topics()
    {
        return $this->hasMany(Topic::class);
    }

    public function sections()
    {
        return $this->hasMany(Section::class)->orderBy('sort_order');
    }
}

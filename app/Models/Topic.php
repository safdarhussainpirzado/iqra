<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Topic extends Model
{
    use SoftDeletes;

    protected $fillable = ['chapter_id', 'title', 'topic_number'];

    public function chapter()
    {
        return $this->belongsTo(Chapter::class);
    }
}

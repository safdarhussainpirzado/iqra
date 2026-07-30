<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Board extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'code', 'board_group_id', 'slug', 'is_active'];

    public function boardGroup()
    {
        return $this->belongsTo(BoardGroup::class);
    }

    public function chapters()
    {
        return $this->hasMany(Chapter::class);
    }
}

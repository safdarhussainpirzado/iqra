<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    protected $fillable = ['chapter_id', 'type', 'label', 'sort_order'];

    public function chapter()
    {
        return $this->belongsTo(Chapter::class);
    }

    public function items()
    {
        return $this->hasMany(Item::class)->orderBy('sort_order');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = ['section_id', 'question', 'item_type', 'correct_option_index', 'sort_order'];

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function options()
    {
        return $this->hasMany(ItemOption::class)->orderBy('option_index');
    }

    public function paragraphs()
    {
        return $this->hasMany(ItemAnswerParagraph::class)->orderBy('paragraph_order');
    }

    public function table()
    {
        return $this->hasOne(ItemTable::class);
    }
}

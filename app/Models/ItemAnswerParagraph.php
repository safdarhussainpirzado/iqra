<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemAnswerParagraph extends Model
{
    protected $fillable = ['item_id', 'paragraph_order', 'content_html'];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}

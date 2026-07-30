<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemOption extends Model
{
    protected $fillable = ['item_id', 'option_index', 'option_text'];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}

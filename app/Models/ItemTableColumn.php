<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemTableColumn extends Model
{
    protected $fillable = ['item_table_id', 'column_index', 'heading'];

    public function table()
    {
        return $this->belongsTo(ItemTable::class, 'item_table_id');
    }
}

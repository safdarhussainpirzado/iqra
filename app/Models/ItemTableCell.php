<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemTableCell extends Model
{
    protected $fillable = ['item_table_row_id', 'column_index', 'value'];

    public function row()
    {
        return $this->belongsTo(ItemTableRow::class, 'item_table_row_id');
    }
}

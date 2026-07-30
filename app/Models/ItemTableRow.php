<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemTableRow extends Model
{
    protected $fillable = ['item_table_id', 'row_index'];

    public function table()
    {
        return $this->belongsTo(ItemTable::class, 'item_table_id');
    }

    public function cells()
    {
        return $this->hasMany(ItemTableCell::class, 'item_table_row_id')->orderBy('column_index');
    }
}

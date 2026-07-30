<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemTable extends Model
{
    protected $fillable = ['item_id', 'caption'];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function columns()
    {
        return $this->hasMany(ItemTableColumn::class)->orderBy('column_index');
    }

    public function rows()
    {
        return $this->hasMany(ItemTableRow::class)->orderBy('row_index');
    }
}

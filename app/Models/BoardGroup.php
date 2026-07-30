<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BoardGroup extends Model
{
    protected $fillable = ['name', 'slug', 'sort_order'];

    public function boards()
    {
        return $this->hasMany(Board::class)->orderBy('name');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subject extends Model
{
    use SoftDeletes;

    protected $fillable = ['class_id', 'name', 'code'];

    public function class()
    {
        return $this->belongsTo(SubjectClass::class, 'class_id');
    }

    public function chapters()
    {
        return $this->hasMany(Chapter::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubjectClass extends Model
{
    use SoftDeletes;

    protected $table = 'classes';

    protected $fillable = ['name', 'level', 'slug', 'sort_order'];

    public function subjects()
    {
        return $this->hasMany(Subject::class, 'class_id');
    }
}

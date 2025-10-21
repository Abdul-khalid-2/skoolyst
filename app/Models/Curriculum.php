<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Curriculum extends Model
{
    use HasFactory;
    protected $table = 'curriculums';
    protected $fillable = [
        'name',
        'code',
        'description'
    ];

    public function schools()
    {
        return $this->belongsToMany(School::class, 'school_curriculum')
            ->withTimestamps();
    }
}

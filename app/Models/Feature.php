<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feature extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category',
        'icon',
        'description'
    ];

    public function schools()
    {
        return $this->belongsToMany(School::class, 'school_feature')
            ->withPivot('description', 'priority')
            ->withTimestamps();
    }
}

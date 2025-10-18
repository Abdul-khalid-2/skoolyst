<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SchoolImage extends Model
{
    protected $fillable = ['school_id', 'image_path', 'title'];

    public function school()
    {
        return $this->belongsTo(School::class);
    }
}

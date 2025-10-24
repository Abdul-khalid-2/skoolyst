<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class SchoolImageGallery extends Model
{
    use HasFactory;

    protected $table = 'school_image_galleries';

    protected $fillable = [
        'school_id',
        'image_path',
        'image_name',
        'image_unique_name',
        'status'
    ];

    protected $appends = [
        'image_url'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->uuid = Str::uuid();
        });
    }

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function getImageUrlAttribute()
    {
        return asset($this->image_path);
    }
}

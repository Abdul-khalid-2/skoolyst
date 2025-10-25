<?php
// app/Models/Page.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Page extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'school_id',
        'event_id',
        'name',
        'slug',
        'structure',
        'title',
        'banner',
        'image',
        'rich_text',
        'text_left_image_right',
        'custom_html',
        'canvas_elements'
    ];

    protected $casts = [
        'structure' => 'array',
        'title' => 'array',
        'banner' => 'array',
        'image' => 'array',
        'rich_text' => 'array',
        'text_left_image_right' => 'array',
        'custom_html' => 'array',
        'canvas_elements' => 'array',
    ];

    // Relationship with Event
    public function event()
    {
        return $this->belongsTo(Event::class);
    }
    public function events()
    {
        return $this->belongsTo(Event::class);
    }
    public function school()
    {
        return $this->belongsTo(School::class);
    }
}

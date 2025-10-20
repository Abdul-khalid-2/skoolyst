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
        'structure'
    ];

    protected $casts = [
        'structure' => 'array',
    ];

    // Relationship with Event
    public function event()
    {
        return $this->belongsTo(Event::class);
    }
    public function school()
    {
        return $this->belongsTo(School::class);
    }
}

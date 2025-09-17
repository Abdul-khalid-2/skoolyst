<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    use HasFactory;

    // One to many relationship with reviews
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    // One to many relationship with events
    public function events()
    {
        return $this->hasMany(Event::class);
    }
}

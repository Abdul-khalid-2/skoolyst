<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'name',
        'address',
        'city',
        'contact_number',
        'branch_head_name',
        'latitude',
        'longitude',
        'is_main_branch',
        'status'
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function events()
    {
        return $this->hasMany(Event::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}

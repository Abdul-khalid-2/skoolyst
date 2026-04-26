<?php

namespace App\Models;

use App\Enums\ActiveStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{

    protected $fillable = ['school_id', 'branch_id', 'event_name', 'event_description', 'event_date', 'event_location', 'status'];
    use HasFactory;

    protected $casts = [
        'event_date' => 'datetime',
        'status' => ActiveStatus::class,
    ];

    // Belongs to a school
    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}

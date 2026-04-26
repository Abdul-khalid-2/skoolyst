<?php

namespace App\Models;

use App\Enums\ModerationStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnnouncementComment extends Model
{
    use HasFactory;

    protected $fillable = [
        'announcement_id',
        'user_id',
        'name',
        'email',
        'comment',
        'status'
    ];

    protected $casts = [
        'status' => ModerationStatus::class,
    ];

    // Relationships
    public function announcement()
    {
        return $this->belongsTo(Announcement::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scopes
    public function scopeApproved($query)
    {
        return $query->where('status', ModerationStatus::Approved);
    }

    public function scopePending($query)
    {
        return $query->where('status', ModerationStatus::Pending);
    }

    // Methods
    public function getCommenterNameAttribute()
    {
        return $this->user ? $this->user->name : $this->name;
    }

    public function getCommenterEmailAttribute()
    {
        return $this->user ? $this->user->email : $this->email;
    }
}

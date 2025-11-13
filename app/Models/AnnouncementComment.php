<?php

namespace App\Models;

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
        return $query->where('status', 'approved');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
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

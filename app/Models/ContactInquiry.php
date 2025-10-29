<?php
// app/Models/ContactInquiry.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContactInquiry extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'school_id',
        'branch_id',
        'user_id',
        'name',
        'email',
        'phone',
        'subject',
        'custom_subject',
        'message',
        'status',
        'admin_notes',
        'assigned_to',
        'responded_at',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'responded_at' => 'datetime',
    ];

    /**
     * Get the school that owns the inquiry.
     */
    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    /**
     * Get the branch that owns the inquiry.
     */
    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    /**
     * Get the user that created the inquiry.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the admin user assigned to this inquiry.
     */
    public function assignedAdmin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    /**
     * Scope a query to only include inquiries for a specific school.
     */
    public function scopeForSchool($query, $schoolId)
    {
        return $query->where('school_id', $schoolId);
    }

    /**
     * Get the full subject (including custom subject if applicable)
     */
    public function getFullSubjectAttribute(): string
    {
        if ($this->subject === 'other' && $this->custom_subject) {
            return $this->custom_subject;
        }

        return match ($this->subject) {
            'admission' => 'Admission Inquiry',
            'tour' => 'Schedule a Tour',
            'general' => 'General Information',
            'feedback' => 'Feedback',
            'other' => 'Other Inquiry',
            default => ucfirst(str_replace('_', ' ', $this->subject)),
        };
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($inquiry) {
            $inquiry->uuid = (string) \Illuminate\Support\Str::uuid();
            $inquiry->ip_address = request()->ip();
            $inquiry->user_agent = request()->userAgent();

            // Set default status if not provided
            if (empty($inquiry->status)) {
                $inquiry->status = 'new';
            }
        });
    }
}

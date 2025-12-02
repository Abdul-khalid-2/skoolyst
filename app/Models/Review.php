<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'user_id',
        'branch_id',
        'review',
        'rating',
        'reviewer_name',
        'reviewer_email',
        'status',
        'admin_notes',
        'created_by_admin',
    ];

    protected $casts = [
        'created_by_admin' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
    // Accessor for reviewer name
    public function getReviewerNameAttribute($value)
    {
        return $value ?? $this->user->name ?? 'Anonymous';
    }

    // Accessor for formatted created date
    public function getFormattedDateAttribute()
    {
        return $this->created_at->format('M d, Y \a\t h:i A');
    }

    // Scope for approved reviews
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    // Scope for pending reviews
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    // Scope for school-admin
    public function scopeForSchool($query, $schoolId)
    {
        return $query->where('school_id', $schoolId);
    }

    // Get rating stars HTML
    public function getRatingStarsAttribute()
    {
        $stars = '';
        for ($i = 1; $i <= 5; $i++) {
            if ($i <= $this->rating) {
                $stars .= '<i class="fas fa-star text-warning"></i>';
            } else {
                $stars .= '<i class="far fa-star text-muted"></i>';
            }
        }
        return $stars;
    }

    // Get status badge HTML
    public function getStatusBadgeAttribute()
    {
        $badges = [
            'pending' => '<span class="badge bg-warning">Pending</span>',
            'approved' => '<span class="badge bg-success">Approved</span>',
            'rejected' => '<span class="badge bg-danger">Rejected</span>',
        ];

        return $badges[$this->status] ?? '<span class="badge bg-secondary">Unknown</span>';
    }
}

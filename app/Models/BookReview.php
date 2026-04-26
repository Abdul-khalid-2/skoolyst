<?php

namespace App\Models;

use App\Enums\BookReviewType;
use App\Enums\ModerationStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BookReview extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'uuid',
        'book_id',
        'user_id',
        'rating',
        'review',
        'pros',
        'cons',
        'review_type',
        'reviewer_role',
        'is_verified_purchase',
        'is_helpful',
        'helpful_count',
        'unhelpful_count',
        'status'
    ];

    protected $casts = [
        'pros' => 'array',
        'cons' => 'array',
        'is_verified_purchase' => 'boolean',
        'is_helpful' => 'boolean',
        'rating' => 'integer',
        'helpful_count' => 'integer',
        'unhelpful_count' => 'integer',
        'review_type' => BookReviewType::class,
        'status' => ModerationStatus::class,
    ];

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->hasOneThrough(Product::class, Book::class, 'id', 'id', 'book_id', 'product_id');
    }

    public function getReviewerNameAttribute()
    {
        if ($this->user) {
            return $this->user->name;
        }

        return 'Anonymous';
    }

    public function getHelpfulPercentageAttribute()
    {
        $total = $this->helpful_count + $this->unhelpful_count;

        if ($total === 0) {
            return 0;
        }

        return ($this->helpful_count / $total) * 100;
    }

    public function markAsHelpful()
    {
        $this->helpful_count++;
        $this->save();
    }

    public function markAsUnhelpful()
    {
        $this->unhelpful_count++;
        $this->save();
    }
}

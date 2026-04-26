<?php

namespace App\Models;

use App\Enums\ActiveStatus;
use App\Enums\BookCategoryType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BookCategory extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'uuid',
        'parent_id',
        'name',
        'slug',
        'description',
        'icon',
        'cover_image',
        'book_type',
        'education_level',
        'board',
        'sort_order',
        'status'
    ];

    protected $casts = [
        'book_type' => BookCategoryType::class,
        'status' => ActiveStatus::class,
    ];

    public function parent()
    {
        return $this->belongsTo(BookCategory::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(BookCategory::class, 'parent_id');
    }

    public function books()
    {
        return $this->hasMany(Book::class);
    }
}

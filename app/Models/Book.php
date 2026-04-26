<?php

namespace App\Models;

use App\Enums\BookCondition;
use App\Enums\BookLanguage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Book extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'uuid',
        'product_id',
        'book_category_id',
        'author',
        'publisher',
        'isbn',
        'edition',
        'publication_year',
        'language',
        'pages',
        'cover_type',
        'education_board',
        'class_level',
        'subject',
        'table_of_contents',
        'sample_pages',
        'preview_link',
        'book_condition',
        'condition_description',
        'is_digital',
        'digital_file',
        'file_format',
        'file_size_mb'
    ];

    protected $casts = [
        'is_digital' => 'boolean',
        'book_condition' => BookCondition::class,
        'language' => BookLanguage::class,
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function category()
    {
        return $this->belongsTo(BookCategory::class, 'book_category_id');
    }

    public function getFullTitleAttribute()
    {
        return "{$this->product->name} - {$this->author} ({$this->edition})";
    }

    public function getConditionColorAttribute()
    {
        return match ($this->book_condition) {
            BookCondition::AsNew => 'success',
            BookCondition::LikeNew => 'primary',
            BookCondition::Good => 'info',
            BookCondition::Fair => 'warning',
            BookCondition::Poor => 'danger',
            default => 'secondary',
        };
    }
}

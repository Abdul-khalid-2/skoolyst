<?php

namespace App\Models;

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
        'book_condition' => 'string',
        'language' => 'string'
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
        $colors = [
            'new' => 'success',
            'like_new' => 'primary',
            'good' => 'info',
            'fair' => 'warning',
            'poor' => 'danger'
        ];

        return $colors[$this->book_condition] ?? 'secondary';
    }
}

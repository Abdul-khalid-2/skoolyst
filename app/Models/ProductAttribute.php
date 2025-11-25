<?php
// app/Models/ProductAttribute.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductAttribute extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'product_id',
        'attribute_type',
        'book_author',
        'book_publisher',
        'book_isbn',
        'book_edition',
        'book_publication_year',
        'education_board',
        'school_name',
        'class_level',
        'subject',
        'medium',
        'copy_pages',
        'copy_quality',
        'copy_size',
        'copy_type',
        'bag_type',
        'bag_compartments',
        'bag_water_resistant',
        'bag_wheels',
        'weight_grams',
        'dimensions'
    ];

    protected $casts = [
        'book_publication_year' => 'integer',
        'copy_pages' => 'integer',
        'bag_compartments' => 'integer',
        'bag_water_resistant' => 'boolean',
        'bag_wheels' => 'boolean',
        'weight_grams' => 'decimal:2'
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function getFormattedDimensionsAttribute()
    {
        if (!$this->dimensions) {
            return null;
        }

        $parts = explode('x', $this->dimensions);
        if (count($parts) === 3) {
            return "{$parts[0]}cm × {$parts[1]}cm × {$parts[2]}cm";
        }

        return $this->dimensions;
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($attribute) {
            $attribute->uuid = (string) \Illuminate\Support\Str::uuid();
        });
    }
}

<?php

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
        'dimensions',
    ];

    protected $casts = [
        'book_publication_year' => 'integer',
        'copy_pages' => 'integer',
        'bag_compartments' => 'integer',
        'bag_water_resistant' => 'boolean',
        'bag_wheels' => 'boolean',
        'weight_grams' => 'decimal:2',
    ];

    /**
     * Get the product that owns the attributes.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Scope a query to only include attributes of a specific type.
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('attribute_type', $type);
    }

    /**
     * Get book-specific attributes as an array.
     */
    public function getBookAttributesAttribute(): array
    {
        if ($this->attribute_type !== 'book') {
            return [];
        }

        return [
            'author' => $this->book_author,
            'publisher' => $this->book_publisher,
            'isbn' => $this->book_isbn,
            'edition' => $this->book_edition,
            'publication_year' => $this->book_publication_year,
            'education_board' => $this->education_board,
            'school_name' => $this->school_name,
            'class_level' => $this->class_level,
            'subject' => $this->subject,
            'medium' => $this->medium,
        ];
    }

    /**
     * Get copy-specific attributes as an array.
     */
    public function getCopyAttributesAttribute(): array
    {
        if ($this->attribute_type !== 'copy') {
            return [];
        }

        return [
            'pages' => $this->copy_pages,
            'quality' => $this->copy_quality,
            'size' => $this->copy_size,
            'type' => $this->copy_type,
        ];
    }

    /**
     * Get bag-specific attributes as an array.
     */
    public function getBagAttributesAttribute(): array
    {
        if ($this->attribute_type !== 'bag') {
            return [];
        }

        return [
            'type' => $this->bag_type,
            'compartments' => $this->bag_compartments,
            'water_resistant' => $this->bag_water_resistant,
            'wheels' => $this->bag_wheels,
        ];
    }

    /**
     * Check if the attribute has book data.
     */
    public function hasBookData(): bool
    {
        return $this->attribute_type === 'book' && (
            $this->book_author ||
            $this->book_publisher ||
            $this->book_isbn
        );
    }

    /**
     * Check if the attribute has copy data.
     */
    public function hasCopyData(): bool
    {
        return $this->attribute_type === 'copy' && (
            $this->copy_pages ||
            $this->copy_quality ||
            $this->copy_size
        );
    }

    /**
     * Check if the attribute has bag data.
     */
    public function hasBagData(): bool
    {
        return $this->attribute_type === 'bag' && (
            $this->bag_type ||
            $this->bag_compartments
        );
    }
}

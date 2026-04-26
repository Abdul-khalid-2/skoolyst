<?php

namespace App\Models;

use App\Enums\ActiveStatus;
use App\Enums\BranchImageType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BranchImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'branch_id',
        'image_path',
        'image_name',
        'image_unique_name',
        'title',
        'caption',
        'type',
        'sort_order',
        'is_featured',
        'is_main_banner',
        'status'
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'is_main_banner' => 'boolean',
        'sort_order' => 'integer',
        'type' => BranchImageType::class,
        'status' => ActiveStatus::class,
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public static function getTypeOptions(): array
    {
        return [
            'gallery' => 'Gallery',
            'banner' => 'Banner',
            'infrastructure' => 'Infrastructure',
            'events' => 'Events',
            'classroom' => 'Classroom',
        ];
    }
}

<?php

namespace App\Models;

use App\Enums\ContentStatus;
use App\Enums\StudyMaterialType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudyMaterial extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'uuid',
        'user_id',
        'subject_id',
        'test_type_id',
        'title',
        'description',
        'material_type',
        'file_path',
        'file_type',
        'file_size',
        'download_count',
        'page_count',
        'is_free',
        'price',
        'status',
        'tags',
        'view_count'
    ];

    protected $casts = [
        'is_free' => 'boolean',
        'tags' => 'array',
        'download_count' => 'integer',
        'view_count' => 'integer',
        'page_count' => 'integer',
        'file_size' => 'integer',
        'material_type' => StudyMaterialType::class,
        'status' => ContentStatus::class,
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function testType()
    {
        return $this->belongsTo(TestType::class);
    }

    public function incrementDownloadCount()
    {
        $this->download_count++;
        $this->save();
    }

    public function incrementViewCount()
    {
        $this->view_count++;
        $this->save();
    }

    public function getFileSizeFormattedAttribute()
    {
        $size = $this->file_size;

        if ($size >= 1073741824) {
            return number_format($size / 1073741824, 2) . ' GB';
        } elseif ($size >= 1048576) {
            return number_format($size / 1048576, 2) . ' MB';
        } elseif ($size >= 1024) {
            return number_format($size / 1024, 2) . ' KB';
        } else {
            return $size . ' bytes';
        }
    }

    public function getFileIconAttribute()
    {
        $icons = [
            'pdf' => 'fa-file-pdf',
            'doc' => 'fa-file-word',
            'docx' => 'fa-file-word',
            'ppt' => 'fa-file-powerpoint',
            'pptx' => 'fa-file-powerpoint',
            'xls' => 'fa-file-excel',
            'xlsx' => 'fa-file-excel',
            'txt' => 'fa-file-alt',
            'zip' => 'fa-file-archive',
            'rar' => 'fa-file-archive',
        ];

        $extension = strtolower($this->file_type);

        return $icons[$extension] ?? 'fa-file';
    }

    public function scopePublished($query)
    {
        return $query->where('status', ContentStatus::Published);
    }

    public function scopeFree($query)
    {
        return $query->where('is_free', true);
    }

    public function scopePremium($query)
    {
        return $query->where('is_free', false);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SchoolTranslation extends Model
{
    public const LOCALE_UR = 'ur';

    protected $fillable = [
        'school_id',
        'locale',
        'name',
        'description',
        'facilities',
        'banner_title',
        'banner_tagline',
        'school_motto',
        'mission',
        'vision',
    ];

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }
}

<?php

namespace App\Services;

use App\Models\School;
use App\Models\SchoolTranslation;

class SchoolTranslationService
{
    /**
     * Sync Urdu (and future non-default) rows from the admin request.
     * English remains on schools + school_profiles.
     */
    public static function syncFromRequest(School $school, ?array $translations): void
    {
        if (empty($translations) || !is_array($translations)) {
            return;
        }

        $ur = $translations[SchoolTranslation::LOCALE_UR] ?? null;
        if (!is_array($ur)) {
            return;
        }

        $allowed = [
            'name', 'description', 'facilities', 'banner_title', 'banner_tagline',
            'school_motto', 'mission', 'vision',
        ];

        $payload = ['locale' => SchoolTranslation::LOCALE_UR];
        foreach ($allowed as $key) {
            $val = $ur[$key] ?? null;
            $payload[$key] = ($val === null || $val === '') ? null : $val;
        }

        $hasText = false;
        foreach ($allowed as $key) {
            if (! empty($payload[$key])) {
                $hasText = true;
                break;
            }
        }

        if (! $hasText) {
            $school->translations()->where('locale', SchoolTranslation::LOCALE_UR)->delete();

            return;
        }

        $school->translations()->updateOrCreate(
            ['locale' => SchoolTranslation::LOCALE_UR],
            $payload
        );
    }
}

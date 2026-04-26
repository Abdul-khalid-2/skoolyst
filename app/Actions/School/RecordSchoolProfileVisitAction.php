<?php

namespace App\Actions\School;

use App\Models\School;

class RecordSchoolProfileVisitAction
{
    public function execute(School $school): void
    {
        if (! $school->profile) {
            return;
        }

        $school->profile->increment('visitor_count');
        $school->profile->update(['last_visited_at' => now()]);
    }
}

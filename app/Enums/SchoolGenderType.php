<?php

namespace App\Enums;

enum SchoolGenderType: string
{
    case CoEd = 'co-education';
    case Boys = 'boys';
    case Girls = 'girls';
    case SeparateCampuses = 'separate-campuses';

    public function label(): string
    {
        return match ($this) {
            self::CoEd => 'Co-Education',
            self::Boys => 'Boys',
            self::Girls => 'Girls',
            self::SeparateCampuses => 'Boys & Girls (Separate Campuses)',
        };
    }
}

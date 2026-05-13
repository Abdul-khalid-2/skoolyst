<?php

namespace App\Enums;

enum SchoolGenderType: string
{
    case CoEd = 'co-education';
    case Boys = 'boys';
    case Girls = 'girls';

    public function label(): string
    {
        return match ($this) {
            self::CoEd => 'Co-Education',
            self::Boys => 'Boys',
            self::Girls => 'Girls',
        };
    }
}

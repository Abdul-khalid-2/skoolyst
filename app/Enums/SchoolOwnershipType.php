<?php

namespace App\Enums;

enum SchoolOwnershipType: string
{
    case Private = 'private';
    case Government = 'government';
    case SemiGovernment = 'semi-government';
    case NGO = 'ngo';

    public function label(): string
    {
        return match ($this) {
            self::Private => 'Private',
            self::Government => 'Government',
            self::SemiGovernment => 'Semi-Government',
            self::NGO => 'NGO',
        };
    }
}

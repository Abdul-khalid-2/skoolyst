<?php

namespace App\Enums;

enum SchoolType: string
{
    case CoEd = 'Co-Ed';
    case Boys = 'Boys';
    case Girls = 'Girls';
    case Separate = 'Separate';
}

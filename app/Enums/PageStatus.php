<?php

namespace App\Enums;

enum PageStatus: string
{
    case Active = 'active';
    case Pending = 'pending';
    case Inactive = 'inactive';
}

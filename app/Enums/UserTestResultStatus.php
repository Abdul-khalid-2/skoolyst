<?php

namespace App\Enums;

enum UserTestResultStatus: string
{
    case Passed = 'passed';
    case Failed = 'failed';
    case Pending = 'pending';
}

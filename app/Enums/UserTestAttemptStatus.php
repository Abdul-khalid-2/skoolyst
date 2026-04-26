<?php

namespace App\Enums;

enum UserTestAttemptStatus: string
{
    case InProgress = 'in_progress';
    case Completed = 'completed';
    case Expired = 'expired';
    case Abandoned = 'abandoned';
}

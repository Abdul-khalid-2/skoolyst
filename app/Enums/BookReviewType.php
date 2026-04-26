<?php

namespace App\Enums;

enum BookReviewType: string
{
    case Academic = 'academic';
    case Competitive = 'competitive';
    case General = 'general';
}

<?php

namespace App\Enums;

enum BookCondition: string
{
    case AsNew = 'new';
    case LikeNew = 'like_new';
    case Good = 'good';
    case Fair = 'fair';
    case Poor = 'poor';
}

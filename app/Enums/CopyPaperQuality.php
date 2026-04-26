<?php

namespace App\Enums;

enum CopyPaperQuality: string
{
    case Premium = 'premium';
    case Standard = 'standard';
    case Economy = 'economy';
}

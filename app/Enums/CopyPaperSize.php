<?php

namespace App\Enums;

enum CopyPaperSize: string
{
    case A4 = 'a4';
    case A5 = 'a5';
    case Quarter = 'quarter';
    case Half = 'half';
    case Other = 'other';
}

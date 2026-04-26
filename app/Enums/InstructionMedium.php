<?php

namespace App\Enums;

/**
 * Textbook / instruction language mix (e.g. product attribute `medium` column).
 */
enum InstructionMedium: string
{
    case English = 'english';
    case Urdu = 'urdu';
    case Bilingual = 'bilingual';
    case Other = 'other';
}

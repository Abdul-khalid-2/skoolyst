<?php

namespace App\Enums;

enum StudyMaterialType: string
{
    case Notes = 'notes';
    case PastPapers = 'past_papers';
    case FormulaSheet = 'formula_sheet';
    case Summary = 'summary';
    case Other = 'other';
}

<?php

namespace App\Enums;

enum RuledCopyType: string
{
    case SingleLine = 'single_line';
    case DoubleLine = 'double_line';
    case FourLine = 'four_line';
    case Plain = 'plain';
    case Graph = 'graph';
}

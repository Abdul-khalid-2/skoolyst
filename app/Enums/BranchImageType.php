<?php

namespace App\Enums;

enum BranchImageType: string
{
    case Gallery = 'gallery';
    case Banner = 'banner';
    case Infrastructure = 'infrastructure';
    case Events = 'events';
    case Classroom = 'classroom';
}

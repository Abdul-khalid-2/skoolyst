<?php

namespace App\Enums;

enum VideoPublishStatus: string
{
    case Draft = 'draft';
    case Published = 'published';
    case VideoPrivate = 'private';
}

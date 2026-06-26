<?php

namespace App\Enums;

enum BranchImageType: string
{
    case Gallery = 'gallery';
    case Banner = 'banner';
    case Infrastructure = 'infrastructure';
    case Events = 'events';
    case Classroom = 'classroom';

    public function label(): string
    {
        return match ($this) {
            self::Gallery => 'Gallery',
            self::Banner => 'Banner',
            self::Infrastructure => 'Infrastructure',
            self::Events => 'Events',
            self::Classroom => 'Classroom',
        };
    }
}

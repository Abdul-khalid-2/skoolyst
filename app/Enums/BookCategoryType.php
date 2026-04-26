<?php

namespace App\Enums;

enum BookCategoryType: string
{
    case Academic = 'academic';
    case Competitive = 'competitive';
    case Story = 'story';
    case Religious = 'religious';
    case GeneralKnowledge = 'general_knowledge';
    case Magazine = 'magazine';
    case Newspaper = 'newspaper';
}

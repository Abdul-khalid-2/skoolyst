<?php

namespace App\Enums;

enum ShopAssociationType: string
{
    case Preferred = 'preferred';
    case Official = 'official';
    case Affiliated = 'affiliated';
    case General = 'general';
}

<?php

namespace App\Enums;

enum ShopType: string
{
    case Stationery = 'stationery';
    case BookStore = 'book_store';
    case Mixed = 'mixed';
    case SchoolAffiliated = 'school_affiliated';
}

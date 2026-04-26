<?php

namespace App\Enums;

enum SchoolBagType: string
{
    case SchoolBag = 'school_bag';
    case Backpack = 'backpack';
    case Satchel = 'satchel';
    case LunchBag = 'lunch_bag';
    case Other = 'other';
}

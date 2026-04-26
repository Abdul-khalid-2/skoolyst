<?php

namespace App\Enums;

enum ProductType: string
{
    case Book = 'book';
    case Copy = 'copy';
    case Stationery = 'stationery';
    case Bag = 'bag';
    case Uniform = 'uniform';
    case Other = 'other';
}

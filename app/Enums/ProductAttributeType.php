<?php

namespace App\Enums;

enum ProductAttributeType: string
{
    case Book = 'book';
    case Copy = 'copy';
    case Bag = 'bag';
    case Uniform = 'uniform';
}

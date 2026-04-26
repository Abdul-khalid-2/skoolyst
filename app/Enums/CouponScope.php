<?php

namespace App\Enums;

enum CouponScope: string
{
    case GlobalScope = 'global';
    case ShopSpecific = 'shop_specific';
    case SchoolSpecific = 'school_specific';
    case ProductSpecific = 'product_specific';
}

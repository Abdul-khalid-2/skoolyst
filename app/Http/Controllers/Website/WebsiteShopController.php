<?php

namespace App\Http\Controllers\Website;

use App\Enums\ModerationStatus;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Shop;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Enums\SchoolGenderType;
use App\Enums\SchoolOwnershipType;
use App\Models\ShopSchoolAssociation;

class WebsiteShopController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Get filter parameters
        $search = $request->input('search');
        $schoolGenderType = $request->input('school_gender_type');
        $schoolOwnershipType = $request->input('school_ownership_type');
        $city = $request->input('city');
        $shopType = $request->input('shop_type');

        $shopsQuery = Shop::with(['schoolAssociations.school', 'products'])
            ->where('is_active', true)
            ->where('is_verified', true);

        // Apply search filter
        if ($search) {
            $shopsQuery->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhereHas('products', function ($productQuery) use ($search) {
                        $productQuery->where('name', 'like', "%{$search}%")
                            ->orWhere('description', 'like', "%{$search}%");
                    });
            });
        }

        // Apply school gender / ownership filters (associated schools)
        if ($schoolGenderType) {
            $shopsQuery->whereHas('schoolAssociations.school', function ($q) use ($schoolGenderType) {
                $q->where('school_gender_type', $schoolGenderType);
            });
        }

        if ($schoolOwnershipType) {
            $shopsQuery->whereHas('schoolAssociations.school', function ($q) use ($schoolOwnershipType) {
                $q->where('school_ownership_type', $schoolOwnershipType);
            });
        }

        // Apply city filter
        if ($city) {
            $shopsQuery->where('city', 'like', "%{$city}%");
        }

        // Apply shop type filter
        if ($shopType) {
            $shopsQuery->where('shop_type', $shopType);
        }

        // Get shops with pagination
        $shops = $shopsQuery->paginate(12);

        $schoolGenderTypes = collect(SchoolGenderType::cases())->mapWithKeys(function ($case) {
            return [$case->value => $case->label()];
        });

        $schoolOwnershipTypes = collect(SchoolOwnershipType::cases())->mapWithKeys(function ($case) {
            return [$case->value => $case->label()];
        });

        // Get unique cities for filter
        $cities = Shop::where('is_active', true)
            ->distinct()
            ->pluck('city')
            ->filter();

        // Get featured products from shops with school associations
        $featuredProducts = Product::with(['shop', 'category', 'attributes'])
            ->where('is_active', true)
            ->where('is_approved', true)
            ->where('is_featured', true)
            ->whereHas('shop.schoolAssociations', function ($query) {
                $query->where('is_active', true)
                    ->where('status', ModerationStatus::Approved);
            })
            ->inRandomOrder()
            ->limit(6)
            ->get();

        // Get product categories
        $categories = ProductCategory::where('is_active', true)
            ->whereHas('products', function ($query) {
                $query->where('is_active', true)
                    ->where('is_approved', true);
                // ->whereHas('shop.schoolAssociations', function ($q) {
                //     $q->where('is_active', true)
                //         ->where('status', ModerationStatus::Approved);
                // });
            })
            ->withCount(['products' => function ($query) {
                $query->where('is_active', true)
                    ->where('is_approved', true);
                // ->whereHas('shop.schoolAssociations', function ($q) {
                //     $q->where('is_active', true)
                //         ->where('status', ModerationStatus::Approved);
                // });

            }])
            ->having('products_count', '>', 0)
            ->get();

        return view('website.shops.index', compact(
            'shops',
            'featuredProducts',
            'categories',
            'schoolGenderTypes',
            'schoolOwnershipTypes',
            'cities',
            'search',
            'schoolGenderType',
            'schoolOwnershipType',
            'city',
            'shopType'
        ));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $uuid)
    {
        $shop = Shop::with([
            'schoolAssociations.school',
            'products' => function ($query) {
                $query->where('is_active', true)
                    ->where('is_approved', true);
            },
            'products.category',
            'products.attributes'
        ])->where('uuid', $uuid)
            ->where('is_active', true)
            ->firstOrFail();

        return view('website.shops.show', compact('shop'));
    }
}

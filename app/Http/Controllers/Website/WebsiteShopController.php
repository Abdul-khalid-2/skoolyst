<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Shop;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\School;
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
        $schoolType = $request->input('school_type');
        $city = $request->input('city');
        $shopType = $request->input('shop_type');

        // Start query for shops with school associations
        $shopsQuery = Shop::with(['schoolAssociations.school', 'products'])
            ->where('is_active', true)
            ->where('is_verified', true)
            ->where(function ($query) {
                $query->whereHas('schoolAssociations', function ($subQuery) {
                    $subQuery->where('is_active', true)
                        ->where('status', 'approved');
                })
                    ->orWhereDoesntHave('schoolAssociations');
            });

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

        // Apply school type filter
        if ($schoolType) {
            $shopsQuery->whereHas('schoolAssociations.school', function ($q) use ($schoolType) {
                $q->where('school_type', $schoolType);
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

        // Get unique school types from associated schools for filter
        $schoolTypes = School::whereHas('shopAssociations', function ($query) {
            $query->where('is_active', true)
                ->where('status', 'approved');
        })->distinct()->pluck('school_type');

        // Get unique cities for filter
        $cities = Shop::where('is_active', true)
            ->whereHas('schoolAssociations')
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
                    ->where('status', 'approved');
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
                //         ->where('status', 'approved');
                // });
            })
            ->withCount(['products' => function ($query) {
                $query->where('is_active', true)
                    ->where('is_approved', true)
                    ->whereHas('shop.schoolAssociations', function ($q) {
                        $q->where('is_active', true)
                            ->where('status', 'approved');
                    });
            }])
            ->having('products_count', '>', 0)
            ->get();

        return view('website.shops.index', compact(
            'shops',
            'featuredProducts',
            'categories',
            'schoolTypes',
            'cities',
            'search',
            'schoolType',
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

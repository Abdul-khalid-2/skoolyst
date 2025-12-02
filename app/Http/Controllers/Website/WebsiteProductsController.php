<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Shop;
use App\Models\School;
use App\Models\ShopSchoolAssociation;
use App\Models\ProductAttribute;

class WebsiteProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Get filter parameters
        $search = $request->input('search');
        $category = $request->input('category');
        $shop = $request->input('shop');
        $school = $request->input('school');
        $productType = $request->input('product_type');
        $educationBoard = $request->input('education_board');
        $classLevel = $request->input('class_level');
        $minPrice = $request->input('min_price');
        $maxPrice = $request->input('max_price');
        $sortBy = $request->input('sort_by', 'created_at');
        $sortOrder = $request->input('sort_order', 'desc');

        // Start query for products from shops with school associations
        $productsQuery = Product::with([
            'shop',
            'category',
            'attributes',
            'shop.schoolAssociations.school'
        ])
            ->where('is_active', true)
            ->where('is_approved', true)
            ->where('stock_quantity', '>', 1);
        $productsQuery->where(function ($query) {
            $query->whereHas('shop.schoolAssociations', function ($q) {
                $q->where('is_active', true)
                    ->where('status', 'approved');
            })->orWhereDoesntHave('shop.schoolAssociations', function ($q) {
                $q->where('is_active', true)
                    ->where('status', 'approved');
            });
        });

        // Apply search filter
        if ($search) {
            $productsQuery->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('short_description', 'like', "%{$search}%")
                    ->orWhereHas('category', function ($categoryQuery) use ($search) {
                        $categoryQuery->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('shop', function ($shopQuery) use ($search) {
                        $shopQuery->where('name', 'like', "%{$search}%");
                    });
            });
        }

        // Apply category filter
        if ($category) {
            $productsQuery->whereHas('category', function ($q) use ($category) {
                $q->where('slug', $category);
            });
        }

        // Apply shop filter
        if ($shop) {
            $productsQuery->whereHas('shop', function ($q) use ($shop) {
                $q->where('uuid', $shop);
            });
        }

        // Apply school filter
        if ($school) {
            $productsQuery->whereHas('shop.schoolAssociations.school', function ($q) use ($school) {
                $q->where('uuid', $school);
            });
        }

        // Apply product type filter
        if ($productType) {
            $productsQuery->where('product_type', $productType);
        }

        // Apply education board filter
        if ($educationBoard) {
            $productsQuery->whereHas('attributes', function ($q) use ($educationBoard) {
                $q->where('education_board', $educationBoard);
            });
        }

        // Apply class level filter
        if ($classLevel) {
            $productsQuery->whereHas('attributes', function ($q) use ($classLevel) {
                $q->where('class_level', $classLevel);
            });
        }

        // Apply price range filter
        if ($minPrice) {
            $productsQuery->where(function ($q) use ($minPrice) {
                $q->where('sale_price', '>=', $minPrice)
                    ->orWhere('base_price', '>=', $minPrice);
            });
        }
        if ($maxPrice) {
            $productsQuery->where(function ($q) use ($maxPrice) {
                $q->where('sale_price', '<=', $maxPrice)
                    ->orWhere('base_price', '<=', $maxPrice);
            });
        }

        // Apply sorting
        switch ($sortBy) {
            case 'price_low':
                $productsQuery->orderByRaw('COALESCE(sale_price, base_price) ASC');
                break;
            case 'price_high':
                $productsQuery->orderByRaw('COALESCE(sale_price, base_price) DESC');
                break;
            case 'name':
                $productsQuery->orderBy('name', $sortOrder);
                break;
            case 'rating':
                // You might want to add rating to products table
                $productsQuery->orderBy('created_at', $sortOrder);
                break;
            default:
                $productsQuery->orderBy('created_at', $sortOrder);
        }

        // Get products with pagination
        $products = $productsQuery->paginate(16);

        // Get filter data
        $categories = ProductCategory::where('is_active', true)
            ->whereHas('products', function ($query) {
                $query->where('is_active', true)
                    ->where('is_approved', true)
                    ->where('stock_quantity', '>', 1)
                    ->where(function ($q) {
                        $q->whereHas('shop.schoolAssociations', function ($schoolQ) {
                            $schoolQ->where('is_active', true)
                                ->where('status', 'approved');
                        })->orWhereDoesntHave('shop.schoolAssociations', function ($schoolQ) {
                            $schoolQ->where('is_active', true)
                                ->where('status', 'approved');
                        });
                    });
            })
            ->get();

        $shops = Shop::where('is_active', true)
            ->where('is_verified', true)
            ->get();

        $schools = School::whereHas('shopAssociations', function ($query) {
            $query->where('is_active', true)
                ->where('status', 'approved');
        })->get();

        // Get unique product types
        $productTypes = Product::where('is_active', true)
            ->where('is_approved', true)
            ->where('stock_quantity', '>', 1)
            ->where(function ($query) {
                $query->whereHas('shop.schoolAssociations', function ($q) {
                    $q->where('is_active', true)
                        ->where('status', 'approved');
                })->orWhereDoesntHave('shop.schoolAssociations', function ($q) {
                    $q->where('is_active', true)
                        ->where('status', 'approved');
                });
            })
            ->distinct()
            ->pluck('product_type')
            ->filter();

        // Get unique education boards
        $educationBoards = ProductAttribute::whereHas('product', function ($query) {
            $query->where('is_active', true)
                ->where('is_approved', true)
                ->where('stock_quantity', '>', 1)
                ->where(function ($q) {
                    $q->whereHas('shop.schoolAssociations', function ($schoolQ) {
                        $schoolQ->where('is_active', true)
                            ->where('status', 'approved');
                    })
                        ->orWhereDoesntHave('shop.schoolAssociations', function ($schoolQ) {
                            $schoolQ->where('is_active', true)
                                ->where('status', 'approved');
                        });
                });
        })
            ->whereNotNull('education_board')
            ->distinct()
            ->pluck('education_board');

        // Get unique class levels
        $classLevels = ProductAttribute::whereHas('product', function ($query) {
            $query->where('is_active', true)
                ->where('is_approved', true)
                ->where('stock_quantity', '>', 1)
                ->where(function ($q) {
                    $q->whereHas('shop.schoolAssociations', function ($schoolQ) {
                        $schoolQ->where('is_active', true)
                            ->where('status', 'approved');
                    })->orWhereDoesntHave('shop.schoolAssociations', function ($schoolQ) {
                        $schoolQ->where('is_active', true)
                            ->where('status', 'approved');
                    });
                });
        })
            ->whereNotNull('class_level')
            ->distinct()
            ->pluck('class_level');

        return view('website.products.index', compact(
            'products',
            'categories',
            'shops',
            'schools',
            'productTypes',
            'educationBoards',
            'classLevels',
            'search',
            'category',
            'shop',
            'school',
            'productType',
            'educationBoard',
            'classLevel',
            'minPrice',
            'maxPrice',
            'sortBy',
            'sortOrder'
        ));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $uuid)
    {
        $product = Product::with([
            'shop',
            'category',
            'attributes',
            'shop.schoolAssociations.school'
        ])
            ->where('uuid', $uuid)
            ->where('is_active', true)
            ->where('is_approved', true)
            ->where('stock_quantity', '>', 1)
            ->where(function ($query) {
                $query->whereHas('shop.schoolAssociations', function ($q) {
                    $q->where('is_active', true)
                        ->where('status', 'approved');
                })->orWhereDoesntHave('shop.schoolAssociations', function ($q) {
                    $q->where('is_active', true)
                        ->where('status', 'approved');
                });
            })
            ->firstOrFail();

        // Get related products from the same shop or category
        $relatedProducts = Product::with(['shop', 'category'])
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->where('is_approved', true)
            ->where('stock_quantity', '>', 1)
            ->where(function ($query) {
                $query->whereHas('shop.schoolAssociations', function ($q) {
                    $q->where('is_active', true)
                        ->where('status', 'approved');
                })->orWhereDoesntHave('shop.schoolAssociations', function ($q) {
                    $q->where('is_active', true)
                        ->where('status', 'approved');
                });
            })
            ->where(function ($query) use ($product) {
                $query->where('shop_id', $product->shop_id)
                    ->orWhere('category_id', $product->category_id);
            })
            ->inRandomOrder()
            ->limit(4)
            ->get();

        return view('website.products.show', compact('product', 'relatedProducts'));
    }
}

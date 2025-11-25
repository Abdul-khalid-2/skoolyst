<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use App\Models\School;
use App\Models\ShopSchoolAssociation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ShopController extends Controller
{
    public function __construct()
    {
        // $this->authorizeResource(Shop::class, 'shop');
    }

    public function index(Request $request)
    {
        $query = Shop::with(['user', 'schoolAssociations.school']);

        if (Auth::user()->hasRole('shop-owner')) {
            $query->where('user_id', Auth::id());
        } elseif (Auth::user()->hasRole('school-admin')) {
            // Fix: Use the school() relationship instead of schools()
            $schoolId = Auth::user()->school_id;

            if ($schoolId) {
                $query->whereHas('schoolAssociations', function ($q) use ($schoolId) {
                    $q->where('school_id', $schoolId)
                        ->where('status', 'approved')
                        ->where('is_active', true);
                });
            } else {
                // If user has no school assigned, return empty results
                $query->where('id', 0);
            }
        }

        // Apply filters
        if ($request->has('city')) {
            $query->where('city', $request->city);
        }

        if ($request->has('shop_type')) {
            $query->where('shop_type', $request->shop_type);
        }

        if ($request->has('is_verified')) {
            $query->where('is_verified', $request->boolean('is_verified'));
        }

        $shops = $query->paginate(15);

        return view('dashboard.shops.index', compact('shops'));
    }

    public function create()
    {
        return view('dashboard.shops.create');
    }

    public function store(Request $request)
    {

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'shop_type' => 'required|in:stationery,book_store,mixed,school_affiliated',
            'description' => 'nullable|string',
            'logo_url' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'banner_url' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'is_verified' => 'boolean',
            'is_active' => 'boolean',
            'rating' => 'nullable|numeric|between:0,5',
            'total_reviews' => 'nullable|integer|min:0',

            // Shop Owner Information
            'owner_name' => 'required|string|max:255',
            'owner_email' => 'required|email|unique:users,email',
            'owner_phone' => 'nullable|string|max:20',
            'owner_password' => 'required|string|min:8|confirmed',
        ]);



        // try {
        // Create Shop Owner User
        $owner = User::create([
            'uuid' => Str::uuid(),
            'name' => $validated['owner_name'],
            'email' => $validated['owner_email'],
            'phone' => $validated['owner_phone'] ?? null,
            'password' => $validated['owner_password'],
        ]);

        // Assign shop-owner role
        $owner->assignRole('shop-owner');

        // Set default values for shop
        $validated['user_id'] = $owner->id; // Use the created owner's ID
        $validated['slug'] = $this->generateSlug($validated['name']);
        $validated['is_verified'] = $request->boolean('is_verified');
        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['rating'] = $validated['rating'] ?? 0.00;
        $validated['total_reviews'] = $validated['total_reviews'] ?? 0;

        // Create shop
        $shop = Shop::create($validated);

        // Handle file uploads after shop creation
        if ($request->hasFile('logo_url')) {
            $logoPath = Storage::disk('website')
                ->putFile("shop/{$shop->id}/logo", $request->file('logo_url'));
            $shop->update(['logo_url' => $logoPath]);
        }

        if ($request->hasFile('banner_url')) {
            $bannerPath = Storage::disk('website')
                ->putFile("shop/{$shop->id}/banner", $request->file('banner_url'));
            $shop->update(['banner_url' => $bannerPath]);
        }


        return redirect()->route('shops.show', $shop)->with('success', 'Shop created successfully with owner account.');
        // } catch (\Exception $e) {
        //     return back()->with('error', 'Failed to create shop: ' . $e->getMessage());
        // }
    }

    public function show(Shop $shop)
    {
        $shop->load(['user', 'schoolAssociations.school', 'products.category']);
        return view('dashboard.shops.show', compact('shop'));
    }

    public function edit(Shop $shop)
    {
        return view('dashboard.shops.edit', compact('shop'));
    }

    public function update(Request $request, Shop $shop)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'nullable|email',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'shop_type' => 'sometimes|in:stationery,book_store,mixed,school_affiliated',
            'description' => 'nullable|string',
            'logo_url' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'banner_url' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'is_verified' => 'boolean',
            'is_active' => 'boolean',
            'rating' => 'nullable|numeric|between:0,5',
            'total_reviews' => 'nullable|integer|min:0',
        ]);

        if (isset($validated['name']) && $validated['name'] !== $shop->name) {
            $validated['slug'] = $this->generateSlug($validated['name']);
        }

        $validated['is_verified'] = $request->boolean('is_verified');
        $validated['is_active'] = $request->boolean('is_active');

        // Handle file uploads
        if ($request->hasFile('logo_url')) {
            $logoPath = Storage::disk('website')
                ->putFile("shop/{$shop->id}/logo", $request->file('logo_url'));
            $validated['logo_url'] = $logoPath;
        }

        if ($request->hasFile('banner_url')) {
            $bannerPath = Storage::disk('website')
                ->putFile("shop/{$shop->id}/banner", $request->file('banner_url'));
            $validated['banner_url'] = $bannerPath;
        }

        $shop->update($validated);

        return redirect()->route('shops.show', $shop)->with('success', 'Shop updated successfully.');
    }

    public function destroy(Shop $shop)
    {
        $shop->delete();
        return redirect()->route('shops.index')->with('success', 'Shop deleted successfully.');
    }

    public function associateSchool(Request $request, Shop $shop)
    {
        // Gate::authorize('manage-associations', $shop);

        $validated = $request->validate([
            'school_id' => 'required|exists:schools,id',
            'association_type' => 'required|in:preferred,official,affiliated,general',
            'discount_percentage' => 'nullable|numeric|min:0|max:100',
            'can_add_products' => 'boolean',
            'can_manage_products' => 'boolean',
            'can_view_analytics' => 'boolean',
            'notes' => 'nullable|string',
        ]);

        // Check if association already exists
        $existingAssociation = \App\Models\ShopSchoolAssociation::where('shop_id', $shop->id)
            ->where('school_id', $validated['school_id'])
            ->first();

        if ($existingAssociation) {
            return back()->with('error', 'Association with this school already exists.');
        }

        $validated['created_by'] = Auth::id();
        $validated['shop_id'] = $shop->id;
        $validated['can_add_products'] = $request->boolean('can_add_products', true);
        $validated['can_manage_products'] = $request->boolean('can_manage_products', true);
        $validated['can_view_analytics'] = $request->boolean('can_view_analytics', true);

        // Auto-approve if user is super admin
        if (Auth::user()->hasRole('super_admin')) {
            $validated['status'] = 'approved';
            $validated['approved_at'] = now();
            $validated['approved_by'] = Auth::id();
        }

        $association = \App\Models\ShopSchoolAssociation::create($validated);

        return back()->with('success', 'School association request created successfully.');
    }

    public function getAssociations(Shop $shop)
    {
        // Gate::authorize('view-associations', $shop);

        $associations = $shop->schoolAssociations()
            ->with(['school', 'createdBy', 'approvedBy'])
            ->get();

        return view('dashboard.shops.associations', compact('shop', 'associations'));
    }

    private function generateSlug($name)
    {
        $slug = \Illuminate\Support\Str::slug($name);
        $count = Shop::where('slug', 'LIKE', "{$slug}%")->count();
        return $count ? "{$slug}-{$count}" : $slug;
    }
}

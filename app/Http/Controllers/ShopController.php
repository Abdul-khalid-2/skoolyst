<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shop;
use App\Models\School;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        $query = Shop::with(['school', 'products'])
            ->withCount('products');

        // Apply role-based filtering
        if (auth()->user()->hasRole('super-admin')) {
            // Super admin can see all shops
            // No additional filtering needed
        } elseif (auth()->user()->hasRole('school-admin')) {
            // School admin can only see shops from their school
            $query->where('school_id', auth()->user()->school_id);
        } elseif (auth()->user()->hasRole('shop-owner')) {
            // Shop owner can only see their own shops
            $query->where('user_id', auth()->id());
        } else {
            return redirect()->route('dashboard')->with('error', 'Unauthorized access.');
        }

        // Apply filters
        if ($request->has('type') && $request->type) {
            $query->where('shop_type', $request->type);
        }

        if ($request->has('status') && $request->status) {
            switch ($request->status) {
                case 'active':
                    $query->where('is_active', true);
                    break;
                case 'inactive':
                    $query->where('is_active', false);
                    break;
                case 'verified':
                    $query->where('is_verified', true);
                    break;
                case 'unverified':
                    $query->where('is_verified', false);
                    break;
            }
        }

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('city', 'LIKE', "%{$search}%")
                    ->orWhere('state', 'LIKE', "%{$search}%");
            });
        }

        $shops = $query->latest()->paginate(10);

        return view('dashboard.shop.index', compact('shops'));
    }

    public function create()
    {
        $schools = [];
        if (auth()->user()->hasRole('super-admin')) {
            $schools = School::where('is_active', true)->get();
        } elseif (auth()->user()->hasRole('school-admin')) {
            // School admin can only create shops for their school
            $schools = School::where('id', auth()->user()->school_id)->get();
        }

        return view('dashboard.shop.create', compact('schools'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'shop_type' => 'required|in:stationery,book_store,mixed,school_affiliated',
            'description' => 'nullable|string',
            'email' => 'nullable|email',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'banner' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'is_active' => 'boolean',
            'is_verified' => 'boolean',
            'school_id' => 'nullable|exists:schools,id',
        ]);

        // Auto-set school_id for school admins
        if (auth()->user()->hasRole('school-admin') && auth()->user()->school_id) {
            $validated['school_id'] = auth()->user()->school_id;
        }

        // Generate slug from name
        $validated['slug'] = Str::slug($validated['name']);

        // Ensure slug is unique
        $counter = 1;
        $originalSlug = $validated['slug'];
        while (Shop::where('slug', $validated['slug'])->exists()) {
            $validated['slug'] = $originalSlug . '-' . $counter;
            $counter++;
        }

        // Set user_id
        $validated['user_id'] = auth()->id();

        if ($request->hasFile('logo')) {
            $logoPath = Storage::disk('website')
                ->putFile("shops/logo", $request->file('logo'));
            $validated['logo_url'] = $logoPath;
        }

        if ($request->hasFile('banner')) {
            $bannerPath = Storage::disk('website')
                ->putFile("shops/banner", $request->file('banner'));
            $validated['banner_url'] = $bannerPath;
        }

        $validated['uuid'] = Str::uuid();

        // Create shop
        $shop = Shop::create($validated);

        return redirect()->route('admin.shops.index')
            ->with('success', 'Shop created successfully!');
    }

    public function show(Shop $shop)
    {
        // Check authorization
        $this->checkShopAuthorization($shop);

        $shop->load(['school', 'products.category', 'user']);

        return view('dashboard.shop.show', compact('shop'));
    }

    public function edit(Shop $shop)
    {
        // Check authorization
        $this->checkShopAuthorization($shop);

        $schools = [];
        if (auth()->user()->hasRole('super-admin')) {
            $schools = School::where('is_active', true)->get();
        } elseif (auth()->user()->hasRole('school-admin')) {
            $schools = School::where('id', auth()->user()->school_id)->get();
        }

        return view('dashboard.shop.edit', compact('shop', 'schools'));
    }

    public function update(Request $request, Shop $shop)
    {
        // Check authorization
        $this->checkShopAuthorization($shop);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'shop_type' => 'required|in:stationery,book_store,mixed,school_affiliated',
            'description' => 'nullable|string',
            'email' => 'nullable|email',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'banner' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'is_active' => 'boolean',
            'is_verified' => 'boolean',
            'school_id' => 'nullable|exists:schools,id',
        ]);

        // Auto-set school_id for school admins (can't change school)
        if (auth()->user()->hasRole('school-admin') && auth()->user()->school_id) {
            $validated['school_id'] = auth()->user()->school_id;
        }

        // Update slug if name changed
        if ($shop->name !== $validated['name']) {
            $validated['slug'] = Str::slug($validated['name']);

            // Ensure slug is unique
            $counter = 1;
            $originalSlug = $validated['slug'];
            while (Shop::where('slug', $validated['slug'])->where('id', '!=', $shop->id)->exists()) {
                $validated['slug'] = $originalSlug . '-' . $counter;
                $counter++;
            }
        }

        // Handle file uploads
        if ($request->hasFile('logo')) {
            // Delete old logo
            if ($shop->logo_url) {
                Storage::disk('website')->delete($shop->logo_url);
            }

            $logoPath = Storage::disk('website')
                ->putFile("shops/logo", $request->file('logo'));
            $validated['logo_url'] = $logoPath;
        }

        if ($request->hasFile('banner')) {
            // Delete old banner
            if ($shop->banner_url) {
                Storage::disk('website')->delete($shop->banner_url);
            }

            $bannerPath = Storage::disk('website')
                ->putFile("shops/banner", $request->file('banner'));
            $validated['banner_url'] = $bannerPath;
        }

        $shop->update($validated);

        return redirect()->route('admin.shops.index')
            ->with('success', 'Shop updated successfully!');
    }

    public function destroy(Shop $shop)
    {
        // Check authorization
        $this->checkShopAuthorization($shop);

        // Delete associated files
        if ($shop->logo_url) {
            Storage::disk('website')->delete($shop->logo_url);
        }
        if ($shop->banner_url) {
            Storage::disk('website')->delete($shop->banner_url);
        }

        $shop->delete();

        return redirect()->route('admin.shops.index')
            ->with('success', 'Shop deleted successfully!');
    }

    /**
     * Check if user is authorized to access the shop
     */
    private function checkShopAuthorization(Shop $shop)
    {
        if (auth()->user()->hasRole('super-admin')) {
            return true; // Super admin can access all shops
        }

        if (auth()->user()->hasRole('school-admin')) {
            // School admin can only access shops from their school
            if ($shop->school_id !== auth()->user()->school_id) {
                abort(403, 'Unauthorized access to this shop.');
            }
            return true;
        }

        if (auth()->user()->hasRole('shop-owner')) {
            // Shop owner can only access their own shops
            if ($shop->user_id !== auth()->id()) {
                abort(403, 'Unauthorized access to this shop.');
            }
            return true;
        }

        abort(403, 'Unauthorized access.');
    }
}

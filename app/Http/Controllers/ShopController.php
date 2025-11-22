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

        // School admin can only see their school's shops
        if (auth()->user()->hasRole('school_admin') && auth()->user()->school_id) {
            $query->where('school_id', auth()->user()->school_id);
        }

        $shops = $query->latest()->paginate(10);

        return view('dashboard.shop.index', compact('shops'));
    }

    public function create()
    {
        $schools = [];
        if (auth()->user()->hasRole('super_admin')) {
            $schools = School::where('is_active', true)->get();
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
        // $this->authorize('view', $shop);

        $shop->load(['school', 'products.category', 'user']);

        return view('dashboard.shop.show', compact('shop'));
    }

    public function edit(Shop $shop)
    {
        // $this->authorize('update', $shop);

        $schools = [];
        if (auth()->user()->hasRole('super_admin')) {
            $schools = School::where('is_active', true)->get();
        }

        return view('dashboard.shop.edit', compact('shop', 'schools'));
    }

    public function update(Request $request, Shop $shop)
    {
        // $this->authorize('update', $shop);

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
                Storage::disk('public')->delete($shop->logo_url);
            }
            $validated['logo_url'] = $request->file('logo')->store('shops/logo', 'public');
        }

        if ($request->hasFile('banner')) {
            // Delete old banner
            if ($shop->banner_url) {
                Storage::disk('public')->delete($shop->banner_url);
            }
            $validated['banner_url'] = $request->file('banner')->store('shops/banner', 'public');
        }

        $shop->update($validated);

        return redirect()->route('admin.shops.index')
            ->with('success', 'Shop updated successfully!');
    }

    public function destroy(Shop $shop)
    {
        // $this->authorize('delete', $shop);

        // Delete associated files
        if ($shop->logo_url) {
            Storage::disk('public')->delete($shop->logo_url);
        }
        if ($shop->banner_url) {
            Storage::disk('public')->delete($shop->banner_url);
        }

        $shop->delete();

        return redirect()->route('admin.shops.index')
            ->with('success', 'Shop deleted successfully!');
    }
}

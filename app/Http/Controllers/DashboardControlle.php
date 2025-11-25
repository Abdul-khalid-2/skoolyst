<?php

namespace App\Http\Controllers;

use App\Models\School;
use Illuminate\Http\Request;

class DashboardControlle extends Controller
{
    public function index()
    {

        if (auth()->user()->hasRole('super-admin')) {
            // Super Admin sees all schools with aggregated data
            $schools = School::with([
                'profile',  // Changed from 'schoolprofile'
                'reviews',
                'events',
                'branches',
                'users'
            ])->get();

            // Calculate aggregated statistics
            $stats = [
                'total_schools' => $schools->count(),
                'total_reviews' => $schools->sum(function ($school) {
                    return $school->reviews->count();
                }),
                'total_events' => $schools->sum(function ($school) {
                    return $school->events->count();
                }),
                'total_branches' => $schools->sum(function ($school) {
                    return $school->branches->count();
                }),
                'total_users' => $schools->sum(function ($school) {
                    return $school->users->count();
                }),
                'average_rating' => $schools->avg(function ($school) {
                    return $school->reviews->avg('rating');
                }),
            ];

            return view('dashboard.dashboard', compact('schools', 'stats'));
        } elseif (auth()->user()->hasRole('school-admin')) {
            // School Admin sees only their school data
            $schoolAdminSchoolId = auth()->user()->school_id;
            $school = School::with([
                'profile',  // Changed from 'schoolprofile'
                'reviews',
                'events',
                'branches',
                'users',
                'images',  // Changed from 'schoolImageGalleries'
                'features',
                'curriculums'
            ])->where('id', $schoolAdminSchoolId)->first();

            if (!$school) {
                return redirect()->route('dashboard')->with('error', 'School not found.');
            }

            // Calculate school-specific statistics
            $stats = [
                'total_reviews' => $school->reviews->count(),
                'total_events' => $school->events->count(),
                'total_branches' => $school->branches->count(),
                'total_users' => $school->users->count(),
                'total_images' => $school->images->count(),  // Changed from schoolImageGalleries
                'total_features' => $school->features->count(),
                'total_curriculums' => $school->curriculums->count(),
                'average_rating' => $school->reviews->avg('rating') ?? 0,
                'profile_visits' => $school->profile->visitor_count ?? 0,  // Changed from schoolprofile
            ];

            // Get recent reviews and events
            $recentReviews = $school->reviews()->latest()->take(5)->get();
            $upcomingEvents = $school->events()
                ->where('event_date', '>=', now())
                ->where('status', 'active')
                ->orderBy('event_date')
                ->take(5)
                ->get();

            return view('dashboard.dashboard', compact('school', 'stats', 'recentReviews', 'upcomingEvents'));
        } elseif (auth()->user()->hasRole('shop-owner')) {
            // Shop Owner sees only their shop data and associated schools
            $shopOwnerId = auth()->id();


            // Get the shop owned by this user
            $shop = \App\Models\Shop::with([
                'schoolAssociations.school',
                'products',
                'products.category',
                'user'
            ])->where('user_id', $shopOwnerId)->first();

            if (!$shop) {
                return redirect()->route('dashboard')->with('error', 'Shop not found. Please create a shop first.');
            }

            // Get associated schools (approved associations only)
            $associatedSchools = $shop->schoolAssociations()
                ->where('status', 'approved')
                ->where('is_active', true)
                ->with('school')
                ->get();

            // Calculate shop-specific statistics
            $stats = [
                'total_products' => $shop->products->count(),
                'active_products' => $shop->products->where('is_active', true)->count(),
                'out_of_stock' => $shop->products->where('is_in_stock', false)->count(),
                'low_stock' => $shop->products->where('stock_quantity', '<=', 5)->count(),
                'total_schools' => $associatedSchools->count(),
                'total_reviews' => $shop->total_reviews,
                'average_rating' => $shop->rating,
                'pending_associations' => $shop->schoolAssociations()->where('status', 'pending')->count(),
            ];

            // Get recent products
            $recentProducts = $shop->products()
                ->with('category')
                ->latest()
                ->take(5)
                ->get();

            // Get low stock products
            $lowStockProducts = $shop->products()
                ->where('manage_stock', true)
                ->where('stock_quantity', '<=', 5)
                ->where('is_active', true)
                ->with('category')
                ->take(5)
                ->get();

            // Get pending association requests
            $pendingAssociations = $shop->schoolAssociations()
                ->where('status', 'pending')
                ->with('school')
                ->take(5)
                ->get();

            return view('dashboard.shops.dashboard', compact(
                'shop',
                'stats',
                'recentProducts',
                'lowStockProducts',
                'pendingAssociations',
                'associatedSchools'
            ));
        } else {
            return redirect()->route('dashboard')->with('error', 'Unauthorized access.');
        }
    }
}

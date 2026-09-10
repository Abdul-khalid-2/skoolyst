<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Event;
use App\Models\Review;
use App\Models\School;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardControlle extends Controller
{
    public function index()
    {

        if (auth()->user()->hasRole('super-admin')) {
            // Aggregates only — avoids loading every school + relations (N+1 / memory on large DBs)
            $stats = [
                'total_schools' => School::count(),
                'total_reviews' => Review::count(),
                'total_events' => Event::count(),
                'total_branches' => Branch::count(),
                'total_users' => User::whereNotNull('school_id')->count(),
                'average_rating' => (float) (Review::avg('rating') ?? 0),
            ];

            return view('dashboard.dashboard', compact('stats'));
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
            // The Shop admin dashboard was removed (Step 3, 2026-09-10). Shop data,
            // models and the database are untouched; only this admin UI entry point
            // is disabled.
            abort(404, 'The shop dashboard has been removed.');
        } else {
            return redirect()->route('dashboard')->with('error', 'Unauthorized access.');
        }
    }
}

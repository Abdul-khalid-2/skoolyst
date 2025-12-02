<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\School;
use App\Models\Branch;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Spatie\Permission\Models\Role;

class ReviewController extends Controller
{
    /**
     * Display a listing of the reviews.
     */
    public function index(Request $request)
    {
        // Check permissions
        if (!auth()->user()->hasRole(['super-admin', 'school-admin'])) {
            abort(403, 'Unauthorized action.');
        }

        $query = Review::with(['school', 'branch', 'user'])
            ->latest();

        // Apply filters for school-admin (only their school)
        if (auth()->user()->hasRole('school-admin') && auth()->user()->school_id) {
            $query->where('school_id', auth()->user()->school_id);
        }

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('review', 'like', "%{$search}%")
                    ->orWhere('reviewer_name', 'like', "%{$search}%")
                    ->orWhereHas('school', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('branch', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    });
            });
        }

        // Filter by school
        if ($request->filled('school_id') && auth()->user()->hasRole('super-admin')) {
            $query->where('school_id', $request->school_id);
        }

        // Filter by branch
        if ($request->filled('branch_id')) {
            $query->where('branch_id', $request->branch_id);
        }

        // Filter by rating
        if ($request->filled('rating')) {
            $query->where('rating', $request->rating);
        }

        // Filter by status (approved/pending if you add that field)
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Get all schools for super-admin filter
        if (auth()->user()->hasRole('super-admin')) {
            $schools = School::orderBy('name')->get();
        } else {
            $schools = collect();
        }

        // Get branches for the selected school
        $branches = collect();
        if ($request->filled('school_id')) {
            $branches = Branch::where('school_id', $request->school_id)
                ->orderBy('name')
                ->get();
        }

        $reviews = $query->paginate(20);

        // Get review statistics
        $reviewStats = [
            'total' => Review::when(
                auth()->user()->hasRole('school-admin') && auth()->user()->school_id,
                function ($q) {
                    $q->where('school_id', auth()->user()->school_id);
                }
            )->count(),
            'average_rating' => number_format(Review::when(
                auth()->user()->hasRole('school-admin') && auth()->user()->school_id,
                function ($q) {
                    $q->where('school_id', auth()->user()->school_id);
                }
            )->avg('rating'), 1),
            'five_star' => Review::when(
                auth()->user()->hasRole('school-admin') && auth()->user()->school_id,
                function ($q) {
                    $q->where('school_id', auth()->user()->school_id);
                }
            )->where('rating', 5)->count(),
            'pending' => Review::when(
                auth()->user()->hasRole('school-admin') && auth()->user()->school_id,
                function ($q) {
                    $q->where('school_id', auth()->user()->school_id);
                }
            )->where('status', 'pending')->count(),
        ];

        return view('dashboard.review.index', compact('reviews', 'schools', 'branches', 'reviewStats'));
    }

    /**
     * Show the form for creating a new review.
     */
    public function create()
    {
        if (!auth()->user()->hasRole(['super-admin', 'school-admin'])) {
            abort(403, 'Unauthorized action.');
        }

        // Get schools based on user role
        if (auth()->user()->hasRole('super-admin')) {
            $schools = School::orderBy('name')->get();
        } else {
            $schools = School::where('id', auth()->user()->school_id)->get();
        }

        return view('dashboard.review.create', compact('schools'));
    }

    /**
     * Store a newly created review in storage.
     */
    public function store(Request $request)
    {
        if (!auth()->user()->hasRole(['super-admin', 'school-admin'])) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'school_id' => 'required|exists:schools,id',
            'branch_id' => 'nullable|exists:branches,id',
            'review' => 'required|string|min:10|max:1000',
            'rating' => 'required|integer|between:1,5',
            'reviewer_name' => 'required|string|max:255',
            'reviewer_email' => 'nullable|email|max:255',
        ]);

        // Check permission for school-admin
        if (auth()->user()->hasRole('school-admin')) {
            if ($request->school_id != auth()->user()->school_id) {
                abort(403, 'Unauthorized action.');
            }
        }

        $reviewData = [
            'school_id' => $request->school_id,
            'branch_id' => $request->branch_id,
            'review' => $request->review,
            'rating' => $request->rating,
            'reviewer_name' => $request->reviewer_name,
            'status' => 'approved', // Auto-approve admin created reviews
            'created_by_admin' => true,
            'admin_notes' => $request->admin_notes ?? null,
        ];

        // Add user_id if reviewer_email matches a user
        if ($request->filled('reviewer_email')) {
            $user = User::where('email', $request->reviewer_email)->first();
            if ($user) {
                $reviewData['user_id'] = $user->id;
                $reviewData['reviewer_email'] = $request->reviewer_email;
            }
        }

        Review::create($reviewData);

        return redirect()->route('admin.reviews.index')
            ->with('success', 'Review created successfully.');
    }

    /**
     * Display the specified review.
     */
    public function show(Review $review)
    {
        // Check permissions
        if (auth()->user()->hasRole('school-admin') && $review->school_id != auth()->user()->school_id) {
            abort(403, 'Unauthorized action.');
        }

        $review->load(['school', 'branch', 'user']);

        return view('dashboard.review.show', compact('review'));
    }

    /**
     * Show the form for editing the specified review.
     */
    public function edit(Review $review)
    {
        // Check permissions
        if (auth()->user()->hasRole('school-admin') && $review->school_id != auth()->user()->school_id) {
            abort(403, 'Unauthorized action.');
        }

        // Get schools based on user role
        if (auth()->user()->hasRole('super-admin')) {
            $schools = School::orderBy('name')->get();
        } else {
            $schools = School::where('id', auth()->user()->school_id)->get();
        }

        // Get branches for the school
        $branches = Branch::where('school_id', $review->school_id)
            ->orderBy('name')
            ->get();

        return view('dashboard.review.edit', compact('review', 'schools', 'branches'));
    }

    /**
     * Update the specified review in storage.
     */
    public function update(Request $request, Review $review)
    {
        // Check permissions
        if (auth()->user()->hasRole('school-admin') && $review->school_id != auth()->user()->school_id) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'review' => 'required|string|min:10|max:1000',
            'rating' => 'required|integer|between:1,5',
            'reviewer_name' => 'required|string|max:255',
            'status' => 'required|in:pending,approved,rejected',
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        // For school-admin, only allow updating reviews for their school
        if (auth()->user()->hasRole('school-admin') && $request->school_id != auth()->user()->school_id) {
            abort(403, 'Unauthorized action.');
        }

        $review->update([
            'review' => $request->review,
            'rating' => $request->rating,
            'reviewer_name' => $request->reviewer_name,
            'status' => $request->status,
            'admin_notes' => $request->admin_notes,
        ]);

        return redirect()->route('admin.reviews.index')
            ->with('success', 'Review updated successfully.');
    }

    /**
     * Remove the specified review from storage.
     */
    public function destroy(Review $review)
    {
        // Check permissions
        if (auth()->user()->hasRole('school-admin') && $review->school_id != auth()->user()->school_id) {
            abort(403, 'Unauthorized action.');
        }

        $review->delete();

        return redirect()->route('admin.reviews.index')
            ->with('success', 'Review deleted successfully.');
    }

    /**
     * Update review status (approve/reject)
     */
    public function updateStatus(Request $request, Review $review)
    {
        // Check permissions
        if (auth()->user()->hasRole('school-admin') && $review->school_id != auth()->user()->school_id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized action.'], 403);
        }

        $request->validate([
            'status' => 'required|in:pending,approved,rejected',
            'notes' => 'nullable|string|max:1000',
        ]);

        $review->update([
            'status' => $request->status,
            'admin_notes' => $request->filled('notes') ? $request->notes : $review->admin_notes,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Review status updated successfully.',
            'status' => $review->status,
            'status_badge' => $this->getStatusBadge($review->status)
        ]);
    }

    /**
     * Get branches for a school (AJAX)
     */
    public function getBranches(Request $request)
    {
        $request->validate([
            'school_id' => 'required|exists:schools,id'
        ]);

        // Check permissions for school-admin
        if (auth()->user()->hasRole('school-admin') && $request->school_id != auth()->user()->school_id) {
            abort(403, 'Unauthorized action.');
        }

        $branches = Branch::where('school_id', $request->school_id)
            ->orderBy('name')
            ->get();

        return response()->json($branches);
    }

    /**
     * Bulk actions
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:approve,reject,delete',
            'reviews' => 'required|array',
            'reviews.*' => 'exists:reviews,id'
        ]);

        $reviews = Review::whereIn('id', $request->reviews);

        // Check permissions for school-admin
        if (auth()->user()->hasRole('school-admin')) {
            $reviews->where('school_id', auth()->user()->school_id);
        }

        switch ($request->action) {
            case 'approve':
                $reviews->update(['status' => 'approved']);
                $message = 'Selected reviews approved successfully.';
                break;
            case 'reject':
                $reviews->update(['status' => 'rejected']);
                $message = 'Selected reviews rejected successfully.';
                break;
            case 'delete':
                $reviews->delete();
                $message = 'Selected reviews deleted successfully.';
                break;
        }

        return redirect()->route('admin.reviews.index')
            ->with('success', $message);
    }

    /**
     * Helper function to get status badge
     */
    private function getStatusBadge($status)
    {
        $badges = [
            'pending' => '<span class="badge bg-warning">Pending</span>',
            'approved' => '<span class="badge bg-success">Approved</span>',
            'rejected' => '<span class="badge bg-danger">Rejected</span>',
        ];

        return $badges[$status] ?? '<span class="badge bg-secondary">Unknown</span>';
    }
}

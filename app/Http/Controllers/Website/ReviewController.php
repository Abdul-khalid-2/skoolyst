<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\School;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request, $schoolId)
    {
        $request->validate([
            'rating' => 'required|integer|between:1,5',
            'review' => 'required|string|min:10|max:1000',
        ]);

        $school = School::findOrFail($schoolId);

        // Check if user already reviewed this school
        $existingReview = Review::where('school_id', $schoolId)
            ->where('user_id', auth()->id())
            ->first();

        if ($existingReview) {
            return response()->json([
                'success' => false,
                'message' => 'You have already reviewed this school.'
            ]);
        }

        Review::create([
            'school_id' => $schoolId,
            'user_id' => auth()->id(),
            'review' => $request->review,
            'rating' => $request->rating,
            // reviewer_name will be null for authenticated users
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Review submitted successfully'
        ]);
    }
}

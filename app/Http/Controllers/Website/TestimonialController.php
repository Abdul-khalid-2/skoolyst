<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;

class TestimonialController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'author_name' => 'required|string|max:255',
            'author_email' => 'nullable|email|max:255',
            'author_location' => 'nullable|string|max:100',
            'message' => 'required|string|min:20|max:1000',
            'rating' => 'required|integer|min:1|max:5',
            'experience_rating' => 'required|in:excellent,good,average,poor',
            'platform_features' => 'nullable|array',
            'platform_features.*' => 'string|max:100'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // If user is logged in, pre-fill their information
        $userId = null;
        if (auth()->check()) {
            $userId = auth()->id();
        }

        $testimonial = Testimonial::create([
            'user_id' => $userId,
            'author_name' => $request->author_name,
            'author_email' => $request->author_email,
            'author_location' => $request->author_location,
            'author_role' => 'Parent',
            'message' => $request->message,
            'rating' => $request->rating,
            'experience_rating' => $request->experience_rating,
            'platform_features_liked' => $request->platform_features,
            'status' => 'approved', // Needs admin approval
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Thank you for your feedback! Your testimonial has been submitted and is pending approval.',
            'data' => $testimonial
        ]);
    }

    public function index()
    {
        $testimonials = Testimonial::approved()
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('website.testimonials.index', compact('testimonials'));
    }
}

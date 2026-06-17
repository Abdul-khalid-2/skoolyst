<?php

namespace App\Http\Controllers\Website;

use App\Enums\ModerationStatus;
use App\Http\Controllers\Controller;
use App\Models\Shop;
use App\Models\ShopReview;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ShopReviewController extends Controller
{
    public function store(Request $request, string $uuid): JsonResponse
    {
        $request->validate([
            'rating' => 'required|integer|between:1,5',
            'review' => 'required|string|min:10|max:1000',
        ]);

        $shop = Shop::query()
            ->where('uuid', $uuid)
            ->where('is_active', true)
            ->firstOrFail();

        $existingReview = ShopReview::query()
            ->where('shop_id', $shop->id)
            ->where('user_id', $request->user()->id)
            ->first();

        if ($existingReview) {
            return response()->json([
                'success' => false,
                'message' => 'You have already reviewed this shop.',
            ], 422);
        }

        ShopReview::create([
            'shop_id' => $shop->id,
            'user_id' => $request->user()->id,
            'rating' => (int) $request->rating,
            'review' => $request->review,
            'status' => ModerationStatus::Approved,
        ]);

        $shop->recalculateRating();

        return response()->json([
            'success' => true,
            'message' => 'Review submitted successfully.',
        ]);
    }
}

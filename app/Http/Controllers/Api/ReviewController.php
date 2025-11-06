<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreReviewRequest;
use App\Models\Review;
use App\Notifications\Doctors\ReviewNotification;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    //
   public function store(StoreReviewRequest $request)
{
    $review = Review::create([
        'patient_id' => $request->user()->id,
        ...$request->only(['doctor_id', 'rating', 'comment']),
    ]);

    $review->doctor->notify(new ReviewNotification($review));

    return response()->json([
        'message' => 'Review added successfully',
        'review' => $review,
    ]);
}

}

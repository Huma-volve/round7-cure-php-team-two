<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreReviewRequest;
use App\Models\Review;
use App\Notifications\Doctors\ReviewNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    //
  public function store(StoreReviewRequest $request)
{
    $patient = Auth::user()->patient;

    if (!$patient) {
        return response()->json([
            'message' => 'This user is not registered as a patient.'
        ], 422);
    }

    $validated = $request->validated();

    $validated['patient_id'] = $patient->id;

    $review = Review::create($validated);

    $review->doctor->notify(new ReviewNotification($review));

    return response()->json([
        'message' => 'Review added successfully',
        'review' => $review,
    ]);
}
}

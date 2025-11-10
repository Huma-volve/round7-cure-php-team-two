<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSessionFeedbackRequest;
use App\Models\SessionFeedback;
use Illuminate\Http\Request;

class SessionFeedbackController extends Controller
{
    //

    public function store(StoreSessionFeedbackRequest $request)
    {
        $feedback = SessionFeedback::create([
            'patient_id' => $request->user()->id, 
            ...$request->only(['booking_id', 'doctor_id', 'overall_experience', 'notes']),
        ]);

        return response()->json([
            'message' => 'Feedback submitted successfully',
            'feedback' => $feedback,
        ]);
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\DoctorResource;
use App\Http\Resources\ReviewResource;
use App\Models\Doctor;
use App\Models\Favorite;

class DoctorController extends Controller
{
    //
    public function show(Request $request, Doctor $doctor)
    {
        // Eager load related data
        $doctor->load(['user', 'specialty']);
        // latest 3 reviews
        $doctor->setRelation('reviews', $doctor->reviews()->with('patient.user')->latest()->limit(3)->get());

        return new DoctorResource($doctor);
    }

    public function reviews(Request $request, Doctor $doctor)
    {
        $perPage = $request->input('per_page', 10);
        $reviews = $doctor->reviews()->with('patient.user')->latest()->paginate($perPage);
        return ReviewResource::collection($reviews);
    }

    /**
     * Toggle favorite (polymorphic)
     * POST /api/doctors/{doctor}/favorite
     */
    public function toggleFavorite(Request $request, Doctor $doctor)
    {
        $user = $request->user();

        $exists = Favorite::where('user_id', $user->id)
            ->where('favoritable_type', Doctor::class)
            ->where('favoritable_id', $doctor->id)
            ->exists();

        if ($exists) {
            Favorite::where('user_id', $user->id)
                ->where('favoritable_type', Doctor::class)
                ->where('favoritable_id', $doctor->id)
                ->delete();

            return response()->json(['status' => 'removed', 'message' => 'Removed from favorites']);
        }

        Favorite::create([
            'user_id' => $user->id,
            'favoritable_type' => Doctor::class,
            'favoritable_id' => $doctor->id,
        ]);

        return response()->json(['status' => 'added', 'message' => 'Added to favorites']);
    }
}

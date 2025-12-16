<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\DoctorResource;
use App\Models\Doctor;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    //
  
    public function nearby(Request $request)
    {
        //return response()->json(auth()->user());
        $request->validate([
            'lat' => 'nullable|numeric',
            'lng' => 'nullable|numeric',
            'radius_km' => 'nullable|integer|min:1|max:200',
            'specialty_id' => 'nullable|integer|exists:specialties,id',
            'page' => 'nullable|integer',
        ]);

        $lat = $request->input('lat') ?? optional(auth()->user())->latitude;
        $lng = $request->input('lng') ?? optional(auth()->user())->longitude;

        if (is_null($lat) || is_null($lng)) {
            return response()->json([
                'message' => 'User location (lat/lng) is required either in request or in profile.'
            ], 422);
        }

        $radius = $request->input('radius_km', 10);
        $perPage = 12;

        $query = Doctor::nearby((float) $lat, (float) $lng, (int) $radius)
            ->with(['user', 'specialty']);

        if ($request->filled('specialty_id')) {
            $query->where('doctors.specialty_id', $request->specialty_id);
        }

        $doctors = $query->paginate($perPage);

        return DoctorResource::collection($doctors)->additional([
            'meta' => [
                'user_location' => ['lat' => (float) $lat, 'lng' => (float) $lng],
            ]
        ]);
    }

}

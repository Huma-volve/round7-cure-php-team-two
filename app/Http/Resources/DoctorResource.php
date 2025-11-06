<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DoctorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $doctor = $this->resource;
        $user = $doctor->user;

        $clinicLat = $doctor->clinic_lat ?? ($user->latitude ?? null);
        $clinicLng = $doctor->clinic_lng ?? ($user->longitude ?? null);

        return [
            'id' => $doctor->id,
            'user_id' => $doctor->user_id,
            'name' => $user?->name,
            'profile_photo' => $user?->profile_photo ? url($user->profile_photo) : null,
            'specialty_id' => $doctor->specialty_id,
            'specialty_name' => $doctor->specialty?->name ?? null,
            //'license_number' => $doctor->license_number,
            'session_price' => (float) $doctor->session_price,
            'available_slots' => $doctor->available_slots,
            'contact' => [
                'phone' => $user?->phone_number,
                'email' => $user?->email,
            ],
            'clinic' => [
                'lat' => $clinicLat !== null ? (float) $clinicLat : null,
                'lng' => $clinicLng !== null ? (float) $clinicLng : null,
            ],
            'average_rating' => $doctor->average_rating,
            'reviews_count' => $doctor->reviews_count,
            'is_favorite' => $request->user() ? $request->user()->isFavorite($doctor) : false,
            'distance_km' => isset($doctor->distance) ? round($doctor->distance, 2) : null,
        ];
    }
}

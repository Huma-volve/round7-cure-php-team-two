<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReviewResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $patient = $this->patient;
        $user = $patient ? $patient->user : null;

        return [
            'id' => $this->id,
            'rating' => (int) $this->rating,
            'comment' => $this->comment,
            'created_at' => $this->created_at->toDateTimeString(),
            'patient' => [
                'id' => $patient?->id,
                'user_id' => $user?->id,
                'name' => $user?->name,
                'profile_photo' => $user?->profile_photo ? url($user->profile_photo) : null,
            ],
        ];
    }
}

<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SearchResultResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
        public function toArray($request)
    {
        return [
            'id' => $this->id,
            'doctor_name'=>$this->user->name,
            'specialty_name' => $this->specialty_name,
            'license_number' => $this->license_number,
            'session_price' => $this->session_price,
            'available_slots' => $this->available_slots,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
        ];
    }

}

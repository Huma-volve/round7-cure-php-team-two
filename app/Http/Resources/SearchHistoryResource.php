<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SearchHistoryResource extends JsonResource
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
            'query' => $this->search_query,
            'location' => $this->location,
            'searched_at' => $this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}

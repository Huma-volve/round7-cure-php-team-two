<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SearchRequest;
use App\Http\Resources\SearchResultResource;
use App\Http\Resources\SearchHistoryResource;
use App\Models\Doctor;
use App\Models\SearchHistory;
use Illuminate\Support\Facades\Http;

class SearchController extends Controller
{
    public function search(SearchRequest $request)
    {
        $userId = $request->user()->id;
      $query = $request->input('query');
      $location = $request->input('location');

        $this->saveSearchHistory($userId, $query, $location);

        $results = Doctor::with(['user', 'specialty'])
            ->whereHas('user', function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%");
            })
            ->orWhereHas('specialty', function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%");
            })
            ->get();

        $locationData = $location ? $this->fetchLocationData($location) : null;

        return response()->json([
            'query' => $query,
            'location' => $location,
            'location_data' => $locationData,
            'results' => SearchResultResource::collection($results),
        ]);
    }

    protected function saveSearchHistory($userId, $query, $location = null)
    {
        SearchHistory::create([
            'user_id' => $userId,
            'search_query' => $query,
            'location' => $location,
        ]);
    }

    protected function fetchLocationData($location)
    {
        try {
            $response = Http::get('https://api.api-ninjas.com/v1/geocoding', [
                'city' => $location,
            ]);

            if ($response->successful()) {
                return $response->json();
            }
        } catch (\Exception $e) {
            return ['error' => 'Unable to fetch location data'];
        }

        return null;
    }

    public function history()
    {
        $userId = auth()->id();

        $history = SearchHistory::where('user_id', $userId)
            ->orderByDesc('created_at')
            ->get();

        return SearchHistoryResource::collection($history);
    }

    public function clearHistory()
    {
        $userId = auth()->id();

        SearchHistory::where('user_id', $userId)->delete();

        return response()->json(['message' => 'Search history cleared']);
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\FavoriteRequest;
use App\Http\Resources\FavoriteResource;
use App\Models\Favorite;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    // Add to favorites
    public function add(FavoriteRequest $request)
    {
        $favorite = Favorite::updateOrCreate(
            [
                'user_id' => auth()->id(),
                'favoritable_id' => $request->favoritable_id,
                'favoritable_type' => $request->favoritable_type,
            ]
        );

        return new FavoriteResource($favorite);
    }

    // List user favorites
    public function index()
    {
        $favorites = Favorite::where('user_id', auth()->id())
            ->with('favoritable')
            ->get();

        return FavoriteResource::collection($favorites);
    }

    // Remove favorite
    public function remove($id)
    {
        $favorite = Favorite::where('user_id', auth()->id())->findOrFail($id);
        $favorite->delete();

        return response()->json([
            'message' => 'Favorite removed successfully'
        ]);
    }
}


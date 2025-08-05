<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Favorite;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function index()
    {
        $favorites = Favorite::all();
        return response()->json($favorites);
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'movie_id' => 'required|integer|unique:favorites,movie_id',
                'title' => 'required|string',
                'poster_path' => 'nullable|string',
            ]);

            $favorite = Favorite::create($validated);

            return response()->json($favorite, 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        $favorite = Favorite::findOrFail($id);
        $favorite->delete();

        return response()->json(null, 204);
    }
}
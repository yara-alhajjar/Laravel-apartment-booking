<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{

    public function store(Request $request)
    {
        $request->validate([
            'apartment_id' => 'required|exists:apartments,id',
        ]);

        if (Favorite::where('tenant_id', Auth::id())->where('apartment_id', $request->apartment_id)->exists()) {
            return response()->json(['message' => 'Already in favorites'], 409);
        }

        $favorite = Favorite::create([
            'tenant_id' => Auth::id(),
            'apartment_id' => $request->apartment_id,
        ]);

        return response()->json([
            'message' => 'Apartment added to favorites',
            'favorite' => $favorite,
        ], 201);
    }



    public function index()
    {
        $favorites = Favorite::with('apartment')
            ->where('tenant_id', Auth::id())
            ->get();

        return response()->json($favorites);
    }

    
    public function destroy($id)
    {
        $favorite = Favorite::findOrFail($id);

        if ($favorite->tenant_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $favorite->delete();

        return response()->json(['message' => 'Apartment removed from favorites']);
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Rating;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RatingController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'apartment_id' => 'required|exists:apartments,id',
            'rating'       => 'required|integer|min:1|max:5',
            'comment'      => 'nullable|string|max:500',
        ]);

    
        $hasBooking = Booking::where('apartment_id', $request->apartment_id)
            ->where('tenant_id', Auth::id())
            ->where('status', 'approved')
            ->where('end_date', '<', now()) 
            ->exists();

        if (!$hasBooking) {
            return response()->json([
                'message' => 'You can only rate apartments you have completed an approved booking for'
            ], 403);
        }

        
        $alreadyRated = Rating::where('apartment_id', $request->apartment_id)
            ->where('tenant_id', Auth::id())
            ->exists();

        if ($alreadyRated) {
            return response()->json([
                'message' => 'You have already rated this apartment'
            ], 409);
        }

        $rating = Rating::create([
            'tenant_id'    => Auth::id(),
            'apartment_id' => $request->apartment_id,
            'rating'       => $request->rating,
            'comment'      => $request->comment,
        ]);

        return response()->json([
            'message' => 'Rating submitted successfully',
            'rating'  => $rating,
        ], 201);
    }

    public function apartmentRatings($apartmentId)
    {
        $ratings = Rating::with('tenant')
            ->where('apartment_id', $apartmentId)
            ->get();

        return response()->json($ratings);
    }

    public function tenantRatings()
    {
        $ratings = Rating::with('apartment')
            ->where('tenant_id', Auth::id())
            ->get();

        return response()->json($ratings);
    }
}

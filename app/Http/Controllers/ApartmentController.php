<?php

namespace App\Http\Controllers;

use App\Models\Apartment;
use App\Models\Booking;
use App\Models\Rating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApartmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Apartment::query();

        if ($request->has('governorate')) {
            $query->where('governorate', $request->governorate);
        }
        if ($request->has('city')) {
            $query->where('city', $request->city);
        }
        if ($request->has('price_min')) {
            $query->where('price_per_night', '>=', $request->price_min);
        }
        if ($request->has('price_max')) {
            $query->where('price_per_night', '<=', $request->price_max);
        }
        if ($request->has('features')) {
            foreach ($request->features as $feature) {
                $query->whereJsonContains('features', $feature);
            }
        }

        $apartments = $query->get();
        return response()->json($apartments);
    }

    public function show($id)
    {
        $apartment = Apartment::with('landlord')->findOrFail($id);
        return response()->json($apartment);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'governorate' => 'required|string',
            'city' => 'required|string',
            'price_per_night' => 'required|numeric|min:0',
            'features' => 'nullable|array',
        ]);

        $apartment = Apartment::create([
            'landlord_id' => Auth::id(),
            'title' => $request->title,
            'description' => $request->description,
            'governorate' => $request->governorate,
            'city' => $request->city,
            'price_per_night' => $request->price_per_night,
            'features' => $request->features,
            'status' => 'available',
        ]);

        return response()->json([
            'message' => 'Apartment created successfully',
            'apartment' => $apartment,
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $apartment = Apartment::findOrFail($id);

        if ($apartment->landlord_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'governorate' => 'sometimes|string',
            'city' => 'sometimes|string',
            'price_per_night' => 'sometimes|numeric|min:0',
            'features' => 'nullable|array',
            'status' => 'in:available,unavailable',
        ]);

        $apartment->update($validated);

        return response()->json([
            'message' => 'Apartment updated successfully',
            'apartment' => $apartment,
        ]);
    }

    public function destroy($id)
    {
        $apartment = Apartment::findOrFail($id);

        if ($apartment->landlord_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $apartment->delete();
        return response()->json(['message' => 'Apartment deleted successfully']);
    }

    public function rateApartment(Request $request, $id)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:500',
        ]);

        $hasBooking = Booking::where('apartment_id', $id)
            ->where('tenant_id', Auth::id())
            ->where('status', 'approved')
            ->where('end_date', '<', now()) 
            ->exists();

        if (!$hasBooking) {
            return response()->json([
                'message' => 'You can only rate apartments you have completed an approved booking for'
            ], 403);
        }

        $rating = Rating::create([
            'apartment_id' => $id,
            'tenant_id' => Auth::id(),
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return response()->json([
            'message' => 'Rating submitted successfully',
            'rating' => $rating,
        ], 201);
    }

    public function apartmentRatings($id)
    {
        $ratings = Rating::with('tenant')
            ->where('apartment_id', $id)
            ->get();

        return response()->json($ratings);
    }
}

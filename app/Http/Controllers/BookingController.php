<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\BookingStatusChanged;


class BookingController extends Controller
{

    public function store(Request $request)
    {
        $request->validate([
            'apartment_id' => 'required|exists:apartments,id',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
        ]);

    
        $overlap = Booking::where('apartment_id', $request->apartment_id)
            ->where(function ($query) use ($request) {
                $query->whereBetween('start_date', [$request->start_date, $request->end_date])
                      ->orWhereBetween('end_date', [$request->start_date, $request->end_date])
                      ->orWhere(function ($q) use ($request) {
                          $q->where('start_date', '<=', $request->start_date)
                            ->where('end_date', '>=', $request->end_date);
                      });
            })
            ->where('status', 'approved')
            ->exists();

        if ($overlap) {
            return response()->json([
                'message' => 'This apartment is already booked for the selected dates'
            ], 409);
        }

        $booking = Booking::create([
            'apartment_id' => $request->apartment_id,
            'tenant_id' => Auth::id(),
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'status' => 'pending', 
        ]);

        return response()->json([
            'message' => 'Booking request created successfully',
            'booking' => $booking,
        ], 201);
    }

    
    public function tenantBookings()
    {
        $bookings = Booking::with('apartment')
            ->where('tenant_id', Auth::id())
            ->orderBy('start_date', 'desc')
            ->get();

        return response()->json($bookings);
    }

    
    public function landlordBookings()
    {
        $bookings = Booking::with(['apartment', 'tenant'])
            ->whereHas('apartment', function ($query) {
                $query->where('landlord_id', Auth::id());
            })
            ->orderBy('start_date', 'desc')
            ->get();

        return response()->json($bookings);
    }


    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected,cancelled',
        ]);

        $booking = Booking::with('tenant', 'apartment')->findOrFail($id);

        if ($booking->apartment->landlord_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $booking->status = $request->status;
        $booking->save();

    
        $booking->tenant->notify(new BookingStatusChanged($booking));

        return response()->json([
            'message' => 'Booking status updated successfully',
            'booking' => $booking,
        ]);
    }



    public function update(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);

        if ($booking->tenant_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
        ]);

        
        $overlap = Booking::where('apartment_id', $booking->apartment_id)
            ->where('id', '!=', $booking->id)
            ->where(function ($query) use ($request) {
                $query->whereBetween('start_date', [$request->start_date, $request->end_date])
                    ->orWhereBetween('end_date', [$request->start_date, $request->end_date])
                    ->orWhere(function ($q) use ($request) {
                        $q->where('start_date', '<=', $request->start_date)
                            ->where('end_date', '>=', $request->end_date);
                    });
            })
            ->where('status', 'approved')
            ->exists();

        if ($overlap) {
            return response()->json([
                'message' => 'This apartment is already booked for the selected dates'
            ], 409);
        }

        $booking->start_date = $request->start_date;
        $booking->end_date = $request->end_date;
        $booking->status = 'pending';
        $booking->save();

        return response()->json([
            'message' => 'Booking update request submitted successfully',
            'booking' => $booking,
        ]);
    }


    public function cancel($id)
    {
        $booking = Booking::findOrFail($id);

        if ($booking->tenant_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if (in_array($booking->status, ['approved', 'pending'])) {
            $booking->status = 'cancelled';
            $booking->save();

            return response()->json([
                'message' => 'Booking cancelled successfully',
                'booking' => $booking,
            ]);
        }

        return response()->json(['message' => 'Booking already cancelled or rejected'], 400);
}

}

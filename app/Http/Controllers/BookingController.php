<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Property;
use App\Models\Booking;

class BookingController extends Controller
{
    public function index()
    {
        $bookings = auth()->user()->bookings()->with('property')->latest()->get();
        return view('bookings.index', compact('bookings'));
    }

    public function store(Request $request, Property $property)
    {
        $validated = $request->validate([
            'check_in' => 'required|date|after_or_equal:today',
            'check_out' => 'required|date|after:check_in',
        ]);

        // Simple overlay check (optional but good)
        // For now, let's keep it simple as per request or add basic check
        
        $property->bookings()->create([
            'user_id' => auth()->id(),
            'check_in' => $validated['check_in'],
            'check_out' => $validated['check_out'],
        ]);

        return redirect()->route('bookings.index')->with('success', 'Booking confirmed!');
    }

    public function destroy(Booking $booking)
    {
        if ($booking->user_id !== auth()->id()) {
            abort(403);
        }

        $booking->update(['status' => 'cancelled']);

        return back()->with('success', 'Booking cancelled.');
    }
}

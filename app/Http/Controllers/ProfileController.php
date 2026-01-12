<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        $totalRevenue = 0;

        if ($user->role === 'seller') {
            $properties = $user->properties()->with('bookings')->get();
            foreach($properties as $property) {
                foreach($property->bookings as $booking) {
                    if ($booking->status !== 'cancelled') {
                        $days = \Carbon\Carbon::parse($booking->check_in)->diffInDays(\Carbon\Carbon::parse($booking->check_out));
                        $totalRevenue += $days * $property->price;
                    }
                }
            }
        }

        return view('profile.edit', compact('user', 'totalRevenue'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return back()->with('success', 'Profile updated successfully.');
    }
}

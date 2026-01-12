<?php

namespace App\Http\Controllers;

use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PropertyController extends Controller
{
    // Customer/Public Home - Show all properties
    public function index(Request $request)
    {
        $query = Property::latest();

        if ($request->filled('location')) {
            $query->where('location', 'like', '%' . $request->location . '%');
        }

        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        $properties = $query->get();
        return view('home', compact('properties'));
    }

    // Seller Dashboard - Show my properties
    public function sellerDashboard()
    {
        $properties = Auth::user()->properties()->with('bookings')->latest()->get();
        
        $totalRevenue = 0;
        foreach($properties as $property) {
            foreach($property->bookings as $booking) {
                if ($booking->status !== 'cancelled') {
                    $days = \Carbon\Carbon::parse($booking->check_in)->diffInDays(\Carbon\Carbon::parse($booking->check_out));
                    $totalRevenue += $days * $property->price;
                }
            }
        }

        return view('seller.dashboard', compact('properties', 'totalRevenue'));
    }

    // Become a Host logic
    public function becomeHost()
    {
        $user = Auth::user();
        
        if($user->role !== 'seller') {
            $user->role = 'seller';
            $user->save();
        }

        return redirect('/seller/properties/create')->with('success', 'You are now a host! List your first property.');
    }

    // Show Create Form
    public function create()
    {
        return view('seller.create');
    }

    // Store new property
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'rooms' => 'required|integer|min:1',
            'bathrooms' => 'required|integer|min:1',
            'location' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Validate as image file
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('properties', 'public');
            $validated['image'] = $path; // Save properties/filename.jpg
        }

        $request->user()->properties()->create($validated);

        return redirect('/seller/dashboard')->with('success', 'Property listed successfully!');
    }

    // Show Edit Form
    public function edit(Property $property)
    {
        // Check ownership
        if (Auth::id() !== $property->user_id) {
            abort(403);
        }
        return view('seller.edit', compact('property'));
    }

    // Update Property
    public function update(Request $request, Property $property)
    {
        // Check ownership
        if (Auth::id() !== $property->user_id) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'rooms' => 'required|integer|min:1',
            'bathrooms' => 'required|integer|min:1',
            'location' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('properties', 'public');
            $validated['image'] = $path;
        }

        $property->update($validated);

        return redirect('/seller/dashboard')->with('success', 'Property updated successfully!');
    }

    public function show(Property $property)
    {
        return view('properties.show', compact('property'));
    }

    // Delete property
    public function destroy(Property $property)
    {
        // Check if owner or admin
        if (Auth::id() !== $property->user_id && Auth::user()->role !== 'admin') {
            abort(403);
        }

        $property->delete();

        return back()->with('success', 'Property deleted.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function index()
    {
        // If authenticated as admin, show dashboard
        if (Auth::guard('admin')->check()) {
            $totalUsers = User::count();
            $totalBookings = \App\Models\Booking::where('status', '!=', 'cancelled')->count();

            // Calculate Revenue
            $bookings = \App\Models\Booking::with('property')->where('status', '!=', 'cancelled')->get();
            $totalRevenue = 0;
            foreach ($bookings as $booking) {
                $days = \Carbon\Carbon::parse($booking->check_in)->diffInDays(\Carbon\Carbon::parse($booking->check_out));
                $totalRevenue += $days * $booking->property->price;
            }

            $activeTab = 'overview';
            return view('admin.dashboard', compact('totalUsers', 'totalRevenue', 'totalBookings', 'activeTab'));
        }

        // If Guest or Not Admin, show admin login
        return view('admin.login');
    }

    public function revenue()
    {
        $this->checkAdmin();
        $bookings = \App\Models\Booking::with(['property', 'user'])->where('status', '!=', 'cancelled')->latest()->get();
        $activeTab = 'revenue';
        return view('admin.dashboard', compact('bookings', 'activeTab'));
    }

    public function users()
    {
        $this->checkAdmin();
        $users = User::latest()->get();
        $activeTab = 'users';
        return view('admin.dashboard', compact('users', 'activeTab'));
    }

    public function bookings()
    {
        $this->checkAdmin();
        $bookings = \App\Models\Booking::with(['property', 'user'])->where('status', '!=', 'cancelled')->latest()->get();
        $activeTab = 'bookings';
        return view('admin.dashboard', compact('bookings', 'activeTab'));
    }

    public function properties()
    {
        $this->checkAdmin();
        $properties = Property::with('user')->get();
        $activeTab = 'properties';
        return view('admin.dashboard', compact('properties', 'activeTab'));
    }

    private function checkAdmin()
    {
        if (!Auth::guard('admin')->check()) {
            abort(403);
        }
    }

    public function destroyUser(User $user)
    {
        $this->checkAdmin();

        // Prevent self-delete seems irrelevant since admins are in a different table now, but safe to keep check logic or remove.
        // Actually admins manage USERS table (User model). Admins are in Admin model.
        // So an admin deleting a user is fine. 
        // Admin deleting another ADMIN? Not implemented yet.

        $user->delete();
        $user->properties()->delete();

        return back()->with('success', 'User deleted.');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::guard('admin')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/secret-admin-portal');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    public function profile()
    {
        $this->checkAdmin();
        $admin = Auth::guard('admin')->user();
        return view('admin.profile', compact('admin'));
    }

    public function updateProfile(Request $request)
    {
        $this->checkAdmin();
        $admin = Auth::guard('admin')->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:admins,email,' . $admin->id,
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $admin->name = $request->name;
        $admin->email = $request->email;

        if ($request->filled('password')) {
            $admin->password = \Illuminate\Support\Facades\Hash::make($request->password);
        }

        $admin->save();

        return back()->with('success', 'Profile updated successfully.');
    }
}

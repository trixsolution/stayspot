@extends('layouts.admin')

@section('content')
<style>
    .admin-container { display: flex; min-height: 80vh; }
    .sidebar { width: 250px; background: #f8f9fa; padding: 2rem 1rem; border-right: 1px solid #ddd; }
    .sidebar-link { display: block; padding: 0.8rem 1rem; color: #333; text-decoration: none; border-radius: 6px; margin-bottom: 0.5rem; }
    .sidebar-link:hover, .sidebar-link.active { background: #e2e8f0; color: var(--primary-color); font-weight: bold; }
    .content { flex: 1; padding: 2rem; }
    .table { width: 100%; border-collapse: collapse; margin-top: 1rem; }
    .table th, .table td { text-align: left; padding: 0.8rem; border-bottom: 1px solid #ddd; }
    .table th { background-color: #f7f7f7; }
    .btn-danger { background-color: #ff385c; color: white; border: none; padding: 0.4rem 0.8rem; border-radius: 4px; cursor: pointer; }
</style>

<div class="admin-container">
    <!-- Sidebar -->
    <div class="sidebar">
        <h3 style="margin-bottom: 1.5rem; padding-left: 1rem;">Admin Portal</h3>
        <a href="/secret-admin-portal" class="sidebar-link {{ $activeTab === 'overview' ? 'active' : '' }}">Dashboard</a>
        <a href="/secret-admin-portal/revenue" class="sidebar-link {{ $activeTab === 'revenue' ? 'active' : '' }}">Revenue</a>
        <a href="/secret-admin-portal/bookings" class="sidebar-link {{ $activeTab === 'bookings' ? 'active' : '' }}">Manage Bookings</a>
        <hr style="margin: 1rem 0; border: 0; border-top: 1px solid #ddd;">
        <a href="/secret-admin-portal/users" class="sidebar-link {{ $activeTab === 'users' ? 'active' : '' }}">Manage Users</a>
        <a href="/secret-admin-portal/properties" class="sidebar-link {{ $activeTab === 'properties' ? 'active' : '' }}">Manage Properties</a>
    </div>

    <!-- Main Content -->
    <div class="content">
        @if(session('success'))
            <div style="background: #e6fffa; color: #2c7a7b; padding: 1rem; border-radius: 8px; margin-bottom: 1rem;">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div style="background: #fff5f5; color: #c53030; padding: 1rem; border-radius: 8px; margin-bottom: 1rem;">{{ session('error') }}</div>
        @endif

        @if($activeTab === 'overview')
            <h2 style="margin-bottom: 2rem;">Dashboard Overview</h2>
             <div style="display: flex; gap: 2rem; flex-wrap: wrap;">
                <!-- Total Users Card -->
                <a href="/secret-admin-portal/users" style="flex: 1; min-width: 250px; background: white; padding: 2rem; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); text-decoration: none; color: inherit; border: 1px solid #eee; transition: transform 0.2s;">
                    <h3 style="color: #777; font-size: 1rem; margin-bottom: 0.5rem;">TOTAL USERS</h3>
                    <p style="font-size: 2.5rem; font-weight: bold; margin: 0; color: var(--primary-color);">{{ $totalUsers }}</p>
                </a>

                <!-- Total Bookings Card -->
                <a href="/secret-admin-portal/bookings" style="flex: 1; min-width: 250px; background: white; padding: 2rem; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); text-decoration: none; color: inherit; border: 1px solid #eee; transition: transform 0.2s;">
                    <h3 style="color: #777; font-size: 1rem; margin-bottom: 0.5rem;">TOTAL BOOKINGS</h3>
                    <p style="font-size: 2.5rem; font-weight: bold; margin: 0; color: var(--primary-color);">{{ $totalBookings }}</p>
                </a>

                <!-- Total Revenue Card -->
                <a href="/secret-admin-portal/revenue" style="flex: 1; min-width: 250px; background: white; padding: 2rem; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); text-decoration: none; color: inherit; border: 1px solid #eee; transition: transform 0.2s;">
                    <h3 style="color: #777; font-size: 1rem; margin-bottom: 0.5rem;">TOTAL REVENUE</h3>
                    <p style="font-size: 2.5rem; font-weight: bold; margin: 0; color: #2ecc71;">Rs. {{ number_format($totalRevenue, 2) }}</p>
                </a>
             </div>

        @elseif($activeTab === 'bookings')
            <h2>Manage Bookings</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>Booking ID</th>
                        <th>Property</th>
                        <th>Guest</th>
                        <th>Check-in</th>
                        <th>Check-out</th>
                        <th>Nights</th>
                        <th>Total Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @php $grandTotal = 0; @endphp
                    @foreach($bookings as $booking)
                        @php
                            $days = \Carbon\Carbon::parse($booking->check_in)->diffInDays(\Carbon\Carbon::parse($booking->check_out));
                            $amount = $days * $booking->property->price;
                            $grandTotal += $amount;
                        @endphp
                        <tr>
                            <td>#{{ $booking->id }}</td>
                            <td>{{ $booking->property->title }}</td>
                            <td>{{ $booking->user->name }}</td>
                            <td>{{ $booking->check_in }}</td>
                            <td>{{ $booking->check_out }}</td>
                            <td>{{ $days }}</td>
                            <td style="font-weight: bold; color: #2ecc71;">Rs. {{ number_format($amount, 2) }}</td>
                        </tr>
                    @endforeach
                    <tr style="background: #f8f9fa;">
                        <td colspan="6" style="text-align: right; font-weight: bold;">Grand Total Booked Value:</td>
                        <td style="font-weight: bold; font-size: 1.1rem; color: #2ecc71;">Rs. {{ number_format($grandTotal, 2) }}</td>
                    </tr>
                </tbody>
            </table>

        @elseif($activeTab === 'revenue')
            <h2>Revenue Analytics</h2>
            
            <div style="padding: 2rem; background: white; border-radius: 12px; text-align: center; margin-bottom: 2rem;">
                <!-- We don't have $totalRevenue passed to this view in revenue() method, only bookings. 
                     Wait, revenue() method passes 'bookings'. 
                     So I can calculate total revenue again. -->
                @php 
                    $revenueTotal = 0; 
                    foreach($bookings as $b) {
                        $d = \Carbon\Carbon::parse($b->check_in)->diffInDays(\Carbon\Carbon::parse($b->check_out));
                        $revenueTotal += $d * $b->property->price;
                    }
                @endphp
                 <h3 style="color: #777;">TOTAL REVENUE GENERATED</h3>
                 <p style="font-size: 4rem; font-weight: bold; color: #2ecc71; margin: 1rem 0;">Rs. {{ number_format($revenueTotal, 2) }}</p>
                 <p>From {{ count($bookings) }} active bookings.</p>
            </div>
            
            <h3>Transaction History</h3>
            <table class="table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Booking ID</th>
                        <th>Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($bookings as $booking)
                        @php
                            $days = \Carbon\Carbon::parse($booking->check_in)->diffInDays(\Carbon\Carbon::parse($booking->check_out));
                            $amount = $days * $booking->property->price;
                        @endphp
                        <tr>
                            <td>{{ $booking->created_at->format('M d, Y') }}</td>
                            <td>#{{ $booking->id }}</td>
                            <td style="color: #2ecc71;">+ Rs. {{ number_format($amount, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

        @elseif($activeTab === 'users')
            <h2>Manage Users</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Role</th>
                        <th>Email</th>
                        <th>Joined</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->name }}</td>
                            <td>
                                @if($user->role === 'seller')
                                    <span style="background: #e2e8f0; color: #2d3748; padding: 2px 6px; border-radius: 4px; font-size: 0.8rem;">Host</span>
                                @else
                                    <span style="background: #edf2f7; color: #718096; padding: 2px 6px; border-radius: 4px; font-size: 0.8rem;">User</span>
                                @endif
                            </td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->created_at ? $user->created_at->format('M d, Y') : 'N/A' }}</td>
                            <td>
                                @if($user->id !== auth()->id())
                                    <form action="/secret-admin-portal/users/{{ $user->id }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-danger" onclick="return confirm('Delete this user?');">Delete</button>
                                    </form>
                                @else
                                    <span style="color: #777;">(You)</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @elseif($activeTab === 'properties')
             <h2>Manage Properties</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Image</th>
                        <th>Title</th>
                        <th>Location</th>
                        <th>Owner</th>
                        <th>Price</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($properties as $property)
                        <tr>
                            <td>{{ $property->id }}</td>
                            <td>
                                <img src="{{ asset('storage/' . $property->image) }}" alt="Property Image" style="width: 60px; height: 40px; object-fit: cover; border-radius: 4px;">
                            </td>
                            <td>
                                <a href="{{ route('properties.show', $property->id) }}" style="color: var(--primary-color); text-decoration: none; font-weight: 500;">
                                    {{ $property->title }}
                                </a>
                            </td>
                            <td>{{ $property->location }}</td>
                            <td>{{ $property->user->name ?? 'Unknown' }}</td>
                            <td>Rs. {{ $property->price }}</td>
                            <td>
                                <form action="/properties/{{ $property->id }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-danger" onclick="return confirm('Delete this property?');">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</div>
@endsection

@extends('layouts.main')

@section('title', 'My Trips')

@section('content')
<div class="container">
    <h1 style="margin-bottom: 2rem;">My Trips</h1>

    @if($bookings->isEmpty())
        <div style="text-align: center; padding: 4rem 0;">
            <h3>No bookings yet!</h3>
            <p>Time to dust off your bags and start planning your next adventure.</p>
            <a href="/home" class="btn btn-secondary" style="margin-top: 1rem; display: inline-block;">Start exploring</a>
        </div>
    @else
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 1.5rem;">
            @foreach($bookings as $booking)
            <div class="property-card">
                <div style="position: relative;">
                    <img src="{{ asset('storage/' . $booking->property->image) }}" style="width: 100%; height: 200px; object-fit: cover;">
                    <div style="position: absolute; top: 10px; right: 10px; background: rgba(0,0,0,0.7); color: white; padding: 4px 8px; border-radius: 4px; font-size: 0.8rem;">
                        {{ ucfirst($booking->status) }}
                    </div>
                </div>
                <div style="padding: 1rem;">
                    <h3 style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $booking->property->title }}</h3>
                    <p style="color: #777; font-size: 0.9rem;">{{ $booking->property->location }}</p>
                    <hr style="border: 0; border-top: 1px solid #eee; margin: 0.8rem 0;">
                    <div style="display: flex; justify-content: space-between; font-size: 0.9rem;">
                        <div>
                            <span style="display: block; color: #999; font-size: 0.8rem;">CHECK-IN</span>
                            {{ \Carbon\Carbon::parse($booking->check_in)->format('M d, Y') }}
                        </div>
                        <div style="text-align: right;">
                            <span style="display: block; color: #999; font-size: 0.8rem;">CHECKOUT</span>
                            {{ \Carbon\Carbon::parse($booking->check_out)->format('M d, Y') }}
                        </div>
                    </div>
                    
                    @if($booking->status !== 'cancelled')
                    <form action="{{ route('bookings.destroy', $booking->id) }}" method="POST" style="margin-top: 1rem; text-align: center;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" style="width: 100%; border: 1px solid red; background: white; color: red; padding: 0.5rem; border-radius: 6px; cursor: pointer; transition: all 0.2s;" onmouseover="this.style.background='red'; this.style.color='white'" onmouseout="this.style.background='white'; this.style.color='red'" onclick="return confirm('Are you sure you want to cancel this trip?');">Cancel Booking</button>
                    </form>
                    @else
                    <div style="margin-top: 1rem; text-align: center; padding: 0.5rem; background: #fee2e2; color: #dc2626; border-radius: 6px; border: 1px solid #fecaca; font-weight: bold;">
                        Trip Cancelled
                    </div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    @endif
</div>
@endsection

@extends('layouts.main')

@section('title', $property->title)

@section('content')
<div style="max-width: 1000px; margin: 2rem auto; padding: 0 1rem;">
    <div style="display: flex; gap: 2rem; flex-wrap: wrap;">
        
        <!-- Left: Image & Details -->
        <div style="flex: 2; min-width: 300px;">
            <img src="{{ asset('storage/' . $property->image) }}" alt="{{ $property->title }}" style="width: 100%; height: 400px; object-fit: cover; border-radius: 12px; margin-bottom: 2rem;">
            
            <h1 style="font-size: 2rem; margin-bottom: 0.5rem; color: var(--text-color);">{{ $property->title }}</h1>
            <p style="color: #777; margin-bottom: 1rem; font-size: 1.1rem;">{{ $property->location }}</p>
            <div style="display: flex; gap: 1rem; margin-bottom: 1.5rem; color: #555;">
                <span>{{ $property->rooms }} Rooms</span> &bull; <span>{{ $property->bathrooms }} Bathrooms</span>
            </div>
            
            <div style="margin-bottom: 2rem; line-height: 1.6;">
                <h3 style="margin-bottom: 1rem;">About this place</h3>
                <p>{{ $property->description }}</p>
            </div>

            <!-- Map -->
            @if($property->latitude && $property->longitude)
            <div style="margin-bottom: 2rem;">
                <h3 style="margin-bottom: 1rem;">Location</h3>
                <div id="map" style="height: 300px; width: 100%; border-radius: 12px; z-index: 1;"></div>
            </div>
            
            <!-- Leaflet CSS/JS -->
            <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
            <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
            <script>
                var map = L.map('map').setView([{{ $property->latitude }}, {{ $property->longitude }}], 15);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '© OpenStreetMap'
                }).addTo(map);
                L.marker([{{ $property->latitude }}, {{ $property->longitude }}]).addTo(map)
                    .bindPopup("{{ $property->title }}").openPopup();
            </script>
            @endif
        </div>

        <!-- Right: Booking Card -->
        <div style="flex: 1; min-width: 300px;">
            <div style="background: white; border: 1px solid #ddd; border-radius: 12px; padding: 1.5rem; box-shadow: 0 4px 12px rgba(0,0,0,0.1); position: sticky; top: 100px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                    <span style="font-size: 1.5rem; font-weight: bold;">Rs. {{ $property->price }}</span>
                    <span style="color: #777;">night</span>
                </div>

                @if(auth()->user()->role !== 'admin')
                <form action="{{ route('properties.book', $property->id) }}" method="POST">
                    @csrf
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.5rem; margin-bottom: 1rem; border: 1px solid #ccc; border-radius: 8px; overflow: hidden;">
                        <div style="padding: 0.5rem; border-right: 1px solid #ccc;">
                            <label style="font-size: 0.75rem; font-weight: bold; display: block;">CHECK-IN</label>
                            <input type="date" name="check_in" required style="border: none; width: 100%; outline: none; font-family: inherit; cursor: pointer;">
                        </div>
                        <div style="padding: 0.5rem;">
                            <label style="font-size: 0.75rem; font-weight: bold; display: block;">CHECKOUT</label>
                            <input type="date" name="check_out" required style="border: none; width: 100%; outline: none; font-family: inherit; cursor: pointer;">
                        </div>
                    </div>
                    
                    @error('check_in') <div style="color: red; font-size: 0.85rem; margin-bottom: 0.5rem;">{{ $message }}</div> @enderror
                    @error('check_out') <div style="color: red; font-size: 0.85rem; margin-bottom: 0.5rem;">{{ $message }}</div> @enderror

                    <button type="submit" class="btn btn-primary" style="width: 100%; padding: 1rem; font-size: 1.1rem; border-radius: 8px;">Book now</button>
                    
                    <p style="text-align: center; margin-top: 1rem; color: #777; font-size: 0.9rem;">You won't be charged yet</p>
                </form>
                @else
                    <div style="padding: 1rem; background: #f0f0f0; border-radius: 8px; text-align: center; color: #555;">
                        <strong>Admin View Mode</strong><br>
                        Booking is disabled for admins.
                    </div>
                @endif
            </div>
        </div>

    </div>
</div>
@endsection

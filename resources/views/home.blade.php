@extends('layouts.main')

@section('content')
<div class="container">
    <div style="margin-bottom: 2rem;">
        <h2>Explore beautiful stays</h2>
        
        <!-- Search Filters -->
        <form action="{{ route('home') }}" method="GET" style="display: flex; gap: 1rem; align-items: flex-end; margin-top: 1rem; flex-wrap: wrap;">
            <div style="flex: 1; min-width: 200px;">
                <label for="location" style="display: block; font-size: 0.9rem; color: #555; margin-bottom: 4px;">City / Location</label>
                <input type="text" name="location" value="{{ request('location') }}" placeholder="Where to?" style="width: 100%; padding: 0.8rem; border: 1px solid #ddd; border-radius: 8px;">
            </div>
            
            <div style="width: 150px;">
                <label for="min_price" style="display: block; font-size: 0.9rem; color: #555; margin-bottom: 4px;">Min Price</label>
                <input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="Min" style="width: 100%; padding: 0.8rem; border: 1px solid #ddd; border-radius: 8px;">
            </div>
            
            <div style="width: 150px;">
                <label for="max_price" style="display: block; font-size: 0.9rem; color: #555; margin-bottom: 4px;">Max Price</label>
                <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="Max" style="width: 100%; padding: 0.8rem; border: 1px solid #ddd; border-radius: 8px;">
            </div>
            
            <button type="submit" class="btn-primary" style="padding: 0.8rem 1.5rem; height: 100%; display: flex; align-items: center; justify-content: center;">Search</button>
            
            @if(request()->hasAny(['location', 'min_price', 'max_price']))
                <a href="{{ route('home') }}" style="color: #777; text-decoration: underline; margin-bottom: 0.8rem;">Clear</a>
            @endif
        </form>
    </div>

    @if($properties->isEmpty())
        <div style="text-align: center; padding: 4rem; color: #777;">
            <p>No listings available yet. Check back later!</p>
        </div>
    @else
        <div class="property-grid">
            @foreach($properties as $property)
                <div class="property-card" onclick="window.location='{{ route('properties.show', $property->id) }}'" style="cursor: pointer;">
                    <img src="{{ asset('storage/' . $property->image) }}" alt="{{ $property->title }}" class="property-img">
                    <div class="property-info">
                        <div class="property-title">{{ $property->title }}</div>
                        <div style="color: #777; font-size: 0.9rem;">{{ $property->location }}</div>
                        <div class="property-price"><span>Rs. {{ $property->price }}</span> night</div>
                        
                        @if(auth()->user()->role === 'admin')
                            <form action="/properties/{{ $property->id }}" method="POST" style="margin-top: 0.5rem;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="color: red; background: none; border: none; cursor: pointer; text-decoration: underline;">Admin: Delete</button>
                            </form>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection

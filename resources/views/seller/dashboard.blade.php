@extends('layouts.main')

@section('content')
<div class="container">
    <div style="margin-bottom: 2rem; display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h2>My Properties</h2>
            <div style="margin-top: 10px; padding: 1.5rem; background: white; border-radius: 12px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); display: inline-block;">
                 <h4 style="margin: 0 0 0.5rem 0; color: #777; font-size: 0.9rem;">TOTAL ESTIMATED REVENUE</h4>
                 <p style="margin: 0; font-size: 2rem; font-weight: bold; color: #2ecc71;">Rs. {{ number_format($totalRevenue, 2) }}</p>
            </div>
        </div>
        <a href="/seller/properties/create" class="btn-primary" style="padding: 0.8rem 1.5rem; text-decoration: none;">+ List New Property</a>
    </div>

    @if(session('success'))
        <div style="background: #e6fffa; color: #2c7a7b; padding: 1rem; border-radius: 8px; margin-bottom: 2rem;">
            {{ session('success') }}
        </div>
    @endif

    <h3>My Listings</h3>
    @if($properties->isEmpty())
        <p style="color: #777; margin-top: 1rem;">You haven't listed any properties yet.</p>
    @else
        <div class="property-grid" style="margin-top: 1rem;">
            @foreach($properties as $property)
                <div class="property-card">
                    <img src="{{ asset('storage/' . $property->image) }}" alt="{{ $property->title }}" class="property-img">
                    <div class="property-info" style="padding: 1rem;">
                        <div class="property-title">{{ $property->title }}</div>
                        <div class="property-price"><span>Rs. {{ $property->price }}</span></div>
                        
                        <form action="/properties/{{ $property->id }}" method="POST" style="margin-top: 1rem;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="color: red; background: none; border: 1px solid red; padding: 0.3rem 0.8rem; border-radius: 4px; cursor: pointer;">Delete</button>
                            <a href="{{ route('seller.properties.edit', $property->id) }}" style="color: #4a5568; text-decoration: none; border: 1px solid #cbd5e0; padding: 0.3rem 0.8rem; border-radius: 4px; margin-left: 0.5rem;">Edit</a>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection

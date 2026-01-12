@extends('layouts.main')

@section('content')
<div class="hero">
    <h1>Welcome to Stayspot</h1>
    <p>Find your next stay or list your property.</p>
    
    <div class="hero-buttons">
        @auth
            <a href="/home" class="hero-btn primary">Go to Dashboard</a>
        @else
            <a href="/register" class="hero-btn primary">Get Started</a>
            <a href="/login" class="hero-btn">Log In</a>
        @endauth
    </div>
</div>

<div class="container">
    <h2>Explore Stays</h2>
    <p>Sign up to see more.</p>
</div>
@endsection
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stayspot</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
</head>
<body>
    <nav class="navbar">
        <a href="/" class="brand">Stayspot</a>
        <div class="nav-links">
            <a href="/home" style="margin-right: 15px;">Explore</a>
            @auth
                @if(auth()->user()->role !== 'admin')
                    <a href="{{ route('bookings.index') }}" style="margin-right: 15px;">My Trips</a>
                @endif
                @if(auth()->user()->role === 'seller')
                    <a href="/seller/dashboard" class="nav-btn">Switch to Hosting</a>
                @elseif(auth()->user()->role === 'customer')
                     <form action="{{ route('become.host') }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" class="nav-btn" style="border:none; background:none; cursor:pointer; font-size:1rem; color: var(--text-color);">Become a Host</button>
                     </form>
                @endif
                <form action="/logout" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit" class="nav-btn" style="border:none; background:none; cursor:pointer; font-size:1rem;">Logout</button>
                </form>
                <a href="{{ route('profile.edit') }}" style="margin-left: 10px; text-decoration: none; color: #555;">
                    <div style="width: 35px; height: 35px; background: #ddd; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; color: #555;">
                        {{ substr(auth()->user()->name, 0, 1) }}
                    </div>
                </a>
            @else
                <a href="/login" class="nav-btn">Log in</a>
                <a href="/register" class="nav-btn btn-primary">Sign up</a>
            @endauth
        </div>
    </nav>

    <main>
        @yield('content')
    </main>

    <footer>
        <div class="container" style="text-align: center; color: #777; margin-top: 2rem;">
            &copy; {{ date('Y') }} Stayspot, Inc.
        </div>
    </footer>
</body>
</html>

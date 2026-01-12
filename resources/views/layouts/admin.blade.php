<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stayspot Admin</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        .admin-navbar {
            background-color: #333;
            color: white;
        }
        .admin-navbar .brand {
            color: white;
        }
        .admin-navbar .nav-btn {
            color: #ddd;
        }
        .admin-navbar .nav-btn:hover {
            color: white;
        }
    </style>
</head>
<body>
    <nav class="navbar admin-navbar">
        <a href="/secret-admin-portal" class="brand">Stayspot Admin</a>
        <div class="nav-links">
            <span style="color: #bbb; margin-right: 15px;">Admin Mode</span>
            <a href="/secret-admin-portal" class="nav-btn">Dashboard</a>
            <a href="/secret-admin-portal/profile" class="nav-btn" style="margin-right: 15px;">Profile</a>
            <form action="/logout" method="POST" style="display:inline;">
                @csrf
                <button type="submit" class="nav-btn" style="border:none; background:none; cursor:pointer; font-size:1rem;">Logout</button>
            </form>
        </div>
    </nav>

    <main>
        @yield('content')
    </main>

    <footer>
        <div class="container" style="text-align: center; color: #777; margin-top: 2rem;">
            &copy; {{ date('Y') }} Stayspot Admin Portal
        </div>
    </footer>
</body>
</html>

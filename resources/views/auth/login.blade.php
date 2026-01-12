@extends('layouts.main')

@section('content')
    <div class="auth-container">
        <div class="auth-header">
            <h2>Welcome back</h2>
        </div>

        <form method="POST" action="/login">
            @csrf

            <div class="form-group">
                <label for="email">Email</label>
                <input type="text" id="email" name="email" autofocus value="{{ old('email') }}">
                @error('email')
                    <div class="error-msg">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password">
                @error('password')
                    <div class="error-msg">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn-primary btn-block">Log in</button>
        </form>

        <div style="margin-top: 1rem; text-align: center;">
            <p>Don't have an account? <a href="/register" style="color: var(--primary-color);">Sign up</a></p>
        </div>
    </div>
@endsection
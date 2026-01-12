@extends('layouts.main')

@section('content')
    <div class="auth-container">
        <div class="auth-header">
            <h2>Create an account</h2>
        </div>

        <form method="POST" action="/register">
            @csrf

            <div class="form-group">
                <label for="name">Full Name</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}">
                @error('name')
                    <div class="error-msg">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="text" id="email" name="email" value="{{ old('email') }}">
                @error('email')
                    <div class="error-msg">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="role">I want to:</label>
                <select name="role" id="role">
                    <option value="customer">Book places</option>
                    <option value="seller">List my place</option>
                </select>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password">
                @error('password')
                    <div class="error-msg">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="password_confirmation">Confirm Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation">
            </div>

            <button type="submit" class="btn-primary btn-block">Sign up</button>
        </form>

        <div style="margin-top: 1rem; text-align: center;">
            <p>Already have an account? <a href="/login" style="color: var(--primary-color);">Log in</a></p>
        </div>
    </div>
@endsection
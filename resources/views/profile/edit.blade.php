@extends('layouts.main')

@section('content')
<div class="container" style="max-width: 600px; padding: 2rem; margin: 0 auto;">
    <h2 style="margin-bottom: 2rem;">My Profile</h2>

    @if(session('success'))
        <div style="background: #e6fffa; color: #2c7a7b; padding: 1rem; border-radius: 8px; margin-bottom: 1rem;">{{ session('success') }}</div>
    @endif

    @if($user->role === 'seller')
        <div style="background: white; padding: 2rem; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); margin-bottom: 2rem; text-align: center;">
             <h4 style="margin: 0 0 0.5rem 0; color: #777; font-size: 0.9rem; text-transform: uppercase;">Total Earnings</h4>
             <p style="margin: 0; font-size: 2.5rem; font-weight: bold; color: #2ecc71;">Rs. {{ number_format($totalRevenue, 2) }}</p>
        </div>
    @endif

    <div style="background: white; padding: 2rem; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
        <form action="{{ route('profile.update') }}" method="POST">
            @csrf
            @method('PUT')

            <div style="margin-bottom: 20px;">
                <label for="name" style="display: block; margin-bottom: 5px; color: #555;">Name</label>
                <input type="text" id="name" name="name" class="form-control" value="{{ old('name', $user->name) }}" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
                @error('name') <span style="color: red; font-size: 0.9rem;">{{ $message }}</span> @enderror
            </div>

            <div style="margin-bottom: 20px;">
                <label for="email" style="display: block; margin-bottom: 5px; color: #555;">Email Address</label>
                <input type="email" id="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
                 @error('email') <span style="color: red; font-size: 0.9rem;">{{ $message }}</span> @enderror
            </div>

            <hr style="margin: 2rem 0; border: 0; border-top: 1px solid #eee;">
            <p style="margin-bottom: 1rem; color: #777; font-size: 0.9rem;">Leave password blank to keep current password.</p>

            <div style="margin-bottom: 20px;">
                <label for="password" style="display: block; margin-bottom: 5px; color: #555;">New Password</label>
                <input type="password" id="password" name="password" class="form-control" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
                 @error('password') <span style="color: red; font-size: 0.9rem;">{{ $message }}</span> @enderror
            </div>

            <div style="margin-bottom: 20px;">
                <label for="password_confirmation" style="display: block; margin-bottom: 5px; color: #555;">Confirm New Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
            </div>

            <button type="submit" class="btn-primary" style="width: 100%; padding: 12px; background-color: var(--primary-color); color: white; border: none; border-radius: 6px; font-size: 1rem; cursor: pointer;">Update Profile</button>
        </form>
    </div>
</div>
@endsection

@extends('layouts.admin')

@section('content')
<div class="content" style="max-width: 600px; margin: 0rem auto; padding: 2rem;">
    
    @if(session('success'))
        <div style="background: #e6fffa; color: #2c7a7b; padding: 1rem; border-radius: 8px; margin-bottom: 1rem;">{{ session('success') }}</div>
    @endif

    <div style="background: white; padding: 2rem; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
        <h2 style="margin-bottom: 1.5rem;">Edit Admin Profile</h2>
        
        <form action="/secret-admin-portal/profile" method="POST">
            @csrf
            @method('PUT')

            <div style="margin-bottom: 20px;">
                <label for="name" style="display: block; margin-bottom: 5px; color: #555;">Name</label>
                <input type="text" id="name" name="name" class="form-control" value="{{ old('name', $admin->name) }}" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
                @error('name') <span style="color: red; font-size: 0.9rem;">{{ $message }}</span> @enderror
            </div>

            <div style="margin-bottom: 20px;">
                <label for="email" style="display: block; margin-bottom: 5px; color: #555;">Email Address</label>
                <input type="email" id="email" name="email" class="form-control" value="{{ old('email', $admin->email) }}" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
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

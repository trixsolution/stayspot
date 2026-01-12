@extends('layouts.admin')

@section('content')
<div style="max-width: 400px; margin: 4rem auto; padding: 2rem; background: white; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); border: 1px solid #ddd;">
    <h2 style="text-align: center; color: var(--text-color); margin-bottom: 2rem;">Admin Login</h2>
    
    <form action="{{ route('admin.login.submit') }}" method="POST">
        @csrf
        
        <div style="margin-bottom: 1.5rem;">
            <label for="email" style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Email Address</label>
            <input type="email" name="email" id="email" required style="width: 100%; padding: 0.8rem; border: 1px solid #ccc; border-radius: 6px; font-size: 1rem;">
            @error('email')
                <div style="color: red; font-size: 0.85rem; margin-top: 0.5rem;">{{ $message }}</div>
            @enderror
        </div>

        <div style="margin-bottom: 2rem;">
            <label for="password" style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Password</label>
            <input type="password" name="password" id="password" required style="width: 100%; padding: 0.8rem; border: 1px solid #ccc; border-radius: 6px; font-size: 1rem;">
        </div>

        <button type="submit" class="btn btn-primary" style="width: 100%; padding: 0.8rem; font-size: 1.1rem; border-radius: 6px;">Login</button>
    </form>
</div>
@endsection

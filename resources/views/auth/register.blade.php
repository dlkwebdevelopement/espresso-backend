@extends('layouts.auth')

@section('title', 'Join Espresso')

@section('content')
<div class="auth-card">
    <div class="auth-header">
        <div class="auth-logo">ESPRESSO.</div>
        <p style="color: var(--text-muted);">Create your account to start ordering</p>
    </div>

    <form action="{{ route('register') }}" method="POST">
        @csrf
        <div class="form-group">
            <label class="form-label">Full Name</label>
            <input type="text" name="name" class="form-control" placeholder="John Doe" value="{{ old('name') }}" required>
            @error('name') <span style="color: #ff6b6b; font-size: 11px;">{{ $message }}</span> @enderror
        </div>

        <div style="display: grid; grid-template-columns: 1.2fr 0.8fr; gap: 15px;">
            <div class="form-group">
                <label class="form-label">Email Address</label>
                <input type="email" name="email" class="form-control" placeholder="john@example.com" value="{{ old('email') }}" required>
                @error('email') <span style="color: #ff6b6b; font-size: 11px;">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label class="form-label">Date of Birth</label>
                <input type="date" name="dob" class="form-control" value="{{ old('dob') }}" required>
                @error('dob') <span style="color: #ff6b6b; font-size: 11px;">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Mobile Number</label>
            <input type="text" name="mobile_number" class="form-control" placeholder="+973 3300 0000" value="{{ old('mobile_number') }}" required>
            @error('mobile_number') <span style="color: #ff6b6b; font-size: 11px;">{{ $message }}</span> @enderror
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
            <div class="form-group">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                @error('password') <span style="color: #ff6b6b; font-size: 11px;">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label class="form-label">Confirm</label>
                <input type="password" name="password_confirmation" class="form-control" placeholder="••••••••" required>
            </div>
        </div>

        <button type="submit" class="btn-auth">Create Account</button>
    </form>

    <div class="auth-footer">
        Already have an account? <a href="{{ route('login') }}" class="auth-link">Sign In</a>
    </div>
</div>
@endsection

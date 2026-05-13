@extends('layouts.admin')

@section('title', 'Register Customer')
@section('header', 'New Customer Registration')
@section('subheader', 'Register a customer walking into the shop')

@section('content')
<div style="max-width: 600px;">
    <form action="{{ route('admin.customers.store') }}" method="POST" class="stat-card">
        @csrf
        
        <div class="form-group">
            <label class="form-label">Customer Name</label>
            <input type="text" name="name" class="form-control" placeholder="Full Name" value="{{ old('name') }}" required>
            @error('name') <span style="color: #ff6b6b; font-size: 11px;">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label class="form-label">Email Address</label>
            <input type="email" name="email" class="form-control" placeholder="email@example.com" value="{{ old('email') }}" required>
            @error('email') <span style="color: #ff6b6b; font-size: 11px;">{{ $message }}</span> @enderror
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div class="form-group">
                <label class="form-label">Mobile Number</label>
                <input type="text" name="mobile_number" class="form-control" placeholder="+973 ..." value="{{ old('mobile_number') }}" required>
                @error('mobile_number') <span style="color: #ff6b6b; font-size: 11px;">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label class="form-label">Date of Birth</label>
                <input type="date" name="dob" class="form-control" value="{{ old('dob') }}" required>
                @error('dob') <span style="color: #ff6b6b; font-size: 11px;">{{ $message }}</span> @enderror
            </div>
        </div>

        <p style="font-size: 12px; color: var(--text-muted); margin: 20px 0; padding: 15px; background: rgba(255,255,255,0.02); border-radius: 12px; border-left: 3px solid var(--primary);">
            <span class="material-symbols-outlined" style="font-size: 16px; vertical-align: middle;">info</span>
            Staff-created accounts are assigned a random password. The customer can login using <strong>Email OTP</strong> or reset their password later.
        </p>

        <div style="margin-top: 30px; display: flex; gap: 15px;">
            <button type="submit" class="btn-primary" style="width: auto; padding: 12px 30px;">Register Customer</button>
            <a href="{{ route('admin.customers.index') }}" class="btn-primary" style="width: auto; padding: 12px 30px; background: rgba(255,255,255,0.05); color: var(--text); text-decoration: none;">Cancel</a>
        </div>
    </form>
</div>
@endsection

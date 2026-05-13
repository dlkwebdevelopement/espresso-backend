@extends('layouts.admin')

@section('title', 'Edit Customer')
@section('header', 'Edit Customer Details')
@section('subheader', 'Update profile information for ' . $customer->name)

@section('content')
<div style="max-width: 600px;">
    <form action="{{ route('admin.customers.update', $customer) }}" method="POST" class="stat-card">
        @csrf
        @method('PUT')
        
        <div class="form-group">
            <label class="form-label">Customer Name</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $customer->name) }}" required>
            @error('name') <span style="color: #ff6b6b; font-size: 11px;">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label class="form-label">Email Address</label>
            <input type="email" name="email" class="form-control" value="{{ old('email', $customer->email) }}" required>
            @error('email') <span style="color: #ff6b6b; font-size: 11px;">{{ $message }}</span> @enderror
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div class="form-group">
                <label class="form-label">Mobile Number</label>
                <input type="text" name="mobile_number" class="form-control" value="{{ old('mobile_number', $customer->mobile_number) }}" required>
                @error('mobile_number') <span style="color: #ff6b6b; font-size: 11px;">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label class="form-label">Date of Birth</label>
                <input type="date" name="dob" class="form-control" value="{{ old('dob', $customer->dob ? $customer->dob->format('Y-m-d') : '') }}" required>
                @error('dob') <span style="color: #ff6b6b; font-size: 11px;">{{ $message }}</span> @enderror
            </div>
        </div>

        <div style="margin-top: 30px; display: flex; gap: 15px;">
            <button type="submit" class="btn-primary" style="width: auto; padding: 12px 30px;">Update Details</button>
            <a href="{{ route('admin.customers.index') }}" class="btn-primary" style="width: auto; padding: 12px 30px; background: rgba(255,255,255,0.05); color: var(--text); text-decoration: none;">Cancel</a>
        </div>
    </form>
</div>
@endsection

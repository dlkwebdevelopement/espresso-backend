@extends('layouts.admin')

@section('title', 'Register Store')
@section('header', 'Register New Store')
@section('subheader', 'Add a new location to your network')

@section('content')
    <div class="login-card" style="max-width: 600px; margin: 0 auto; animation: none;">
        <form action="{{ route('admin.stores.store') }}" method="POST">
            @csrf
            
            <div class="form-group">
                <label class="form-label">Store Name</label>
                <input type="text" name="name" class="form-control" required placeholder="e.g. Espresso Downtown">
                @error('name') <div class="error-message">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Location / Address</label>
                <input type="text" name="location" class="form-control" required placeholder="e.g. 123 Coffee St, City">
                @error('location') <div class="error-message">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Contact Number</label>
                <input type="text" name="contact_number" class="form-control" placeholder="e.g. +973 1234 5678">
                @error('contact_number') <div class="error-message">{{ $message }}</div> @enderror
            </div>

            <div style="display: flex; gap: 15px; margin-top: 30px;">
                <button type="submit" class="btn-primary">Save Location</button>
                <a href="{{ route('admin.stores.index') }}" class="btn-primary" style="background: rgba(255, 255, 255, 0.05); color: #fff; text-align: center; text-decoration: none;">Cancel</a>
            </div>
        </form>
    </div>
@endsection

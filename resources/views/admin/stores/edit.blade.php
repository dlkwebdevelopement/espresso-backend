@extends('layouts.admin')

@section('title', 'Edit Store')
@section('header', 'Edit: ' . $store->name)
@section('subheader', 'Modify location details')

@section('content')
    <div class="login-card" style="max-width: 600px; margin: 0 auto; animation: none;">
        <form action="{{ route('admin.stores.update', $store) }}" method="POST">
            @csrf @method('PUT')
            
            <div class="form-group">
                <label class="form-label">Store Name</label>
                <input type="text" name="name" class="form-control" value="{{ $store->name }}" required>
                @error('name') <div class="error-message">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Location / Address</label>
                <input type="text" name="location" class="form-control" value="{{ $store->location }}" required>
                @error('location') <div class="error-message">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Contact Number</label>
                <input type="text" name="contact_number" class="form-control" value="{{ $store->contact_number }}">
                @error('contact_number') <div class="error-message">{{ $message }}</div> @enderror
            </div>

            <div style="display: flex; gap: 15px; margin-top: 30px;">
                <button type="submit" class="btn-primary">Update Location</button>
                <a href="{{ route('admin.stores.index') }}" class="btn-primary" style="background: rgba(255, 255, 255, 0.05); color: #fff; text-align: center; text-decoration: none;">Cancel</a>
            </div>
        </form>
    </div>
@endsection

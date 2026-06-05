@extends('layouts.admin')

@section('title', 'Edit Free Drink')
@section('header', 'Edit Free Drink')
@section('subheader', 'Update details for ' . $freeDrink->name)

@section('content')
    <div class="login-card" style="max-width: 600px; margin: 0 auto; animation: none;">
        <form action="{{ route('admin.free-drinks.update', $freeDrink->id) }}" method="POST" enctype="multipart/form-data">
            @csrf 
            @method('PUT')
            
            <div class="form-group">
                <label class="form-label">Name</label>
                <input type="text" name="name" value="{{ $freeDrink->name }}" class="form-control" required placeholder="e.g. Free Espresso Shot">
                @error('name') <div class="error-message">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" placeholder="Short description of the free drink" rows="3" style="resize: vertical;">{{ $freeDrink->description }}</textarea>
                @error('description') <div class="error-message">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Image</label>
                <input type="file" name="image" class="form-control" style="padding: 10px;">
                @if($freeDrink->image_path)
                    <div style="margin-top: 15px; border-radius: 12px; overflow: hidden; display: inline-block; border: 1px solid var(--border);">
                        <img src="{{ asset($freeDrink->image_path) }}" height="100" style="display: block; object-fit: cover;">
                    </div>
                @endif
                @error('image') <div class="error-message">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label class="remember-me" style="margin-top: 20px;">
                    <input type="checkbox" name="is_active" value="1" {{ $freeDrink->is_active ? 'checked' : '' }} style="display: none;">
                    <div class="checkbox-custom"></div>
                    <span class="remember-text">Active and available</span>
                </label>
            </div>

            <div style="display: flex; gap: 15px; margin-top: 30px;">
                <button type="submit" class="btn-primary">Update Free Drink</button>
                <a href="{{ route('admin.free-drinks.index') }}" class="btn-primary" style="background: rgba(255, 255, 255, 0.05); color: var(--text); text-align: center; text-decoration: none;">Cancel</a>
            </div>
        </form>
    </div>
@endsection

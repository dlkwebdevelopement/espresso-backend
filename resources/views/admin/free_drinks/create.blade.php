@extends('layouts.admin')

@section('title', 'Add Free Drink')
@section('header', 'Create Free Drink')
@section('subheader', 'Add a new complimentary drink to the catalog')

@section('content')
    <div class="login-card" style="max-width: 600px; margin: 0 auto; animation: none;">
        <form action="{{ route('admin.free-drinks.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="form-group">
                <label class="form-label">Name</label>
                <input type="text" name="name" class="form-control" required placeholder="e.g. Free Espresso Shot">
                @error('name') <div class="error-message">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" placeholder="Short description of the free drink" rows="3" style="resize: vertical;"></textarea>
                @error('description') <div class="error-message">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Image</label>
                <input type="file" name="image" class="form-control" style="padding: 10px;">
                @error('image') <div class="error-message">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label class="remember-me" style="margin-top: 20px;">
                    <input type="checkbox" name="is_active" value="1" checked style="display: none;">
                    <div class="checkbox-custom"></div>
                    <span class="remember-text">Active and available</span>
                </label>
            </div>

            <div style="display: flex; gap: 15px; margin-top: 30px;">
                <button type="submit" class="btn-primary">Save Free Drink</button>
                <a href="{{ route('admin.free-drinks.index') }}" class="btn-primary" style="background: rgba(255, 255, 255, 0.05); color: var(--text); text-align: center; text-decoration: none;">Cancel</a>
            </div>
        </form>
    </div>
@endsection

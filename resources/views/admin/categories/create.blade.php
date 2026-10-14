@extends('layouts.admin')

@section('title', 'Add Category')
@section('header', 'Create New Category')
@section('subheader', 'Add a top-level group for your catalog')

@section('content')
    <div class="login-card" style="max-width: 600px; margin: 0 auto; animation: none;">
        <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="form-group">
                <label class="form-label">Category Name</label>
                <input type="text" name="name" class="form-control" required placeholder="e.g. Coffee, Pastries, Tea">
                @error('name') <div class="error-message">{{ $message }}</div> @enderror
            </div>

            <div class="form-group" style="margin-top: 20px;">
                <label class="form-label">Category Image</label>
                <input type="file" name="image" class="form-control" accept="image/*" style="padding: 10px; background: rgba(255, 255, 255, 0.05); border: 1px solid var(--border); border-radius: 8px; color: var(--text); width: 100%; box-sizing: border-box;">
                @error('image') <div class="error-message">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label class="remember-me" style="margin-top: 20px;">
                    <input type="checkbox" name="is_active" checked style="display: none;">
                    <div class="checkbox-custom"></div>
                    <span class="remember-text">Active and visible in catalog</span>
                </label>
            </div>

            <div style="display: flex; gap: 15px; margin-top: 30px;">
                <button type="submit" class="btn-primary">Save Category</button>
                <a href="{{ route('admin.categories.index') }}" class="btn-primary" style="background: rgba(255, 255, 255, 0.05); color: var(--text); text-align: center; text-decoration: none;">Cancel</a>
            </div>
        </form>
    </div>
@endsection

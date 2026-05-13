@extends('layouts.admin')

@section('title', 'Edit Sub-Category')
@section('header', 'Edit: ' . $subCategory->name)
@section('subheader', 'Update your sub-grouping details')

@section('content')
    <div class="login-card" style="max-width: 600px; margin: 0 auto; animation: none;">
        <form action="{{ route('admin.sub_categories.update', $subCategory) }}" method="POST">
            @csrf @method('PUT')
            
            <div class="form-group">
                <label class="form-label">Parent Category</label>
                <select name="category_id" class="form-control" required>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ $subCategory->category_id == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id') <div class="error-message">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Sub-Category Name</label>
                <input type="text" name="name" class="form-control" value="{{ $subCategory->name }}" required>
                @error('name') <div class="error-message">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label class="remember-me" style="margin-top: 20px;">
                    <input type="checkbox" name="is_active" {{ $subCategory->is_active ? 'checked' : '' }} style="display: none;">
                    <div class="checkbox-custom"></div>
                    <span class="remember-text">Active and visible in catalog</span>
                </label>
            </div>

            <div style="display: flex; gap: 15px; margin-top: 30px;">
                <button type="submit" class="btn-primary">Update Sub-Category</button>
                <a href="{{ route('admin.sub_categories.index') }}" class="btn-primary" style="background: rgba(255, 255, 255, 0.05); color: var(--text); text-align: center; text-decoration: none;">Cancel</a>
            </div>
        </form>
    </div>
@endsection

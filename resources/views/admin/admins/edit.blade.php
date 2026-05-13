@extends('layouts.admin')

@section('title', 'Edit Admin')
@section('header', 'Edit: ' . $admin->name)
@section('subheader', 'Update administrative permissions')

@section('content')
    <div class="login-card" style="max-width: 600px; margin: 0 auto; animation: none;">
        <form action="{{ route('admin.admins.update', $admin) }}" method="POST">
            @csrf @method('PUT')
            
            <div class="form-group">
                <label class="form-label">Full Name</label>
                <input type="text" name="name" class="form-control" value="{{ $admin->name }}" required>
                @error('name') <div class="error-message">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Email Address</label>
                <input type="email" name="email" class="form-control" value="{{ $admin->email }}" required>
                @error('email') <div class="error-message">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Role</label>
                <select name="role" class="form-control" required onchange="toggleStoreSelect(this.value)">
                    <option value="super_admin" {{ $admin->role === 'super_admin' ? 'selected' : '' }}>Super Admin (Global Access)</option>
                    <option value="store_manager" {{ $admin->role === 'store_manager' ? 'selected' : '' }}>Store Manager (Restricted Access)</option>
                </select>
            </div>

            <div class="form-group" id="store-select-group" style="display: {{ $admin->role === 'store_manager' ? 'block' : 'none' }};">
                <label class="form-label">Assign to Store</label>
                <select name="store_id" class="form-control">
                    <option value="">Select a store...</option>
                    @foreach($stores as $store)
                        <option value="{{ $store->id }}" {{ $admin->store_id == $store->id ? 'selected' : '' }}>{{ $store->name }}</option>
                    @endforeach
                </select>
                @error('store_id') <div class="error-message">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">New Password (Leave blank to keep current)</label>
                <input type="password" name="password" class="form-control" placeholder="••••••••">
                @error('password') <div class="error-message">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Confirm New Password</label>
                <input type="password" name="password_confirmation" class="form-control" placeholder="••••••••">
            </div>

            <div style="display: flex; gap: 15px; margin-top: 30px;">
                <button type="submit" class="btn-primary">Update Account</button>
                <a href="{{ route('admin.admins.index') }}" class="btn-primary" style="background: rgba(255, 255, 255, 0.05); color: #fff; text-align: center; text-decoration: none;">Cancel</a>
            </div>
        </form>
    </div>

    <script>
        function toggleStoreSelect(role) {
            const group = document.getElementById('store-select-group');
            group.style.display = (role === 'store_manager') ? 'block' : 'none';
        }
    </script>
@endsection

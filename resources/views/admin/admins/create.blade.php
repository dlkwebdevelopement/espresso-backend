@extends('layouts.admin')

@section('title', 'Invite Admin')
@section('header', 'Create New Admin')
@section('subheader', 'Grant administrative access to a team member')

@section('content')
    <div class="login-card" style="max-width: 600px; margin: 0 auto; animation: none;">
        <form action="{{ route('admin.admins.store') }}" method="POST">
            @csrf
            
            <div class="form-group">
                <label class="form-label">Full Name</label>
                <input type="text" name="name" class="form-control" required placeholder="e.g. John Doe">
                @error('name') <div class="error-message">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Email Address</label>
                <input type="email" name="email" class="form-control" required placeholder="admin@espresso.com">
                @error('email') <div class="error-message">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Role</label>
                <select name="role" class="form-control" required onchange="toggleStoreSelect(this.value)">
                    <option value="super_admin">Super Admin (Global Access)</option>
                    <option value="store_manager">Store Manager (Restricted Access)</option>
                </select>
            </div>

            <div class="form-group" id="store-select-group" style="display: none;">
                <label class="form-label">Assign to Store</label>
                <select name="store_id" class="form-control">
                    <option value="">Select a store...</option>
                    @foreach($stores as $store)
                        <option value="{{ $store->id }}">{{ $store->name }}</option>
                    @endforeach
                </select>
                @error('store_id') <div class="error-message">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required placeholder="••••••••">
                @error('password') <div class="error-message">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Confirm Password</label>
                <input type="password" name="password_confirmation" class="form-control" required placeholder="••••••••">
            </div>

            <div style="display: flex; gap: 15px; margin-top: 30px;">
                <button type="submit" class="btn-primary">Create Account</button>
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

@extends('layouts.admin')

@section('title', 'Store Dashboard')
@section('header', $store->name)
@section('subheader', 'Location: ' . $store->location)

@section('content')
    <!-- Stats Grid -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-value">{{ $itemCount }}</div>
            <div class="stat-label">Active Products</div>
        </div>
        <div class="stat-card">
            <div class="stat-value">{{ $categoryCount }}</div>
            <div class="stat-label">Available Categories</div>
        </div>
        <div class="stat-card">
            <div class="stat-value">0</div>
            <div class="stat-label">Pending Orders</div>
        </div>
        <div class="stat-card">
            <div class="stat-value">4.8 ★</div>
            <div class="stat-label">Store Rating</div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px; margin-bottom: 40px;">
        <div class="stat-card">
            <h3 style="margin-bottom: 20px;">Quick Actions</h3>
            <div style="display: flex; gap: 15px; flex-wrap: wrap;">
                <a href="{{ route('admin.items.create') }}" class="btn-primary" style="width: auto; padding: 12px 24px; text-decoration: none;">+ Add New Item</a>
                <a href="{{ route('admin.items.index') }}" class="btn-primary" style="width: auto; padding: 12px 24px; text-decoration: none; background: rgba(255, 255, 255, 0.05); color: var(--text);">Manage Inventory</a>
            </div>
        </div>

        <div class="stat-card">
            <h3 style="margin-bottom: 20px;">Store Info</h3>
            <div style="font-size: 14px; line-height: 1.6;">
                <div style="display: flex; justify-content: space-between; border-bottom: 1px solid var(--border); padding: 8px 0;">
                    <span style="color: var(--text-muted);">Manager:</span>
                    <span>{{ auth('admin')->user()->name }}</span>
                </div>
                <div style="display: flex; justify-content: space-between; padding: 8px 0;">
                    <span style="color: var(--text-muted);">Contact:</span>
                    <span>{{ $store->contact_number }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Store Specific Orders Placeholder -->
    <div class="stat-card" style="width: 100%;">
        <h3 style="margin-bottom: 20px;">Recent Orders</h3>
        <div style="color: var(--text-muted); font-size: 14px; text-align: center; padding: 40px;">
            No local orders to display yet.
        </div>
    </div>
@endsection

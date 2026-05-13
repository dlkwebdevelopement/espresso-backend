@extends('layouts.admin')

@section('title', 'Global Command Center')
@section('header', 'Global Command Center')
@section('subheader', 'System-wide overview and store performance')

@section('content')
    <!-- Stats Grid -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-value">BD 1,284.00</div>
            <div class="stat-label">Total Revenue</div>
        </div>
        <div class="stat-card">
            <div class="stat-value">48</div>
            <div class="stat-label">Active Orders</div>
        </div>
        <div class="stat-card">
            <div class="stat-value">1,024</div>
            <div class="stat-label">Total Users</div>
        </div>
        <div class="stat-card">
            <div class="stat-value">BD 4.2K</div>
            <div class="stat-label">Wallet Volume</div>
        </div>
    </div>

    <!-- Recent Activity Table Placeholder -->
    <div class="stat-card" style="width: 100%;">
        <h3 style="margin-bottom: 20px;">Global System Logs</h3>
        <div style="color: var(--text-muted); font-size: 14px; text-align: center; padding: 40px;">
            No global system logs to display.
        </div>
    </div>
@endsection

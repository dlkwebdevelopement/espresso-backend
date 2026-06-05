@extends('layouts.admin')

@section('title', 'Global Command Center')
@section('header', 'Global Command Center')
@section('subheader', 'System-wide overview and store performance')

@section('content')
    <!-- Stats Grid -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-value" style="color: #4CAF50;">{{ \App\Models\Setting::get('currency_symbol', 'BHD') }} {{ number_format($totalRevenue, 2) }}</div>
            <div class="stat-label">Total Revenue</div>
        </div>
        <div class="stat-card">
            <div class="stat-value" style="color: #FF9800;">{{ number_format($activeOrders) }}</div>
            <div class="stat-label">Active Orders</div>
        </div>
        <div class="stat-card">
            <div class="stat-value" style="color: #2196F3;">{{ number_format($totalUsers) }}</div>
            <div class="stat-label">Total Users</div>
        </div>
        <div class="stat-card">
            <div class="stat-value">{{ \App\Models\Setting::get('currency_symbol', 'BHD') }} {{ number_format($walletVolume, 2) }}</div>
            <div class="stat-label">Wallet Volume</div>
        </div>
    </div>

    <!-- Charts Row -->
    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 30px; margin-bottom: 40px;">
        <!-- Revenue Trend Chart -->
        <div class="stat-card" style="padding: 0; overflow: hidden; display: flex; flex-direction: column;">
            <div style="padding: 20px; border-bottom: 1px solid var(--border);">
                <h3 style="margin: 0; font-size: 16px;">Revenue Trend (Last 7 Days)</h3>
            </div>
            <div style="padding: 20px; flex: 1; min-height: 300px; position: relative;">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>

        <!-- Branch Revenue Chart -->
        <div class="stat-card" style="padding: 0; overflow: hidden; display: flex; flex-direction: column;">
            <div style="padding: 20px; border-bottom: 1px solid var(--border);">
                <h3 style="margin: 0; font-size: 16px;">Revenue by Branch</h3>
            </div>
            <div style="padding: 20px; flex: 1; min-height: 300px; position: relative; display: flex; justify-content: center; align-items: center;">
                @if(count($branchData) > 0)
                    <canvas id="branchChart"></canvas>
                @else
                    <div style="color: var(--text-muted); font-size: 14px; text-align: center;">No store revenue data yet.</div>
                @endif
            </div>
        </div>
    </div>

    <!-- Recent Activity Table -->
    <div class="stat-card" style="padding: 0; overflow: hidden; margin-bottom: 40px;">
        <div style="padding: 20px; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center;">
            <h3 style="margin: 0; font-size: 16px;">Recent Orders</h3>
            <a href="{{ route('admin.orders.index') }}" style="font-size: 12px; color: var(--primary); text-decoration: none; font-weight: 600;">View All</a>
        </div>
        <div style="padding: 0;">
            <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 14px;">
                <tbody>
                    @forelse($recentOrders as $order)
                        <tr style="border-bottom: 1px solid var(--border); transition: background 0.3s;" onmouseover="this.style.background='rgba(var(--primary-rgb, 255, 222, 173), 0.03)'" onmouseout="this.style.background='none'">
                            <td style="padding: 15px 20px;">
                                <div style="font-weight: 600; margin-bottom: 4px;">#{{ $order->order_id }}</div>
                                <div style="font-size: 12px; color: var(--text-muted);">{{ $order->user->name ?? 'Guest' }}</div>
                            </td>
                            <td style="padding: 15px 20px;">
                                <div style="font-size: 12px; color: var(--text-muted);">Store</div>
                                <div style="font-size: 13px;">{{ $order->store->name ?? 'Online/Global' }}</div>
                            </td>
                            <td style="padding: 15px 20px; text-align: right;">
                                <div style="font-weight: 600; color: #4CAF50; margin-bottom: 4px;">{{ \App\Models\Setting::get('currency_symbol', 'BHD') }} {{ number_format($order->total, 2) }}</div>
                                <div style="font-size: 11px; text-transform: uppercase;">
                                    @if($order->status === 'completed')
                                        <span style="color: #4CAF50;">{{ $order->status }}</span>
                                    @elseif(in_array($order->status, ['refunded', 'refund_pending']))
                                        <span style="color: #F44336;">{{ str_replace('_', ' ', $order->status) }}</span>
                                    @else
                                        <span style="color: #FF9800;">{{ $order->status }}</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" style="padding: 40px 20px; text-align: center; color: var(--text-muted);">No recent orders found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('revenueChart').getContext('2d');
        
        // Pass PHP data to JS
        const chartLabels = {!! json_encode($chartLabels) !!};
        const chartData = {!! json_encode($chartData) !!};
        
        // Setup Chart
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: chartLabels,
                datasets: [{
                    label: 'Revenue',
                    data: chartData,
                    borderColor: '#FFDEAD', // Using the dark theme primary by default for the line
                    backgroundColor: 'rgba(255, 222, 173, 0.1)',
                    borderWidth: 3,
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: '#FFDEAD',
                    pointBorderColor: '#fff',
                    pointRadius: 4,
                    pointHoverRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        titleFont: { size: 13, family: "'Montserrat', sans-serif" },
                        bodyFont: { size: 14, family: "'Montserrat', sans-serif", weight: 'bold' },
                        displayColors: false,
                        callbacks: {
                            label: function(context) {
                                return '{{ \App\Models\Setting::get('currency_symbol', 'BHD') }} ' + context.parsed.y.toFixed(2);
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false,
                            drawBorder: false
                        },
                        ticks: {
                            font: { family: "'Montserrat', sans-serif", size: 11 },
                            color: '#8D6E63'
                        }
                    },
                    y: {
                        grid: {
                            color: 'rgba(141, 110, 99, 0.1)',
                            drawBorder: false
                        },
                        ticks: {
                            font: { family: "'Montserrat', sans-serif", size: 11 },
                            color: '#8D6E63',
                            callback: function(value) {
                                return '{{ \App\Models\Setting::get('currency_symbol', 'BHD') }} ' + value;
                            }
                        },
                        beginAtZero: true
                    }
                }
            }
        });

        // Branch Revenue Doughnut Chart
        const branchCanvas = document.getElementById('branchChart');
        if (branchCanvas) {
            const bCtx = branchCanvas.getContext('2d');
            const branchLabels = {!! json_encode($branchLabels ?? []) !!};
            const branchData = {!! json_encode($branchData ?? []) !!};

            new Chart(bCtx, {
                type: 'doughnut',
                data: {
                    labels: branchLabels,
                    datasets: [{
                        data: branchData,
                        backgroundColor: [
                            '#FFDEAD', // Primary Theme
                            '#4CAF50', // Green
                            '#2196F3', // Blue
                            '#FF9800', // Orange
                            '#9C27B0'  // Purple
                        ],
                        borderWidth: 0,
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '75%',
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                font: { family: "'Montserrat', sans-serif", size: 12 },
                                color: '#8D6E63',
                                padding: 20
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            padding: 12,
                            titleFont: { size: 13, family: "'Montserrat', sans-serif" },
                            bodyFont: { size: 14, family: "'Montserrat', sans-serif", weight: 'bold' },
                            callbacks: {
                                label: function(context) {
                                    return ' ' + context.label + ': {{ \App\Models\Setting::get('currency_symbol', 'BHD') }} ' + context.parsed.toFixed(2);
                                }
                            }
                        }
                    }
                }
            });
        }
    });
</script>
@endsection

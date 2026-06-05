<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\User;
use App\Models\WalletTransaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Calculate Core Metrics
        $totalRevenue = Order::whereNotIn('status', ['refunded'])->sum('total');
        $activeOrders = Order::whereNotIn('status', ['completed', 'refunded'])->count();
        $totalUsers = User::count();
        $walletVolume = WalletTransaction::where('type', 'credit')->sum('amount'); // Only counting credits (deposits)

        // 2. Fetch Recent Activity
        // Note: Assuming User relation exists on Order
        $recentOrders = Order::with('user')->latest()->take(5)->get();

        // 3. Prepare Chart Data: Last 7 Days Revenue
        $chartData = [];
        $chartLabels = [];

        // Generate the last 7 days including today
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $chartLabels[] = $date->format('M d');

            // Sum total for this specific day
            $dailyTotal = Order::whereNotIn('status', ['refunded'])
                ->whereDate('created_at', $date->toDateString())
                ->sum('total');

            $chartData[] = $dailyTotal;
        }

        // 4. Prepare Branch Revenue Data
        $branchRevenueQuery = Order::whereNotIn('status', ['refunded'])
            ->whereNotNull('store_id')
            ->select('store_id', DB::raw('SUM(total) as store_total'))
            ->groupBy('store_id')
            ->with('store')
            ->get();

        $branchLabels = [];
        $branchData = [];
        
        foreach ($branchRevenueQuery as $br) {
            $branchLabels[] = $br->store ? $br->store->name : 'Unknown';
            $branchData[] = $br->store_total;
        }

        return view('admin.dashboard', compact(
            'totalRevenue',
            'activeOrders',
            'totalUsers',
            'walletVolume',
            'recentOrders',
            'chartLabels',
            'chartData',
            'branchLabels',
            'branchData'
        ));
    }
}

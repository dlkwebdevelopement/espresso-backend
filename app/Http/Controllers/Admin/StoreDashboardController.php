<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StoreDashboardController extends Controller
{
    public function index()
    {
        $admin = Auth::guard('admin')->user();
        $store = $admin->store;

        $itemCount = \App\Models\Item::where('store_id', $store->id)->count();
        $categoryCount = \App\Models\Category::where('is_active', true)->count();

        return view('admin.store_dashboard', compact('store', 'itemCount', 'categoryCount'));
    }
}

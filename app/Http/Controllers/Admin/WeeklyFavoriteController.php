<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WeeklyFavoriteController extends Controller
{
    public function index()
    {
        $admin = Auth::guard('admin')->user();
        $query = Item::where('is_weekly_favorite', true);

        if ($admin->role === 'store_manager') {
            $query->where('store_id', $admin->store_id);
        }

        $favorites = $query->with('store', 'subCategory')->paginate(6);

        // Get list of items NOT currently favorites to assign
        $assignQuery = Item::where('is_weekly_favorite', false);
        if ($admin->role === 'store_manager') {
            $assignQuery->where('store_id', $admin->store_id);
        }
        $availableItems = $assignQuery->get();

        return view('admin.weekly_favorites.index', compact('favorites', 'availableItems'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:items,id'
        ]);

        $item = Item::findOrFail($request->item_id);
        $admin = Auth::guard('admin')->user();

        // Security check for store managers
        if ($admin->role === 'store_manager' && $item->store_id !== $admin->store_id) {
            abort(403);
        }

        $item->update(['is_weekly_favorite' => true]);

        // Create global notification for this weekly favorite item
        \App\Models\Notification::create([
            'user_id' => null, // null means global broadcast
            'type' => 'promo',
            'title' => 'Weekly Favorite Alert!',
            'message' => 'Checkout the new ' . $item->name . ', featured as our weekly favorite! Get 10% off.',
            'read' => false,
        ]);

        return redirect()->route('admin.weekly-favorites.index')->with('success', 'Product assigned to Weekly Favorites!');
    }

    public function destroy($id)
    {
        $item = Item::findOrFail($id);
        $admin = Auth::guard('admin')->user();

        if ($admin->role === 'store_manager' && $item->store_id !== $admin->store_id) {
            abort(403);
        }

        $item->update(['is_weekly_favorite' => false]);

        return redirect()->route('admin.weekly-favorites.index')->with('success', 'Product removed from Weekly Favorites.');
    }
}

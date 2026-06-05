<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PopularItemController extends Controller
{
    public function index()
    {
        $admin = Auth::guard('admin')->user();
        $query = Item::where('is_popular', true);

        if ($admin->role === 'store_manager') {
            $query->where('store_id', $admin->store_id);
        }

        $popularItems = $query->with('store', 'subCategory')->paginate(6);

        // Get list of items NOT currently popular to assign
        $assignQuery = Item::where('is_popular', false);
        if ($admin->role === 'store_manager') {
            $assignQuery->where('store_id', $admin->store_id);
        }
        $availableItems = $assignQuery->get();

        return view('admin.popular_items.index', compact('popularItems', 'availableItems'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:items,id'
        ]);

        $item = Item::findOrFail($request->item_id);
        $admin = Auth::guard('admin')->user();

        if ($admin->role === 'store_manager' && $item->store_id !== $admin->store_id) {
            abort(403);
        }

        $item->update(['is_popular' => true]);

        return redirect()->route('admin.popular-items.index')->with('success', 'Product assigned to Popular Items!');
    }

    public function destroy($id)
    {
        $item = Item::findOrFail($id);
        $admin = Auth::guard('admin')->user();

        if ($admin->role === 'store_manager' && $item->store_id !== $admin->store_id) {
            abort(403);
        }

        $item->update(['is_popular' => false]);

        return redirect()->route('admin.popular-items.index')->with('success', 'Product removed from Popular Items.');
    }
}

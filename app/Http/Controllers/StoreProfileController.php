<?php

namespace App\Http\Controllers;

use App\Models\Store;
use Illuminate\Http\Request;

class StoreProfileController extends Controller
{
    public function show($id)
    {
        $store = Store::findOrFail($id);
        
        if (!$store->is_active) {
            abort(404, 'Store is temporarily closed.');
        }

        $favorites = $store->items()->where('is_weekly_favorite', true)->where('is_available', true)->get();
        $popularItems = $store->items()->where('is_popular', true)->where('is_available', true)->get();

        return view('stores.show', compact('store', 'favorites', 'popularItems'));
    }
}

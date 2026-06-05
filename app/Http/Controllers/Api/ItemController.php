<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function getCategoriesAndItems(Request $request)
    {
        // Get all categories
        $categories = Category::all(['id', 'name']);
        
        // Get all items that are available, with attributes and options
        $query = Item::with(['subCategory.category', 'attributes.options', 'store'])
                     ->where('is_available', true);

        if ($request->filled('location')) {
            $location = $request->input('location');
            
            // Check if there are any stores matching this location/name
            $hasMatchingStore = \App\Models\Store::where('location', 'like', "%{$location}%")
                                      ->orWhere('name', 'like', "%{$location}%")
                                      ->exists();
            
            if ($hasMatchingStore) {
                $query->whereHas('store', function($q) use ($location) {
                    $q->where('location', 'like', "%{$location}%")
                      ->orWhere('name', 'like', "%{$location}%");
                });
            }
        }

        $items = $query->latest()->get();

        return response()->json([
            'status' => 'success',
            'data' => [
                'categories' => $categories,
                'items' => $items
            ]
        ]);
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Item;
use App\Models\ItemAttribute;
use App\Models\ItemAttributeOption;
use App\Models\Store;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ItemController extends Controller
{
    public function index()
    {
        $admin = Auth::guard('admin')->user();
        $query = Item::with(['store', 'subCategory.category']);

        if ($admin->role === 'store_manager') {
            $query->where('store_id', $admin->store_id);
        }

        $items = $query->latest()->paginate(10);
        return view('admin.items.index', compact('items'));
    }

    public function create()
    {
        $admin = Auth::guard('admin')->user();

        $stores = $admin->role === 'super_admin'
            ? Store::all()
            : Store::where('id', $admin->store_id)->get();

        $categories = Category::with('subCategories')->get();

        return view('admin.items.create', compact('stores', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'store_id' => 'required|exists:stores,id',
            'sub_category_id' => 'required|exists:sub_categories,id',
            'name' => 'required|string|max:255',
            'base_price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:10240',
        ]);

        $itemData = [
            'store_id' => $request->store_id,
            'sub_category_id' => $request->sub_category_id,
            'name' => $request->name,
            'description' => $request->description,
            'base_price' => $request->base_price,
            'is_available' => $request->has('is_available'),
            'is_weekly_favorite' => $request->has('is_weekly_favorite'),
        ];

        if ($request->hasFile('image')) {
            if (!$request->file('image')->isValid()) {
                return back()->withErrors(['image' => 'The uploaded image is invalid or exceeds server limits.'])->withInput();
            }
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();

            // Ensure directory exists in PUBLIC folder
            if (!file_exists(('uploads/items'))) {
                mkdir(('uploads/items'), 0755, true);
            }

            $image->move(('uploads/items'), $imageName);
            $itemData['image_path'] = 'uploads/items/' . $imageName;
        }

        $item = Item::create($itemData);

        // Handle dynamic attributes and options
        if ($request->has('attributes')) {
            foreach ($request->input('attributes') as $attrData) {
                if (empty($attrData['name']))
                    continue;

                $attribute = $item->attributes()->create(['name' => $attrData['name']]);

                if (isset($attrData['options'])) {
                    foreach ($attrData['options'] as $optData) {
                        if (empty($optData['value']))
                            continue;
                        $attribute->options()->create([
                            'value' => $optData['value'],
                            'price_adjustment' => $optData['price'] ?? 0
                        ]);
                    }
                }
            }
        }

        return redirect()->route('admin.items.index')->with('success', 'Item created successfully.');
    }

    public function edit(Item $item)
    {
        $admin = Auth::guard('admin')->user();
        if ($admin->role === 'store_manager' && $item->store_id !== $admin->store_id) {
            abort(403);
        }

        $stores = $admin->role === 'super_admin' ? Store::all() : Store::where('id', $admin->store_id)->get();
        $categories = Category::with('subCategories')->get();
        $item->load('attributes.options');

        return view('admin.items.edit', compact('item', 'stores', 'categories'));
    }

    public function update(Request $request, Item $item)
    {
        $admin = Auth::guard('admin')->user();
        if ($admin->role === 'store_manager' && $item->store_id !== $admin->store_id) {
            abort(403);
        }

        $request->validate([
            'store_id' => 'required|exists:stores,id',
            'sub_category_id' => 'required|exists:sub_categories,id',
            'name' => 'required|string|max:255',
            'base_price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:10240',
        ]);

        $itemData = [
            'store_id' => $request->store_id,
            'sub_category_id' => $request->sub_category_id,
            'name' => $request->name,
            'description' => $request->description,
            'base_price' => $request->base_price,
            'is_available' => $request->has('is_available'),
        ];

        if ($request->hasFile('image')) {
            if (!$request->file('image')->isValid()) {
                return back()->withErrors(['image' => 'The uploaded image is invalid or exceeds server limits.'])->withInput();
            }
            if ($item->image_path && file_exists(($item->image_path))) {
                unlink(($item->image_path));
            }
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();

            if (!file_exists(('uploads/items'))) {
                mkdir(('uploads/items'), 0755, true);
            }

            $image->move(('uploads/items'), $imageName);
            $itemData['image_path'] = 'uploads/items/' . $imageName;
        }

        $item->update($itemData);

        // Simple sync for attributes: Delete and recreate
        $item->attributes()->delete();
        if ($request->has('attributes')) {
            foreach ($request->input('attributes') as $attrData) {
                if (empty($attrData['name']))
                    continue;

                $attribute = $item->attributes()->create(['name' => $attrData['name']]);

                if (isset($attrData['options'])) {
                    foreach ($attrData['options'] as $optData) {
                        if (empty($optData['value']))
                            continue;
                        $attribute->options()->create([
                            'value' => $optData['value'],
                            'price_adjustment' => $optData['price'] ?? 0
                        ]);
                    }
                }
            }
        }

        return redirect()->route('admin.items.index')->with('success', 'Item updated successfully.');
    }

    public function destroy(Item $item)
    {
        $admin = Auth::guard('admin')->user();
        if ($admin->role === 'store_manager' && $item->store_id !== $admin->store_id) {
            abort(403);
        }

        if ($item->image_path && file_exists(($item->image_path))) {
            unlink(($item->image_path));
        }
        $item->delete();

        return redirect()->route('admin.items.index')->with('success', 'Item deleted successfully.');
    }
}

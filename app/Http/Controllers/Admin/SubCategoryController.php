<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class SubCategoryController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('super_admin', except: ['index']),
        ];
    }

    public function index()
    {
        $subCategories = SubCategory::with('category')->paginate(10);
        return view('admin.sub_categories.index', compact('subCategories'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.sub_categories.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255|unique:sub_categories',
        ]);

        SubCategory::create([
            'category_id' => $request->category_id,
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.sub_categories.index')->with('success', 'Sub-Category created successfully.');
    }

    public function edit(SubCategory $subCategory)
    {
        $categories = Category::all();
        return view('admin.sub_categories.edit', compact('subCategory', 'categories'));
    }

    public function update(Request $request, SubCategory $subCategory)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255|unique:sub_categories,name,' . $subCategory->id,
        ]);

        $subCategory->update([
            'category_id' => $request->category_id,
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.sub_categories.index')->with('success', 'Sub-Category updated successfully.');
    }

    public function destroy(SubCategory $subCategory)
    {
        $subCategory->delete();
        return redirect()->route('admin.sub_categories.index')->with('success', 'Sub-Category deleted successfully.');
    }
}

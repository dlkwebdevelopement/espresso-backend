<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FreeDrink;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FreeDrinkController extends Controller
{
    public function index()
    {
        $freeDrinks = FreeDrink::all();
        return view('admin.free_drinks.index', compact('freeDrinks'));
    }

    public function create()
    {
        return view('admin.free_drinks.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean'
        ]);

        $data = $request->except('image');
        $data['is_active'] = $request->has('is_active');

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(('uploads/free_drinks'), $filename);
            $data['image_path'] = 'uploads/free_drinks/' . $filename;
        }

        FreeDrink::create($data);

        return redirect()->route('admin.free-drinks.index')->with('success', 'Free Drink created successfully.');
    }

    public function edit(FreeDrink $freeDrink)
    {
        return view('admin.free_drinks.edit', compact('freeDrink'));
    }

    public function update(Request $request, FreeDrink $freeDrink)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean'
        ]);

        $data = $request->except('image');
        $data['is_active'] = $request->has('is_active');

        if ($request->hasFile('image')) {
            // Delete old image if it exists
            if ($freeDrink->image_path && file_exists(($freeDrink->image_path))) {
                unlink(($freeDrink->image_path));
            }
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(('uploads/free_drinks'), $filename);
            $data['image_path'] = 'uploads/free_drinks/' . $filename;
        }

        $freeDrink->update($data);

        return redirect()->route('admin.free-drinks.index')->with('success', 'Free Drink updated successfully.');
    }

    public function destroy(FreeDrink $freeDrink)
    {
        if ($freeDrink->image_path && file_exists(($freeDrink->image_path))) {
            unlink(($freeDrink->image_path));
        }

        $freeDrink->delete();

        return redirect()->route('admin.free-drinks.index')->with('success', 'Free Drink deleted successfully.');
    }
}

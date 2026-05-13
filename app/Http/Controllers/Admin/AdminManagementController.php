<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminManagementController extends Controller
{
    public function index()
    {
        $admins = Admin::with('store')->get();
        return view('admin.admins.index', compact('admins'));
    }

    public function create()
    {
        $stores = Store::all();
        return view('admin.admins.create', compact('stores'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:admins',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|string|in:super_admin,store_manager',
            'store_id' => 'required_if:role,store_manager|nullable|exists:stores,id',
        ]);

        $data['password'] = Hash::make($data['password']);
        $data['is_super_admin'] = ($data['role'] === 'super_admin');

        Admin::create($data);

        return redirect()->route('admin.admins.index')->with('success', 'Admin created successfully.');
    }

    public function edit(Admin $admin)
    {
        $stores = Store::all();
        return view('admin.admins.edit', compact('admin', 'stores'));
    }

    public function update(Request $request, Admin $admin)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:admins,email,' . $admin->id,
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|string|in:super_admin,store_manager',
            'store_id' => 'required_if:role,store_manager|nullable|exists:stores,id',
        ]);

        if ($request->filled('password')) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $data['is_super_admin'] = ($data['role'] === 'super_admin');
        if ($data['is_super_admin']) {
            $data['store_id'] = null;
        }

        $admin->update($data);

        return redirect()->route('admin.admins.index')->with('success', 'Admin updated successfully.');
    }

    public function destroy(Admin $admin)
    {
        if ($admin->id === auth('admin')->id()) {
            return back()->with('error', 'You cannot delete yourself.');
        }
        $admin->delete();
        return redirect()->route('admin.admins.index')->with('success', 'Admin deleted successfully.');
    }
}

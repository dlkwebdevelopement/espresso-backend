<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = User::latest()->paginate(15);
        return view('admin.customers.index', compact('customers'));
    }

    public function create()
    {
        return view('admin.customers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'mobile_number' => 'required|string|max:20|unique:users',
            'dob' => 'required|date',
        ]);

        // When staff creates an account, we generate a random password
        // They can change it later or use OTP login
        $randomPassword = Str::random(10);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'mobile_number' => $request->mobile_number,
            'dob' => $request->dob,
            'password' => Hash::make($randomPassword),
        ]);

        return redirect()->route('admin.customers.index')->with('success', 'Customer account created successfully.');
    }

    public function edit(User $customer)
    {
        return view('admin.customers.edit', compact('customer'));
    }

    public function update(Request $request, User $customer)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $customer->id,
            'mobile_number' => 'required|string|max:20|unique:users,mobile_number,' . $customer->id,
            'dob' => 'required|date',
        ]);

        $customer->update($request->only(['name', 'email', 'mobile_number', 'dob']));

        return redirect()->route('admin.customers.index')->with('success', 'Customer details updated.');
    }

    public function destroy(User $customer)
    {
        $customer->delete();
        return redirect()->route('admin.customers.index')->with('success', 'Customer account deleted.');
    }
}

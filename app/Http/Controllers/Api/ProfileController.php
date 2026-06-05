<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    /**
     * Get user profile details.
     */
    public function getProfile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::where('email', $request->email)->first();

        if ($user) {
            return response()->json([
                'status' => 'success',
                'user' => [
                    'name' => $user->name,
                    'email' => $user->email,
                    'mobile_number' => $user->mobile_number,
                    'dob' => $user->dob,
                    'avatar' => $user->avatar,
                ]
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'User not found.'
        ], 404);
    }

    /**
     * Update user profile information and avatar.
     */
    public function updateProfile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
            'name' => 'nullable|string|max:255',
            'mobile_number' => 'nullable|string|max:20',
            'dob' => 'nullable|date',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120', // 5MB max
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'User not found.'
            ], 404);
        }

        $updateData = [];

        if ($request->has('name')) {
            $updateData['name'] = $request->name;
        }

        if ($request->has('mobile_number')) {
            $updateData['mobile_number'] = $request->mobile_number;
        }

        if ($request->has('dob')) {
            $updateData['dob'] = $request->dob;
        }

        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $filename = time() . '_' . $user->id . '.' . $file->getClientOriginalExtension();
            $file->move(('avatars'), $filename);
            $updateData['avatar'] = url('avatars/' . $filename);
        }

        if (!empty($updateData)) {
            $user->update($updateData);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Profile updated successfully.',
            'user' => $user
        ]);
    }
}

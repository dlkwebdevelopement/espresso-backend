<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Change user password.
     */
    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
            'new_password' => 'required|string|min:6',
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
            $user->update([
                'password' => Hash::make($request->new_password)
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Password changed successfully.'
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'User not found.'
        ], 404);
    }

    /**
     * Delete user account permanently.
     */
    public function deleteAccount(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
            'reason' => 'required|string|max:1000',
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
            $user->update([
                'deletion_requested' => true,
                'deletion_reason' => $request->reason,
                'deletion_requested_at' => now(),
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Your request to delete the account has been submitted successfully to the administrator.'
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'User not found.'
        ], 404);
    }

    /**
     * Get user favorite items.
     */
    public function getFavorites(Request $request)
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
            $favString = $user->favorites;
            $favorites = $favString ? json_decode($favString, true) : [];
            
            return response()->json([
                'status' => 'success',
                'favorites' => is_array($favorites) ? $favorites : []
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'User not found.'
        ], 404);
    }

    /**
     * Sync user favorite items.
     */
    public function syncFavorites(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
            'favorites' => 'required|array',
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
            $user->update([
                'favorites' => json_encode($request->favorites)
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Favorites successfully synchronized.'
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'User not found.'
        ], 404);
    }

    /**
     * Add an item to user favorites.
     */
    public function addFavorite(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
            'item' => 'required|string',
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
            $favString = $user->favorites;
            $favorites = $favString ? json_decode($favString, true) : [];
            if (!is_array($favorites)) {
                $favorites = [];
            }

            $item = $request->item;
            if (!in_array($item, $favorites)) {
                $favorites[] = $item;
                $user->update([
                    'favorites' => json_encode($favorites)
                ]);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Item added to favorites.',
                'favorites' => $favorites
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'User not found.'
        ], 404);
    }

    /**
     * Remove an item from user favorites.
     */
    public function removeFavorite(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
            'item' => 'required|string',
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
            $favString = $user->favorites;
            $favorites = $favString ? json_decode($favString, true) : [];
            if (!is_array($favorites)) {
                $favorites = [];
            }

            $item = $request->item;
            $favorites = array_values(array_filter($favorites, function($value) use ($item) {
                return (string)$value !== (string)$item;
            }));

            $user->update([
                'favorites' => json_encode($favorites)
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Item removed from favorites.',
                'favorites' => $favorites
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'User not found.'
        ], 404);
    }
}


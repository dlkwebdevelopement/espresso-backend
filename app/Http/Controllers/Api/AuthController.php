<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Carbon;

class AuthController extends Controller
{
    private function sendOtpEmail($email, $otp)
    {
        // Print OTP directly to console stdout/stderr for developer convenience
        file_put_contents('php://stderr', "\n[OTP INFO] Verification OTP for {$email} is: {$otp}\n\n");

        try {
            \Illuminate\Support\Facades\Mail::raw("Welcome to Espresso! Your verification code is: $otp. It will expire in 10 minutes.", function($message) use ($email, $otp) {
                $message->to($email)->subject("Your Espresso OTP: $otp");
            });
            return true;
        } catch (\Exception $e) {
            \Log::error("Mail Sending Error: " . $e->getMessage());
            return false;
        }
    }

    public function register(Request $request)
    {
        if ($request->has('country_code') && $request->has('mobile_number')) {
            $countryCode = $request->country_code;
            $mobileNumber = $request->mobile_number;
            
            // Check if mobile number already starts with the country code or '+'
            if (!str_starts_with($mobileNumber, '+') && !str_starts_with($mobileNumber, str_replace('+', '', $countryCode))) {
                $fullMobileNumber = $countryCode . $mobileNumber;
                $request->merge(['mobile_number' => $fullMobileNumber]);
            }
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'mobile_number' => 'required|string|max:20|unique:users',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $otp = rand(1000, 9999);
        
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'mobile_number' => $request->mobile_number,
            'otp' => $otp,
            'otp_expires_at' => Carbon::now()->addMinutes(10),
        ]);

        $this->sendOtpEmail($user->email, $otp);

        return response()->json([
            'status' => 'success',
            'message' => 'User registered successfully. OTP sent to your email.',
            'data' => [
                'user' => $user,
                'otp' => $otp // Keeping for dev ease
            ]
        ], 201);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'nullable|string',
            'mobile_number' => 'nullable|string',
            'country_code' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = null;

        // 1. If email is passed, search by email.
        // Also support fallback parsing of derived emails like user_36001234@espresso.com
        if ($request->has('email') && $request->email) {
            $email = $request->email;
            if (preg_match('/^user_(\d+)@espresso\.com$/', $email, $matches)) {
                $phoneWithoutCode = $matches[1];
                // Search for any user whose mobile_number ends with this phone number
                $user = User::where('mobile_number', 'like', '%' . $phoneWithoutCode)->first();
            } else {
                $user = User::where('email', $email)->first();
            }
        }
        
        // 2. If still not found, and mobile_number is passed, search by mobile_number
        if (!$user && $request->has('mobile_number') && $request->mobile_number) {
            $mobileNumber = $request->mobile_number;
            $countryCode = $request->country_code;

            if ($countryCode) {
                if (!str_starts_with($mobileNumber, '+') && !str_starts_with($mobileNumber, str_replace('+', '', $countryCode))) {
                    $mobileNumber = $countryCode . $mobileNumber;
                }
            }
            
            $user = User::where('mobile_number', $mobileNumber)->first();
        }

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'User not found'
            ], 404);
        }

        $otp = rand(1000, 9999);
        $user->update([
            'otp' => $otp,
            'otp_expires_at' => Carbon::now()->addMinutes(10),
        ]);

        $this->sendOtpEmail($user->email, $otp);

        return response()->json([
            'status' => 'success',
            'message' => 'OTP sent to your email.',
            'data' => [
                'user' => $user,
                'otp' => $otp
            ]
        ]);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'nullable|string',
            'mobile_number' => 'nullable|string',
            'country_code' => 'nullable|string',
            'otp' => 'required|string|size:4',
        ]);

        $query = User::where('otp', $request->otp)
                     ->where('otp_expires_at', '>', Carbon::now());

        $user = null;

        if ($request->has('mobile_number') && $request->mobile_number) {
            $mobileNumber = $request->mobile_number;
            $countryCode = $request->country_code;

            if ($countryCode) {
                if (!str_starts_with($mobileNumber, '+') && !str_starts_with($mobileNumber, str_replace('+', '', $countryCode))) {
                    $mobileNumber = $countryCode . $mobileNumber;
                }
            }
            $user = clone $query;
            $user = $user->where('mobile_number', $mobileNumber)->first();
            
            \Log::info('OTP VERIFY', [
                'phone' => $mobileNumber,
                'otp' => $request->otp,
                'found' => $user ? true : false
            ]);
        } elseif ($request->has('email') && $request->email) {
            $email = $request->email;
            if (preg_match('/^user_(\d+)@espresso\.com$/', $email, $matches)) {
                $phoneWithoutCode = $matches[1];
                $user = clone $query;
                $user = $user->where('mobile_number', 'like', '%' . $phoneWithoutCode)->first();
                
                \Log::info('OTP VERIFY (Fake Email Fallback)', [
                    'email' => $email,
                    'phone_fallback' => $phoneWithoutCode,
                    'otp' => $request->otp,
                    'found' => $user ? true : false
                ]);
            } else {
                $user = clone $query;
                $user = $user->where('email', $email)->first();
                
                \Log::info('OTP VERIFY', [
                    'email' => $email,
                    'otp' => $request->otp,
                    'found' => $user ? true : false
                ]);
            }
        }

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid or expired OTP'
            ], 422);
        }

        // Clear OTP after successful verification
        $user->update([
            'otp' => null,
            'otp_expires_at' => null,
        ]);

        // In a real app, you would create a Sanctum token here:
        // $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'message' => 'OTP verified successfully.',
            'data' => [
                'user' => $user,
                // 'token' => $token
            ]
        ]);
    }
}

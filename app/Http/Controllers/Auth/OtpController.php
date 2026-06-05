<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Carbon;

class OtpController extends Controller
{
    public function sendOtp(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:users,email']);

        $user = User::where('email', $request->email)->first();
        $otp = rand(100000, 999999);
        
        $user->update([
            'otp' => $otp,
            'otp_expires_at' => Carbon::now()->addMinutes(10),
        ]);

        // Send Email
        Mail::raw("Your Espresso login OTP is: $otp", function($message) use ($user) {
            $message->to($user->email)->subject('Login OTP - Espresso');
        });
        
        // For testing, we'll log it and also return it if in debug mode (optional)
        \Log::info("OTP for {$user->email}: $otp");

        return response()->json(['message' => 'OTP sent to your email.']);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'otp' => 'required|string|size:6',
        ]);

        $user = User::where('email', $request->email)
                    ->where('otp', $request->otp)
                    ->where('otp_expires_at', '>', Carbon::now())
                    ->first();

        if ($user) {
            $user->update(['otp' => null, 'otp_expires_at' => null]);
            Auth::login($user);
            return response()->json(['success' => true, 'redirect' => route('shop.index')]);
        }

        return response()->json(['success' => false, 'message' => 'Invalid or expired OTP.'], 422);
    }
}

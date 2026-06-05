<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class WalletController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
            'transaction_id' => 'required|string|unique:wallet_transactions',
            'title' => 'required|string',
            'type' => 'required|string|in:in,out',
            'amount' => 'required|numeric',
            'date' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = \App\Models\User::where('email', $request->email)->first();

        // Update the user's balance
        $amount = (float)$request->amount;
        if ($request->type === 'in') {
            $user->wallet_balance += $amount;
        } else {
            $user->wallet_balance = max(0, $user->wallet_balance - $amount);
        }
        $user->save();

        $transaction = WalletTransaction::create([
            'transaction_id' => $request->transaction_id,
            'user_id' => $user->id,
            'title' => $request->title,
            'type' => $request->type,
            'amount' => $amount,
            'date' => $request->date,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Wallet transaction logged successfully',
            'wallet_balance' => $user->wallet_balance,
            'data' => $transaction
        ], 201);
    }

    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = \App\Models\User::where('email', $request->email)->first();
        
        $transactions = WalletTransaction::where('user_id', $user->id)->latest()->limit(30)->get();

        return response()->json([
            'status' => 'success',
            'wallet_balance' => $user->wallet_balance,
            'data' => $transactions
        ]);
    }
}

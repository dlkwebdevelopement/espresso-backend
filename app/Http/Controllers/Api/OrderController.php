<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
            'order_id' => 'required|string|unique:orders',
            'store_id' => 'required|exists:stores,id',
            'items' => 'required|array',
            'subtotal' => 'required|numeric',
            'service_charge' => 'required|numeric',
            'vat' => 'required|numeric',
            'tip' => 'nullable|numeric',
            'total' => 'required|numeric',
            'payment_method' => 'nullable|string',
            'payment_status' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = \App\Models\User::where('email', $request->email)->first();

        // Create the order
        $order = Order::create([
            'order_id' => $request->order_id,
            'user_id' => $user->id,
            'store_id' => $request->store_id,
            'items' => $request->items,
            'subtotal' => $request->subtotal,
            'service_charge' => $request->service_charge,
            'vat' => $request->vat,
            'tip' => $request->tip ?? 0,
            'total' => $request->total,
            'status' => 'ordered',
            'payment_method' => $request->payment_method ?? 'card',
            'payment_status' => $request->payment_status ?? 'paid',
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Order placed successfully',
            'data' => $order
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
        
        $orders = Order::where('user_id', $user->id)->latest()->limit(20)->get();

        return response()->json([
            'status' => 'success',
            'data' => $orders
        ]);
    }

    public function refund(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
            'order_id' => 'required|string|exists:orders,order_id',
            'refund_reason' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = \App\Models\User::where('email', $request->email)->first();
        $order = Order::where('order_id', $request->order_id)
                      ->where('user_id', $user->id)
                      ->first();

        if (!$order) {
            return response()->json([
                'status' => 'error',
                'message' => 'Order not found or access denied'
            ], 404);
        }

        if ($order->payment_status === 'refunded') {
            return response()->json([
                'status' => 'error',
                'message' => 'Order has already been refunded'
            ], 400);
        }

        if ($order->payment_status === 'refund_pending') {
            return response()->json([
                'status' => 'error',
                'message' => 'Refund request is already pending approval'
            ], 400);
        }

        // Process refund request:
        // 1. Update order status to 'refund_pending'
        $order->status = 'refund_pending';
        $order->payment_status = 'refund_pending';
        $order->refund_reason = $request->refund_reason;
        $order->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Refund request submitted successfully and is pending approval',
            'data' => $order
        ]);
    }

    public function getOrderStatus($order_id)
    {
        $order = Order::where('order_id', $order_id)->first();
        if (!$order) {
            return response()->json(['status' => 'error', 'message' => 'Order not found'], 404);
        }
        return response()->json(['status' => 'success', 'data' => ['status' => $order->status]]);
    }

    public function getAllActiveOrders()
    {
        // Fetch orders that are not fully completed/refunded (adjust as needed for your flow)
        $orders = Order::whereNotIn('status', ['completed', 'refunded'])
                       ->latest()
                       ->get();
        return response()->json(['status' => 'success', 'data' => $orders]);
    }

    public function updateStatus(Request $request, $order_id)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|string|in:ordered,preparing,ready,completed,refund_pending,refunded',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => 'Validation failed', 'errors' => $validator->errors()], 422);
        }

        $order = Order::where('order_id', $order_id)->first();
        if (!$order) {
            return response()->json(['status' => 'error', 'message' => 'Order not found'], 404);
        }

        $order->status = $request->status;
        $order->save();

        return response()->json(['status' => 'success', 'message' => 'Order status updated', 'data' => $order]);
    }
}

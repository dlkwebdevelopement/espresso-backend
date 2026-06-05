<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $admin = Auth::guard('admin')->user();
        $query = Order::with('user')->latest();

        // Filter orders by search query (Order ID, User email, or User name)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('order_id', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('email', 'like', "%{$search}%")
                         ->orWhere('name', 'like', "%{$search}%");
                  });
            });
        }

        // Store manager isolation: only show orders that contain items belonging to their store
        if ($admin->role === 'store_manager') {
            $storeId = $admin->store_id;
            $storeItemIds = Item::where('store_id', $storeId)->pluck('id')->toArray();

            if (empty($storeItemIds)) {
                $query->whereNull('id'); // Force empty result if store has no items
            } else {
                $query->where(function ($q) use ($storeItemIds) {
                    foreach ($storeItemIds as $itemId) {
                        $q->orWhereJsonContains('items', ['id' => $itemId]);
                    }
                });
            }
        }

        $orders = $query->paginate(15)->withQueryString();

        return view('admin.orders.index', compact('orders'));
    }

    public function refund(Order $order, Request $request)
    {
        $admin = Auth::guard('admin')->user();

        // Authorization check for Store Managers
        if ($admin->role === 'store_manager') {
            $storeId = $admin->store_id;
            $storeItemIds = Item::where('store_id', $storeId)->pluck('id')->toArray();
            
            $hasStoreItem = false;
            $items = $order->items;
            if (is_string($items)) {
                $items = json_decode($items, true);
            }
            
            if (is_array($items)) {
                foreach ($items as $item) {
                    $itemId = is_array($item) ? ($item['id'] ?? null) : ($item->id ?? null);
                    if (in_array($itemId, $storeItemIds)) {
                        $hasStoreItem = true;
                        break;
                    }
                }
            }

            if (!$hasStoreItem) {
                abort(403, 'Unauthorized action.');
            }
        }

        if ($order->payment_status === 'refunded') {
            return back()->with('error', 'Order has already been refunded.');
        }

        $request->validate([
            'refund_reason' => 'required|string|max:255',
        ]);

        DB::transaction(function () use ($order, $request) {
            // 1. Update order and payment status
            $order->status = 'refunded';
            $order->payment_status = 'refunded';
            $order->refund_reason = $request->refund_reason;
            $order->save();

            // 2. Refund wallet amount
            $user = $order->user;
            if ($user) {
                $refundAmount = (float)$order->total;
                $user->wallet_balance += $refundAmount;
                $user->save();

                // 3. Create matching wallet transaction
                \App\Models\WalletTransaction::create([
                    'transaction_id' => 'REF-' . time() . '-' . rand(100, 999),
                    'user_id' => $user->id,
                    'title' => 'Refund: #' . $order->order_id,
                    'type' => 'in',
                    'amount' => $refundAmount,
                    'date' => date('M j, Y'),
                ]);

                // 4. Create notification
                \App\Models\Notification::create([
                    'user_id' => $user->id,
                    'type' => 'order',
                    'title' => 'Order Refunded',
                    'message' => 'Your order #' . $order->order_id . ' has been refunded. The refund amount has been added to your wallet.',
                    'read' => false,
                ]);
            }
        });

        return back()->with('success', 'Order #' . $order->order_id . ' has been refunded and the money returned to the customer\'s wallet.');
    }

    public function updateStatus(Request $request, Order $order)
    {
        $admin = Auth::guard('admin')->user();
        
        // Authorization check for Store Managers
        if ($admin->role === 'store_manager') {
            $storeId = $admin->store_id;
            $storeItemIds = Item::where('store_id', $storeId)->pluck('id')->toArray();
            
            $hasStoreItem = false;
            $items = $order->items;
            if (is_string($items)) {
                $items = json_decode($items, true);
            }
            
            if (is_array($items)) {
                foreach ($items as $item) {
                    $itemId = is_array($item) ? ($item['id'] ?? null) : ($item->id ?? null);
                    if (in_array($itemId, $storeItemIds)) {
                        $hasStoreItem = true;
                        break;
                    }
                }
            }

            if (!$hasStoreItem) {
                abort(403, 'Unauthorized action.');
            }
        }

        $request->validate([
            'status' => 'required|string|in:ordered,preparing,ready,completed',
        ]);

        $order->status = $request->status;
        $order->save();

        // Create notification
        \App\Models\Notification::create([
            'user_id' => $order->user_id,
            'type' => 'order',
            'title' => 'Order Status Update',
            'message' => 'Your order #' . $order->order_id . ' status has been updated to ' . ucfirst($request->status) . '.',
            'read' => false,
        ]);

        return back()->with('success', 'Order #' . $order->order_id . ' status updated to ' . ucfirst($request->status) . '.');
    }
}

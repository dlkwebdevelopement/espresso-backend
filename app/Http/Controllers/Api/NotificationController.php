<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class NotificationController extends Controller
{
    /**
     * Get user notifications
     */
    public function index(Request $request)
    {
        $email = $request->query('email');
        $userId = null;
        if ($email) {
            $user = User::where('email', $email)->first();
            if ($user) {
                $userId = $user->id;
            }
        }

        $query = Notification::latest();
        if ($userId) {
            $query->where(function ($q) use ($userId) {
                $q->whereNull('user_id')
                  ->orWhere('user_id', $userId);
            });
        } else {
            $query->whereNull('user_id');
        }

        $notifications = $query->get()->map(function ($notif) {
            return [
                'id' => $notif->id,
                'type' => $notif->type,
                'title' => $notif->title,
                'message' => $notif->message,
                'time' => $notif->created_at ? $notif->created_at->diffForHumans() : 'Just now',
                'read' => (bool)$notif->read,
            ];
        });

        // Fallback to mock data if empty
        if ($notifications->isEmpty()) {
            return response()->json([
                'status' => 'success',
                'data' => [
                    [
                        'id' => 1,
                        'type' => 'order',
                        'title' => 'Order Status Update',
                        'message' => 'Your order #1024 is currently being prepared by our barista.',
                        'time' => '10 mins ago',
                        'read' => false,
                    ],
                    [
                        'id' => 2,
                        'type' => 'promo',
                        'title' => 'Weekly Favorites',
                        'message' => 'Checkout the new Caramel Macchiato, featured as our weekly favorite! Get 10% off.',
                        'time' => '2 hours ago',
                        'read' => false,
                    ]
                ]
            ]);
        }

        return response()->json([
            'status' => 'success',
            'data' => $notifications
        ]);
    }

    /**
     * Register Expo Push Token
     */
    public function registerToken(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
        ]);

        Log::info('Expo Push Token registered: ' . $request->token);

        return response()->json([
            'status' => 'success',
            'message' => 'Push token registered successfully.'
        ]);
    }

    /**
     * Send a notification (internal API or admin panel trigger)
     */
    public function sendPushNotification(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string',
            'body' => 'required|string',
            'type' => 'nullable|string|in:promo,order',
            'to' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Save to Database
        $notification = Notification::create([
            'title' => $request->title,
            'message' => $request->body,
            'type' => $request->type ?? 'promo',
            'read' => false,
        ]);

        $expoResponse = null;
        if ($request->filled('to')) {
            try {
                $url = 'https://exp.host/--/api/v2/push/send';
                $data = [
                    'to' => $request->to,
                    'title' => $request->title,
                    'body' => $request->body,
                    'sound' => 'default',
                ];

                $options = [
                    'http' => [
                        'header'  => "Content-type: application/json\r\n",
                        'method'  => 'POST',
                        'content' => json_encode($data),
                    ],
                ];

                $context  = stream_context_create($options);
                $result = file_get_contents($url, false, $context);
                $expoResponse = json_decode($result, true);
            } catch (\Exception $e) {
                Log::error('Expo push notification error: ' . $e->getMessage());
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Push notification sent and stored.',
            'data' => $notification,
            'expo_response' => $expoResponse
        ]);
    }

    /**
     * Mark all notifications as read
     */
    public function markAsRead(Request $request)
    {
        $email = $request->input('email');
        if ($email) {
            $user = User::where('email', $email)->first();
            if ($user) {
                Notification::where(function ($q) use ($user) {
                    $q->whereNull('user_id')
                      ->orWhere('user_id', $user->id);
                })->update(['read' => true]);
            } else {
                Notification::whereNull('user_id')->update(['read' => true]);
            }
        } else {
            Notification::whereNull('user_id')->update(['read' => true]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Notifications marked as read'
        ]);
    }
}

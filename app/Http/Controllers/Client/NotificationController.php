<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Notification::where('user_id', Auth::id())
            ->latest()
            ->paginate(20);

        return view('client.notifications.index', compact('notifications'));
    }

    public function markAsRead($id)
    {
        $notify = Notification::where('id', $id)
            ->where('user_id', Auth::id())
            ->first();

        if (!$notify) {
            return response()->json([
                'success' => false,
                'message' => 'Notification not found.',
            ], 404);
        }

        if (!$notify->read_at) {
            $notify->update(['read_at' => now()]);
        }

        return response()->json([
            'success' => true,
            'read_at' => $notify->fresh()->read_at,
        ]);
    }

    public function markAll()
    {
        $updated = Notification::where('user_id', Auth::id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return response()->json([
            'success' => true,
            'updated' => $updated,
        ]);
    }

}

<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends BaseController
{
    public function markAsRead($id)
    {
        $notification = Notification::findOrFail($id);

        // Pastikan hanya pemilik notifikasi yang bisa mengubah status
        if (!Auth::check() || $notification->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Tandai sebagai "read"
        $notification->status = 'read';
        $notification->save();

        return response()->json(['success' => 'Notification marked as read.']);
    }

    
}

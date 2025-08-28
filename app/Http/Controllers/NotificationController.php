<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Notification::latest()->take(5)->get();
        $unreadCount   = Notification::where('is_read', false)->count();

        return view('notifications.index', compact('notifications', 'unreadCount'));
    }


    public function markAsRead($id)
    {
        $notification = Notification::findOrFail($id);
        $notification->update(['is_read' => true]);
        
        // Kalau notifikasi punya link, bisa arahkan ke sana
        if ($notification->url) {
            return redirect($notification->url);
        }

        return redirect()->back();
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Notification::orderBy('created_at', 'desc')->paginate(10);
        $unreadCount   = Notification::where('is_read', false)->count();

        return view('notifications.index', compact('notifications', 'unreadCount'));
    }


    public function markAsRead($id)
    {
        $notification = Notification::findOrFail($id);
        $notification->update(['is_read' => true]);

        if ($notification->url) {
            return redirect($notification->url);
        }

        return redirect()->back();
    }

    public function markAllAsRead()
    {
        Notification::where('is_read', false)->update(['is_read' => true]);

        return redirect()->route('notifications.index')
            ->with('success', 'Semua notifikasi ditandai sudah dibaca.');
    }
}

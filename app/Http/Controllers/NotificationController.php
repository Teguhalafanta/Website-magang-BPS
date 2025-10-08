<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $allNotif = Auth::user()->notifications;
        return view('notifications.index', compact('allNotif'));
    }

    public function markAsRead($id)
    {
        $notification = Auth::user()->notifications()->where('id', $id)->first();

        if ($notification) {
            $notification->markAsRead();

            // Jika URL tersedia, kembalikan URL untuk redirect via JS
            $url = $notification->data['url'] ?? null;

            if (request()->ajax()) {
                return response()->json(['success' => true, 'url' => $url]);
            }

            return $url ? redirect($url) : back();
        }

        return response()->json(['success' => false], 404);
    }

    public function markAllAsRead()
    {
        $user = Auth::user();

        if ($user) {
            $user->unreadNotifications->markAsRead();
        }

        return back()->with('success', 'Semua notifikasi sudah ditandai dibaca.');
    }
}

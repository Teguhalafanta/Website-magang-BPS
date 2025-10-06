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

            if (isset($notification->data['url']) && $notification->data['url'] !== '#') {
                return redirect($notification->data['url']);
            }
        }

        if (request()->ajax()) {
            return response()->json(['success' => true]);
        }

        return back();
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

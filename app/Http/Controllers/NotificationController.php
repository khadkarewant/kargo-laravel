<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Notification;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = auth()->user()
            ->notifications()
            ->with('serviceRequest')
            ->latest()
            ->paginate(10);

        return view('notifications.index', compact('notifications'));
    }

    public function markAsRead(Notification $notification)
{
    abort_if($notification->user_id !== auth()->id(), 403);

    if (is_null($notification->read_at)) {
        $notification->update([
            'read_at' => now(),
        ]);
    }

    return back()->with('success', 'Notification marked as read.');
}
}
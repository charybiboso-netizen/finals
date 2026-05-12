<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Notification::with('user')
            ->latest()
            ->paginate(20);
        return view('admin.notifications.index', compact('notifications'));
    }

    public function create()
    {
        $users = User::whereIn('role', ['customer', 'staff'])->active()->get();
        return view('admin.notifications.create', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => ['nullable', 'exists:users,id'],
            'type' => ['required', 'string', 'max:50'],
            'message' => ['required', 'string'],
            'channel' => ['required', 'in:email,sms,both'],
        ]);

        if ($request->user_id) {
            Notification::create([
                'user_id' => $request->user_id,
                'type' => $request->type,
                'message' => $request->message,
                'channel' => $request->channel,
            ]);
        } else {
            $users = User::active()->get();
            foreach ($users as $user) {
                Notification::create([
                    'user_id' => $user->id,
                    'type' => $request->type,
                    'message' => $request->message,
                    'channel' => $request->channel,
                ]);
            }
        }

        ActivityLog::log(auth()->id(), 'notification_sent', "Sent notification: {$request->type}");

        return redirect()->route('admin.notifications.index')->with('success', 'Notification sent successfully.');
    }

    public function show(Notification $notification)
    {
        return view('admin.notifications.show', compact('notification'));
    }

    public function destroy(Notification $notification)
    {
        $notification->delete();
        return redirect()->route('admin.notifications.index')->with('success', 'Notification deleted.');
    }
}

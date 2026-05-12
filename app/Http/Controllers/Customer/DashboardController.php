<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        $activeBookings = Booking::where('customer_id', $userId)
            ->whereIn('status', ['pending', 'confirmed', 'picked_up', 'cleaning'])
            ->with('services')
            ->latest()
            ->get();

        $completedBookings = Booking::where('customer_id', $userId)
            ->whereIn('status', ['completed', 'delivered'])
            ->count();

        $totalSpent = Booking::where('customer_id', $userId)
            ->where('status', 'delivered')
            ->sum('total_price');

        $recentBookings = Booking::where('customer_id', $userId)
            ->with('services')
            ->latest()
            ->take(5)
            ->get();

        $unreadNotifications = Notification::where('user_id', $userId)
            ->unread()
            ->latest()
            ->take(5)
            ->get();

        return view('customer.dashboard', compact(
            'activeBookings', 'completedBookings', 'totalSpent',
            'recentBookings', 'unreadNotifications'
        ));
    }
}

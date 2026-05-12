<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\StaffAssignment;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $staffId = Auth::id();

        $totalAssigned = Booking::where('staff_id', $staffId)
            ->whereIn('status', ['pending', 'confirmed', 'picked_up', 'cleaning'])
            ->count();

        $pendingPickups = Booking::where('staff_id', $staffId)
            ->whereIn('status', ['pending', 'confirmed'])
            ->count();

        $inProgress = Booking::where('staff_id', $staffId)
            ->whereIn('status', ['picked_up', 'cleaning'])
            ->count();

        $completedToday = Booking::where('staff_id', $staffId)
            ->where('status', 'delivered')
            ->whereDate('delivered_at', today())
            ->count();

        $recentBookings = Booking::where('staff_id', $staffId)
            ->with('customer')
            ->latest()
            ->take(10)
            ->get();

        $pickupSchedule = Booking::where('staff_id', $staffId)
            ->whereIn('status', ['confirmed', 'picked_up'])
            ->whereDate('pickup_date', '>=', today())
            ->orderBy('pickup_date')
            ->get();

        $deliverySchedule = Booking::where('staff_id', $staffId)
            ->whereIn('status', ['completed'])
            ->whereDate('delivery_date', '>=', today())
            ->orderBy('delivery_date')
            ->get();

        $assignments = StaffAssignment::where('staff_id', $staffId)
            ->with('booking.customer')
            ->latest()
            ->take(10)
            ->get();

        return view('staff.dashboard', compact(
            'totalAssigned', 'pendingPickups', 'inProgress', 'completedToday',
            'recentBookings', 'pickupSchedule', 'deliverySchedule', 'assignments'
        ));
    }
}

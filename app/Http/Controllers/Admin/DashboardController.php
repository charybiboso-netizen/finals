<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\User;
use App\Models\Rating;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalCustomers = User::byRole('customer')->active()->count();
        $totalStaff = User::byRole('staff')->active()->count();
        $pendingStaff = User::byRole('staff')->pending()->count();

        $totalBookings = Booking::count();
        $pendingBookings = Booking::pending()->count();
        $activeBookings = Booking::whereIn('status', ['confirmed', 'picked_up', 'cleaning'])->count();
        $completedBookings = Booking::where('status', 'delivered')->count();
        $cancelledBookings = Booking::where('status', 'cancelled')->count();

        $totalRevenue = Booking::where('status', 'delivered')->sum('total_price');
        $todayRevenue = Booking::where('status', 'delivered')
            ->whereDate('delivered_at', today())
            ->sum('total_price');

        $paidRevenue = Booking::where('is_paid', true)->sum('total_price');
        $unpaidRevenue = Booking::where('is_paid', false)->sum('total_price');
        $paidBookings = Booking::where('is_paid', true)->count();
        $unpaidBookings = Booking::where('is_paid', false)->count();

        $revenueData = Booking::where('status', 'delivered')
            ->select(DB::raw('DATE(delivered_at) as date'), DB::raw('SUM(total_price) as revenue'))
            ->whereMonth('delivered_at', now()->month)
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $recentBookings = Booking::with('customer')
            ->latest()
            ->take(10)
            ->get();

        $recentRatings = Rating::with(['customer', 'booking'])
            ->latest()
            ->take(5)
            ->get();

        $recentActivity = ActivityLog::with('user')
            ->latest()
            ->take(10)
            ->get();

        $bookingsByStatus = Booking::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get();

        $monthlyRevenue = Booking::where('status', 'delivered')
            ->select(DB::raw('MONTH(delivered_at) as month'), DB::raw('SUM(total_price) as revenue'))
            ->whereYear('delivered_at', now()->year)
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return view('admin.dashboard', compact(
            'totalCustomers', 'totalStaff', 'pendingStaff',
            'totalBookings', 'pendingBookings', 'activeBookings',
            'completedBookings', 'cancelledBookings',
            'totalRevenue', 'todayRevenue', 'revenueData',
            'paidRevenue', 'unpaidRevenue', 'paidBookings', 'unpaidBookings',
            'recentBookings', 'recentRatings', 'recentActivity',
            'bookingsByStatus', 'monthlyRevenue'
        ));
    }
}

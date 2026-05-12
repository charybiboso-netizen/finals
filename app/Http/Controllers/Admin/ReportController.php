<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        return view('admin.reports.index');
    }

    public function daily(Request $request)
    {
        $date = $request->date ? \Carbon\Carbon::parse($request->date) : today();

        $bookings = Booking::whereDate('created_at', $date)
            ->with('customer')
            ->latest()
            ->get();

        $revenue = $bookings->where('status', 'delivered')->sum('total_price');
        $totalBookings = $bookings->count();
        $completed = $bookings->where('status', 'delivered')->count();
        $cancelled = $bookings->where('status', 'cancelled')->count();

        return view('admin.reports.daily', compact(
            'date', 'bookings', 'revenue', 'totalBookings', 'completed', 'cancelled'
        ));
    }

    public function weekly(Request $request)
    {
        $weekStart = $request->week_start ? \Carbon\Carbon::parse($request->week_start)->startOfWeek() : now()->startOfWeek();
        $weekEnd = $weekStart->copy()->endOfWeek();

        $bookings = Booking::whereBetween('created_at', [$weekStart, $weekEnd])
            ->with('customer')
            ->latest()
            ->get();

        $revenue = $bookings->where('status', 'delivered')->sum('total_price');
        $totalBookings = $bookings->count();
        $completed = $bookings->where('status', 'delivered')->count();
        $cancelled = $bookings->where('status', 'cancelled')->count();

        $revenueByDay = Booking::where('status', 'delivered')
            ->whereBetween('delivered_at', [$weekStart, $weekEnd])
            ->select(DB::raw('DATE(delivered_at) as date'), DB::raw('SUM(total_price) as revenue'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return view('admin.reports.weekly', compact(
            'weekStart', 'weekEnd', 'bookings', 'revenue', 'totalBookings', 'completed', 'cancelled', 'revenueByDay'
        ));
    }

    public function monthly(Request $request)
    {
        $month = $request->month ? \Carbon\Carbon::parse($request->month) : now();

        $bookings = Booking::whereYear('created_at', $month->year)
            ->whereMonth('created_at', $month->month)
            ->with('customer')
            ->latest()
            ->get();

        $revenue = $bookings->where('status', 'delivered')->sum('total_price');
        $totalBookings = $bookings->count();
        $completed = $bookings->where('status', 'delivered')->count();
        $cancelled = $bookings->where('status', 'cancelled')->count();

        $revenueByWeek = Booking::where('status', 'delivered')
            ->whereYear('delivered_at', $month->year)
            ->whereMonth('delivered_at', $month->month)
            ->select(DB::raw('WEEK(delivered_at) as week'), DB::raw('SUM(total_price) as revenue'))
            ->groupBy('week')
            ->orderBy('week')
            ->get();

        return view('admin.reports.monthly', compact(
            'month', 'bookings', 'revenue', 'totalBookings', 'completed', 'cancelled', 'revenueByWeek'
        ));
    }

    public function transactions()
    {
        $transactions = Booking::where('is_paid', true)
            ->with('customer')
            ->latest('paid_at')
            ->paginate(15);

        return view('admin.reports.transactions', compact('transactions'));
    }
}

<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    public function index()
    {
        $bookings = Booking::where('staff_id', Auth::id())
            ->with('customer')
            ->latest()
            ->paginate(15);
        return view('staff.bookings.index', compact('bookings'));
    }

    public function show(Booking $booking)
    {
        if ($booking->staff_id !== Auth::id()) {
            abort(403);
        }
        $booking->load(['customer', 'services', 'staffAssignments.staff']);
        return view('staff.bookings.show', compact('booking'));
    }

    public function updateStatus(Request $request, Booking $booking)
    {
        if ($booking->staff_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'status' => ['required', 'in:confirmed,picked_up,cleaning,completed,delivered'],
        ]);

        $validTransitions = [
            'pending' => ['confirmed'],
            'confirmed' => ['picked_up'],
            'picked_up' => ['cleaning'],
            'cleaning' => ['completed'],
            'completed' => ['delivered'],
        ];

        $currentStatus = $booking->status;

        if (!isset($validTransitions[$currentStatus]) || !in_array($request->status, $validTransitions[$currentStatus])) {
            return back()->with('error', "Cannot transition from {$currentStatus} to {$request->status}.");
        }

        $timestamps = [
            'picked_up' => 'picked_up_at',
            'cleaning' => 'cleaning_started_at',
            'completed' => 'completed_at',
            'delivered' => 'delivered_at',
        ];

        $data = ['status' => $request->status];
        if (isset($timestamps[$request->status])) {
            $data[$timestamps[$request->status]] = now();
        }

        $booking->update($data);

        ActivityLog::log(Auth::id(), 'booking_status_updated', "Updated booking {$booking->booking_code} to {$request->status}");

        return redirect()->route('staff.bookings.show', $booking)->with('success', 'Booking status updated successfully.');
    }

    public function pickupSchedule()
    {
        $pickups = Booking::where('staff_id', Auth::id())
            ->whereIn('status', ['confirmed', 'picked_up'])
            ->whereDate('pickup_date', '>=', today())
            ->with('customer')
            ->orderBy('pickup_date')
            ->get();

        return view('staff.bookings.pickup-schedule', compact('pickups'));
    }

    public function deliverySchedule()
    {
        $deliveries = Booking::where('staff_id', Auth::id())
            ->whereIn('status', ['completed'])
            ->whereDate('delivery_date', '>=', today())
            ->with('customer')
            ->orderBy('delivery_date')
            ->get();

        return view('staff.bookings.delivery-schedule', compact('deliveries'));
    }
}

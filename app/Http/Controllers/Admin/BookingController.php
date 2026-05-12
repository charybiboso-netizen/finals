<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\User;
use App\Models\StaffAssignment;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index()
    {
        $bookings = Booking::with(['customer', 'staff', 'services'])
            ->latest()
            ->paginate(15);
        return view('admin.bookings.index', compact('bookings'));
    }

    public function show(Booking $booking)
    {
        $booking->load(['customer', 'staff', 'services', 'rating', 'staffAssignments.staff']);
        return view('admin.bookings.show', compact('booking'));
    }

    public function edit(Booking $booking)
    {
        $booking->load(['customer', 'services']);
        $staff = User::byRole('staff')->active()->get();
        return view('admin.bookings.edit', compact('booking', 'staff'));
    }

    public function update(Request $request, Booking $booking)
    {
        $request->validate([
            'status' => ['required', 'in:pending,confirmed,picked_up,cleaning,completed,delivered,received,cancelled'],
            'staff_id' => ['nullable', 'exists:users,id'],
            'total_price' => ['nullable', 'numeric', 'min:0'],
            'payment_method' => ['nullable', 'string', 'max:50'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $data = [];
        if ($request->has('status')) {
            $data['status'] = $request->status;
            $timestamps = [
                'picked_up' => 'picked_up_at',
                'cleaning' => 'cleaning_started_at',
                'completed' => 'completed_at',
                'delivered' => 'delivered_at',
                'received' => 'received_at',
                'cancelled' => 'cancelled_at',
            ];
            if (isset($timestamps[$request->status])) {
                $data[$timestamps[$request->status]] = now();
            }
        }

        if ($request->has('staff_id')) {
            $data['staff_id'] = $request->staff_id;
        }

        if ($request->has('total_price')) {
            $data['total_price'] = $request->total_price;
        }

        if ($request->has('payment_method')) {
            $data['payment_method'] = $request->payment_method;
        }

        if ($request->has('is_paid')) {
            $data['is_paid'] = $request->is_paid === '1';
            if ($request->is_paid === '1' && !$booking->paid_at) {
                $data['paid_at'] = now();
            } elseif ($request->is_paid === '0') {
                $data['paid_at'] = null;
            }
        }

        if ($request->has('notes')) {
            $data['notes'] = $request->notes;
        }

        $booking->update($data);

        if ($request->has('staff_id') && $request->staff_id) {
            StaffAssignment::updateOrCreate(
                ['booking_id' => $booking->id, 'role' => 'cleaner'],
                ['staff_id' => $request->staff_id]
            );
        }

        ActivityLog::log(auth()->id(), 'booking_updated', "Updated booking {$booking->booking_code}");

        return redirect()->route('admin.bookings.index')->with('success', 'Booking updated successfully.');
    }

    public function cancel(Request $request, Booking $booking)
    {
        $request->validate([
            'cancellation_reason' => ['required', 'string', 'max:500'],
        ]);

        $booking->update([
            'status' => 'cancelled',
            'cancelled_at' => now(),
            'cancellation_reason' => $request->cancellation_reason,
        ]);

        ActivityLog::log(auth()->id(), 'booking_cancelled', "Cancelled booking {$booking->booking_code}");

        return redirect()->route('admin.bookings.index')->with('success', 'Booking cancelled successfully.');
    }

    public function markAsPaid(Booking $booking)
    {
        $booking->update([
            'is_paid' => true,
            'paid_at' => now(),
        ]);

        ActivityLog::log(auth()->id(), 'payment_marked', "Marked booking {$booking->booking_code} as paid");

        return redirect()->route('admin.bookings.show', $booking)->with('success', 'Booking marked as paid.');
    }

    public function markAsUnpaid(Booking $booking)
    {
        $booking->update([
            'is_paid' => false,
            'paid_at' => null,
        ]);

        ActivityLog::log(auth()->id(), 'payment_unmarked', "Marked booking {$booking->booking_code} as unpaid");

        return redirect()->route('admin.bookings.show', $booking)->with('success', 'Booking marked as unpaid.');
    }

    public function assignStaff(Request $request, Booking $booking)
    {
        $request->validate([
            'staff_id' => ['required', 'exists:users,id'],
            'role' => ['required', 'in:picker,cleaner,delivery'],
        ]);

        StaffAssignment::updateOrCreate(
            ['booking_id' => $booking->id, 'role' => $request->role],
            ['staff_id' => $request->staff_id]
        );

        $booking->update(['staff_id' => $request->staff_id]);

        ActivityLog::log(auth()->id(), 'staff_assigned', "Assigned staff to booking {$booking->booking_code}");

        return redirect()->route('admin.bookings.show', $booking)->with('success', 'Staff assigned successfully.');
    }
}

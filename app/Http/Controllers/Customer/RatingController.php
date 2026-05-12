<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Rating;
use App\Models\Booking;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RatingController extends Controller
{
    public function create(Booking $booking)
    {
        if ($booking->customer_id !== Auth::id()) {
            abort(403);
        }

        if (!in_array($booking->status, ['completed', 'delivered'])) {
            return back()->with('error', 'You can only rate completed bookings.');
        }

        if ($booking->rating) {
            return redirect()->route('customer.bookings.show', $booking)
                ->with('error', 'You have already rated this booking.');
        }

        return view('customer.ratings.create', compact('booking'));
    }

    public function store(Request $request, Booking $booking)
    {
        if ($booking->customer_id !== Auth::id()) {
            abort(403);
        }

        if (!in_array($booking->status, ['completed', 'delivered'])) {
            return back()->with('error', 'You can only rate completed bookings.');
        }

        $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'review' => ['nullable', 'string', 'max:1000'],
        ]);

        Rating::create([
            'booking_id' => $booking->id,
            'customer_id' => Auth::id(),
            'rating' => $request->rating,
            'review' => $request->review,
            'is_approved' => false,
        ]);

        ActivityLog::log(Auth::id(), 'rating_submitted', "Submitted rating for booking {$booking->booking_code}");

        return redirect()->route('customer.bookings.show', $booking)
            ->with('success', 'Thank you for your rating!');
    }
}

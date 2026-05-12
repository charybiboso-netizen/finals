<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Service;
use App\Models\Notification;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class BookingController extends Controller
{
    public function create()
    {
        $services = Service::active()->get();
        return view('customer.bookings.create', compact('services'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'service_type' => ['required', 'in:wash,dry,fold,iron,wash_dry_fold,wash_iron,full_service'],
            'pickup_address' => ['required', 'string', 'max:500'],
            'delivery_address' => ['required', 'string', 'max:500'],
            'pickup_date' => ['required', 'date', 'after:now'],
            'delivery_date' => ['required', 'date', 'after:pickup_date'],
            'notes' => ['nullable', 'string', 'max:1000'],
            'services' => ['required', 'array'],
            'services.*.selected' => ['sometimes', 'in:1'],
            'services.*.quantity' => ['required_with:services.*.selected', 'integer', 'min:1'],
        ]);

        $totalPrice = 0;
        $serviceData = [];

        foreach ($request->services as $serviceId => $svc) {
            if (!isset($svc['selected'])) {
                continue;
            }
            $service = Service::findOrFail($serviceId);
            $quantity = $svc['quantity'] ?? 1;
            $price = $service->price * $quantity;
            $totalPrice += $price;
            $serviceData[$service->id] = [
                'quantity' => $quantity,
                'price' => $price,
            ];
        }

        $booking = Booking::create([
            'booking_code' => 'CLN' . strtoupper(Str::random(8)),
            'customer_id' => Auth::id(),
            'service_type' => $request->service_type,
            'status' => 'pending',
            'total_price' => $totalPrice,
            'pickup_address' => $request->pickup_address,
            'delivery_address' => $request->delivery_address,
            'pickup_date' => $request->pickup_date,
            'delivery_date' => $request->delivery_date,
            'notes' => $request->notes,
        ]);

        $booking->services()->attach($serviceData);

        Notification::create([
            'user_id' => Auth::id(),
            'type' => 'booking_confirmation',
            'message' => "Booking {$booking->booking_code} has been created successfully.",
            'channel' => 'email',
        ]);

        ActivityLog::log(Auth::id(), 'booking_created', "Created booking {$booking->booking_code}");

        return redirect()->route('customer.bookings.show', $booking)
            ->with('success', 'Booking created successfully!');
    }

    public function index()
    {
        $bookings = Booking::where('customer_id', Auth::id())
            ->with('services')
            ->latest()
            ->paginate(10);
        return view('customer.bookings.index', compact('bookings'));
    }

    public function show(Booking $booking)
    {
        if ($booking->customer_id !== Auth::id()) {
            abort(403);
        }
        $booking->load(['services', 'staff', 'rating']);
        return view('customer.bookings.show', compact('booking'));
    }

    public function cancel(Request $request, Booking $booking)
    {
        if ($booking->customer_id !== Auth::id()) {
            abort(403);
        }

        if (!$booking->canBeCancelled()) {
            return back()->with('error', 'This booking cannot be cancelled.');
        }

        $request->validate([
            'reason' => ['nullable', 'string', 'max:500'],
        ]);

        $booking->update([
            'status' => 'cancelled',
            'cancelled_at' => now(),
            'cancellation_reason' => $request->reason,
        ]);

        Notification::create([
            'user_id' => Auth::id(),
            'type' => 'booking_cancelled',
            'message' => "Booking {$booking->booking_code} has been cancelled.",
            'channel' => 'email',
        ]);

        ActivityLog::log(Auth::id(), 'booking_cancelled', "Cancelled booking {$booking->booking_code}");

        return redirect()->route('customer.bookings.show', $booking)
            ->with('success', 'Booking cancelled successfully.');
    }

    public function receive(Booking $booking)
    {
        if ($booking->customer_id !== Auth::id()) {
            abort(403);
        }

        if ($booking->status !== 'delivered') {
            return back()->with('error', 'Only delivered bookings can be marked as received.');
        }

        $booking->updateStatus('received');

        Notification::create([
            'user_id' => Auth::id(),
            'type' => 'booking_received',
            'message' => "You have confirmed receipt of booking {$booking->booking_code}.",
            'channel' => 'email',
        ]);

        ActivityLog::log(Auth::id(), 'booking_received', "Confirmed receipt of booking {$booking->booking_code}");

        return redirect()->route('customer.bookings.show', $booking)
            ->with('success', 'Booking marked as received. Thank you!');
    }

    public function history()
    {
        $bookings = Booking::where('customer_id', Auth::id())
            ->whereIn('status', ['completed', 'delivered', 'received', 'cancelled'])
            ->with('services')
            ->latest()
            ->paginate(10);
        return view('customer.bookings.history', compact('bookings'));
    }

    public function track(Booking $booking)
    {
        if ($booking->customer_id !== Auth::id()) {
            abort(403);
        }
        $booking->load('services');
        return view('customer.bookings.track', compact('booking'));
    }
}

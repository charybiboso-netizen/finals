@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Booking #{{ $booking->booking_code }}
    </h2>
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">{{ session('error') }}</div>
        @endif

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Booking Details</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <span class="text-sm text-gray-500">Booking Code</span>
                        <p class="font-mono text-gray-800 font-medium">{{ $booking->booking_code }}</p>
                    </div>
                    <div>
                        <span class="text-sm text-gray-500">Status</span>
                        <p>
                            @php
                                $colors = [
                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                    'confirmed' => 'bg-blue-100 text-blue-800',
                                    'picked_up' => 'bg-indigo-100 text-indigo-800',
                                    'cleaning' => 'bg-purple-100 text-purple-800',
                                    'completed' => 'bg-green-100 text-green-800',
                                    'delivered' => 'bg-gray-100 text-gray-800',
                                    'received' => 'bg-teal-100 text-teal-800',
                                    'cancelled' => 'bg-red-100 text-red-800',
                                ];
                            @endphp
                            <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $colors[$booking->status] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ ucfirst(str_replace('_', ' ', $booking->status)) }}
                            </span>
                        </p>
                    </div>
                    <div>
                        <span class="text-sm text-gray-500">Service Type</span>
                        <p class="text-gray-800 font-medium">{{ ucfirst(str_replace('_', ' ', $booking->service_type)) }}</p>
                    </div>
                    <div>
                        <span class="text-sm text-gray-500">Total Price</span>
                        <p class="text-gray-800 font-medium">₱{{ number_format($booking->total_price, 2) }}</p>
                    </div>
                    <div>
                        <span class="text-sm text-gray-500">Weight</span>
                        <p class="text-gray-800 font-medium">{{ $booking->total_weight ? $booking->total_weight . ' kg' : 'N/A' }}</p>
                    </div>
                    <div>
                        <span class="text-sm text-gray-500">Payment</span>
                        <p class="text-gray-800 font-medium">
                            @if($booking->is_paid)
                                <span class="text-green-600">Paid</span>
                            @else
                                <span class="text-red-600">Unpaid</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Services Breakdown</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Service</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Quantity</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Price</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($booking->services as $service)
                                <tr>
                                    <td class="px-4 py-3 text-sm text-gray-800">{{ $service->name }}</td>
                                    <td class="px-4 py-3 text-sm">{{ $service->pivot->quantity }}</td>
                                    <td class="px-4 py-3 text-sm">₱{{ number_format($service->pivot->price, 2) }}</td>
                                    <td class="px-4 py-3 text-sm">₱{{ number_format($service->pivot->quantity * $service->pivot->price, 2) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-3 text-sm text-gray-500 text-center">No services listed.</td>
                                </tr>
                            @endforelse
                        </tbody>
                        <tfoot class="bg-gray-50">
                            <tr>
                                <td colspan="3" class="px-4 py-3 text-sm font-semibold text-right text-gray-800">Total</td>
                                <td class="px-4 py-3 text-sm font-bold">₱{{ number_format($booking->total_price, 2) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Pickup Details</h3>
                    <p class="text-sm text-gray-600"><strong>Address:</strong> {{ $booking->pickup_address ?? 'N/A' }}</p>
                    <p class="text-sm text-gray-600"><strong>Date:</strong> {{ $booking->pickup_date ? $booking->pickup_date->format('M d, Y h:i A') : 'N/A' }}</p>
                </div>
            </div>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Delivery Details</h3>
                    <p class="text-sm text-gray-600"><strong>Address:</strong> {{ $booking->delivery_address ?? 'N/A' }}</p>
                    <p class="text-sm text-gray-600"><strong>Date:</strong> {{ $booking->delivery_date ? $booking->delivery_date->format('M d, Y h:i A') : 'N/A' }}</p>
                </div>
            </div>
        </div>

        @if($booking->notes)
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Notes</h3>
                <p class="text-gray-600">{{ $booking->notes }}</p>
            </div>
        </div>
        @endif

        <div class="flex flex-wrap items-center gap-4">
            <a href="{{ route('customer.bookings.index') }}" class="text-indigo-600 hover:text-indigo-800">&larr; Back to Bookings</a>

            @if(in_array($booking->status, ['pending', 'confirmed', 'picked_up', 'cleaning', 'completed']))
                <a href="{{ route('customer.bookings.track', $booking) }}" class="bg-indigo-600 text-white py-2 px-4 rounded-md hover:bg-indigo-700 font-medium">Track</a>
            @endif

            @if($booking->status === 'delivered')
                <form action="{{ route('customer.bookings.receive', $booking) }}" method="POST" class="inline" onsubmit="return confirm('Confirm that you have received your laundry?')">
                    @csrf
                    <button type="submit" class="bg-teal-600 text-white py-2 px-4 rounded-md hover:bg-teal-700 font-medium">Mark as Received</button>
                </form>
            @endif

            @if(in_array($booking->status, ['completed', 'delivered', 'received']) && !$booking->rating)
                <a href="{{ route('customer.ratings.create', $booking) }}" class="bg-yellow-500 text-white py-2 px-4 rounded-md hover:bg-yellow-600 font-medium">Rate This Booking</a>
            @endif

            @if($booking->canBeCancelled())
                <div x-data="{ showCancel: false }" class="inline-block">
                    <button @click="showCancel = !showCancel" id="cancel" class="bg-red-600 text-white py-2 px-4 rounded-md hover:bg-red-700 font-medium">Cancel Booking</button>
                    <div x-show="showCancel" class="mt-3 p-4 border border-red-200 rounded-lg bg-red-50">
                        <form action="{{ route('customer.bookings.cancel', $booking) }}" method="POST">
                            @csrf
                            <label class="block text-sm font-medium text-red-800 mb-1">Reason for Cancellation</label>
                            <textarea name="cancellation_reason" rows="2" class="w-full rounded-md border-red-300 shadow-sm focus:border-red-500 focus:ring-red-500" placeholder="Please provide a reason..."></textarea>
                            <div class="mt-2 flex justify-end">
                                <button type="submit" class="bg-red-600 text-white py-1.5 px-4 rounded-md hover:bg-red-700 text-sm font-medium" onclick="return confirm('Are you sure you want to cancel this booking?')">Confirm Cancel</button>
                            </div>
                        </form>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
@endpush

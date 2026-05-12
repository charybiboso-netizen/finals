@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Booking History') }}
    </h2>
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">{{ session('error') }}</div>
        @endif

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Booking Code</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Service Type</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total Price</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Pickup Date</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Delivery Date</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($bookings as $booking)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 text-sm font-mono">{{ $booking->booking_code }}</td>
                                    <td class="px-4 py-3 text-sm">
                                        @php
                                            $colors = [
                                                'completed' => 'bg-green-100 text-green-800',
                                                'delivered' => 'bg-gray-100 text-gray-800',
                                                'cancelled' => 'bg-red-100 text-red-800',
                                            ];
                                            $badgeColor = $colors[$booking->status] ?? 'bg-gray-100 text-gray-800';
                                        @endphp
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $badgeColor }}">
                                            {{ ucfirst(str_replace('_', ' ', $booking->status)) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-sm">{{ ucfirst(str_replace('_', ' ', $booking->service_type)) }}</td>
                                    <td class="px-4 py-3 text-sm font-medium">₱{{ number_format($booking->total_price, 2) }}</td>
                                    <td class="px-4 py-3 text-sm">{{ $booking->pickup_date ? $booking->pickup_date->format('M d, Y') : 'N/A' }}</td>
                                    <td class="px-4 py-3 text-sm">{{ $booking->delivery_date ? $booking->delivery_date->format('M d, Y') : 'N/A' }}</td>
                                    <td class="px-4 py-3 text-sm">
                                        <a href="{{ route('customer.bookings.show', $booking) }}" class="text-blue-600 hover:text-blue-900 font-medium">View</a>
                                        @if(in_array($booking->status, ['completed', 'delivered']) && !$booking->rating)
                                            <a href="{{ route('customer.ratings.create', $booking) }}" class="ml-2 text-yellow-600 hover:text-yellow-900 font-medium">Rate</a>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-4 py-6 text-center text-gray-500">No booking history found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $bookings->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

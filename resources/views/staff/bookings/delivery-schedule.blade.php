@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Delivery Schedule') }}
    </h2>
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">{{ session('error') }}</div>
        @endif

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                @if(isset($deliveries) && $deliveries->count() > 0)
                    @php
                        $grouped = $deliveries->groupBy(function($item) {
                            return $item->delivery_date ? $item->delivery_date->format('Y-m-d') : 'No Date';
                        });
                    @endphp

                    @foreach($grouped as $date => $dateDeliveries)
                        <div class="mb-8 last:mb-0">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b border-gray-200 pb-2">
                                {{ \Carbon\Carbon::parse($date)->format('l, F d, Y') }}
                            </h3>
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Booking</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Customer</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Delivery Address</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Delivery Time</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200">
                                        @foreach($dateDeliveries as $delivery)
                                            <tr class="hover:bg-gray-50">
                                                <td class="px-4 py-3 text-sm font-mono">{{ $delivery->booking_code }}</td>
                                                <td class="px-4 py-3 text-sm">{{ $delivery->customer->name ?? 'N/A' }}</td>
                                                <td class="px-4 py-3 text-sm max-w-xs truncate">{{ $delivery->delivery_address ?? 'N/A' }}</td>
                                                <td class="px-4 py-3 text-sm">{{ $delivery->delivery_date ? $delivery->delivery_date->format('h:i A') : 'N/A' }}</td>
                                                <td class="px-4 py-3 text-sm">
                                                    @php
                                                        $colors = [
                                                            'pending' => 'bg-yellow-100 text-yellow-800',
                                                            'confirmed' => 'bg-blue-100 text-blue-800',
                                                            'picked_up' => 'bg-indigo-100 text-indigo-800',
                                                            'cleaning' => 'bg-purple-100 text-purple-800',
                                                            'completed' => 'bg-green-100 text-green-800',
                                                            'delivered' => 'bg-gray-100 text-gray-800',
                                                            'cancelled' => 'bg-red-100 text-red-800',
                                                        ];
                                                    @endphp
                                                    <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $colors[$delivery->status] ?? 'bg-gray-100 text-gray-800' }}">
                                                        {{ ucfirst(str_replace('_', ' ', $delivery->status)) }}
                                                    </span>
                                                </td>
                                                <td class="px-4 py-3 text-sm">
                                                    <a href="{{ route('staff.bookings.show', $delivery) }}" class="text-blue-600 hover:text-blue-900 font-medium">View</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="text-center py-8">
                        <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <p class="text-gray-500">No delivery schedules available.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

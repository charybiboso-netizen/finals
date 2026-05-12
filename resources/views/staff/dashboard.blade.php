@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Staff Dashboard') }}
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

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="text-sm text-gray-500 font-medium">Total Assigned Tasks</div>
                    <div class="text-3xl font-bold mt-1">{{ $totalAssigned }}</div>
                </div>
            </div>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="text-sm text-gray-500 font-medium">Pending Pickups</div>
                    <div class="text-3xl font-bold mt-1 text-yellow-600">{{ $pendingPickups }}</div>
                </div>
            </div>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="text-sm text-gray-500 font-medium">In Progress</div>
                    <div class="text-3xl font-bold mt-1 text-blue-600">{{ $inProgress }}</div>
                </div>
            </div>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="text-sm text-gray-500 font-medium">Completed Today</div>
                    <div class="text-3xl font-bold mt-1 text-green-600">{{ $completedToday }}</div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
            <div class="p-6">
                <h3 class="font-semibold text-lg text-gray-800 mb-4">Recent Bookings</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Booking Code</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Customer</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Pickup Date</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Delivery Date</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($recentBookings as $booking)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 text-sm font-mono">{{ $booking->booking_code }}</td>
                                    <td class="px-4 py-3 text-sm">{{ $booking->customer->name ?? 'N/A' }}</td>
                                    <td class="px-4 py-3 text-sm">
                                        @php
                                            $statusColors = [
                                                'pending' => 'bg-yellow-100 text-yellow-800',
                                                'confirmed' => 'bg-blue-100 text-blue-800',
                                                'picked_up' => 'bg-indigo-100 text-indigo-800',
                                                'cleaning' => 'bg-purple-100 text-purple-800',
                                                'completed' => 'bg-green-100 text-green-800',
                                                'delivered' => 'bg-gray-100 text-gray-800',
                                                'received' => 'bg-teal-100 text-teal-800',
                                                'cancelled' => 'bg-red-100 text-red-800',
                                            ];
                                            $color = $statusColors[$booking->status] ?? 'bg-gray-100 text-gray-800';
                                        @endphp
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $color }}">
                                            {{ ucfirst(str_replace('_', ' ', $booking->status)) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-sm">{{ $booking->pickup_date ? $booking->pickup_date->format('M d, Y') : 'N/A' }}</td>
                                    <td class="px-4 py-3 text-sm">{{ $booking->delivery_date ? $booking->delivery_date->format('M d, Y') : 'N/A' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-6 text-center text-gray-500">No recent bookings found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="font-semibold text-lg text-gray-800 mb-4">Pickup Schedule</h3>
                    @forelse($pickupSchedule as $pickup)
                        <div class="border-b border-gray-100 py-3 last:border-0">
                            <div class="flex items-center justify-between">
                                <div>
                                    <span class="font-medium text-gray-800">{{ $pickup->customer->name ?? 'N/A' }}</span>
                                    <span class="text-sm text-gray-500 ml-2">
                                        {{ $pickup->pickup_date ? $pickup->pickup_date->format('M d, Y h:i A') : 'N/A' }}
                                    </span>
                                </div>
                                @php
                                    $statusColors = [
                                        'pending' => 'bg-yellow-100 text-yellow-800',
                                        'confirmed' => 'bg-blue-100 text-blue-800',
                                        'picked_up' => 'bg-indigo-100 text-indigo-800',
                                        'cleaning' => 'bg-purple-100 text-purple-800',
                                        'completed' => 'bg-green-100 text-green-800',
                                        'delivered' => 'bg-gray-100 text-gray-800',
                                        'received' => 'bg-teal-100 text-teal-800',
                                        'cancelled' => 'bg-red-100 text-red-800',
                                    ];
                                    $color = $statusColors[$pickup->status] ?? 'bg-gray-100 text-gray-800';
                                @endphp
                                <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $color }}">
                                    {{ ucfirst(str_replace('_', ' ', $pickup->status)) }}
                                </span>
                            </div>
                            <div class="text-sm text-gray-500 mt-1">{{ $pickup->pickup_address ?? 'N/A' }}</div>
                        </div>
                    @empty
                        <p class="text-gray-500 text-center py-4">No upcoming pickups.</p>
                    @endforelse
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="font-semibold text-lg text-gray-800 mb-4">Delivery Schedule</h3>
                    @forelse($deliverySchedule as $delivery)
                        <div class="border-b border-gray-100 py-3 last:border-0">
                            <div class="flex items-center justify-between">
                                <div>
                                    <span class="font-medium text-gray-800">{{ $delivery->customer->name ?? 'N/A' }}</span>
                                    <span class="text-sm text-gray-500 ml-2">
                                        {{ $delivery->delivery_date ? $delivery->delivery_date->format('M d, Y h:i A') : 'N/A' }}
                                    </span>
                                </div>
                                @php
                                    $statusColors = [
                                        'pending' => 'bg-yellow-100 text-yellow-800',
                                        'confirmed' => 'bg-blue-100 text-blue-800',
                                        'picked_up' => 'bg-indigo-100 text-indigo-800',
                                        'cleaning' => 'bg-purple-100 text-purple-800',
                                        'completed' => 'bg-green-100 text-green-800',
                                        'delivered' => 'bg-gray-100 text-gray-800',
                                        'received' => 'bg-teal-100 text-teal-800',
                                        'cancelled' => 'bg-red-100 text-red-800',
                                    ];
                                    $color = $statusColors[$delivery->status] ?? 'bg-gray-100 text-gray-800';
                                @endphp
                                <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $color }}">
                                    {{ ucfirst(str_replace('_', ' ', $delivery->status)) }}
                                </span>
                            </div>
                            <div class="text-sm text-gray-500 mt-1">{{ $delivery->delivery_address ?? 'N/A' }}</div>
                        </div>
                    @empty
                        <p class="text-gray-500 text-center py-4">No upcoming deliveries.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

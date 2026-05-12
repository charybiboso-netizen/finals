@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Booking #{{ $booking->booking_code }}
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

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Booking Information</h3>
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
                                <span class="text-sm text-gray-500">Total Weight</span>
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

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Customer Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <span class="text-sm text-gray-500">Name</span>
                                <p class="text-gray-800 font-medium">{{ $booking->customer->name ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <span class="text-sm text-gray-500">Email</span>
                                <p class="text-gray-800 font-medium">{{ $booking->customer->email ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <span class="text-sm text-gray-500">Phone</span>
                                <p class="text-gray-800 font-medium">{{ $booking->customer->phone ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <span class="text-sm text-gray-500">Address</span>
                                <p class="text-gray-800 font-medium">{{ $booking->customer->address ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Services</h3>
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
                                            <td colspan="4" class="px-4 py-3 text-sm text-gray-500 text-center">No services added.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                                <tfoot class="bg-gray-50">
                                    <tr>
                                        <td colspan="3" class="px-4 py-3 text-sm font-semibold text-right text-gray-800">Total</td>
                                        <td class="px-4 py-3 text-sm font-bold text-gray-800">₱{{ number_format($booking->total_price, 2) }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Pickup & Delivery</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="bg-blue-50 p-4 rounded-lg">
                                <h4 class="font-medium text-blue-800 mb-2">Pickup</h4>
                                <p class="text-sm text-gray-600 mb-1"><strong>Address:</strong> {{ $booking->pickup_address ?? 'N/A' }}</p>
                                <p class="text-sm text-gray-600"><strong>Date:</strong> {{ $booking->pickup_date ? $booking->pickup_date->format('M d, Y h:i A') : 'N/A' }}</p>
                            </div>
                            <div class="bg-green-50 p-4 rounded-lg">
                                <h4 class="font-medium text-green-800 mb-2">Delivery</h4>
                                <p class="text-sm text-gray-600 mb-1"><strong>Address:</strong> {{ $booking->delivery_address ?? 'N/A' }}</p>
                                <p class="text-sm text-gray-600"><strong>Date:</strong> {{ $booking->delivery_date ? $booking->delivery_date->format('M d, Y h:i A') : 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                @if($booking->notes)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">Notes</h3>
                        <p class="text-gray-600">{{ $booking->notes }}</p>
                    </div>
                </div>
                @endif
            </div>

            <div class="space-y-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Status Timeline</h3>
                        @php
                            $allStatuses = ['pending', 'confirmed', 'picked_up', 'cleaning', 'completed', 'delivered', 'received'];
                            $currentIndex = array_search($booking->status, $allStatuses);
                            $timestamps = [
                                'pending' => $booking->created_at,
                                'confirmed' => $booking->confirmed_at ?? null,
                                'picked_up' => $booking->picked_up_at,
                                'cleaning' => $booking->cleaning_started_at,
                                'completed' => $booking->completed_at,
                                'delivered' => $booking->delivered_at,
                                'received' => $booking->received_at,
                            ];
                        @endphp
                        <div class="space-y-4">
                            @foreach($allStatuses as $index => $status)
                                @php
                                    $passed = $index <= $currentIndex;
                                    $current = $index === $currentIndex;
                                    $ts = $timestamps[$status] ?? null;
                                @endphp
                                <div class="flex items-start">
                                    <div class="flex flex-col items-center mr-4">
                                        @if($passed && !$current)
                                            <div class="w-8 h-8 rounded-full bg-green-500 flex items-center justify-center">
                                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                            </div>
                                        @elseif($current)
                                            <div class="w-8 h-8 rounded-full bg-blue-500 flex items-center justify-center">
                                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                            </div>
                                        @else
                                            <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center">
                                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                            </div>
                                        @endif
                                        @if(!$loop->last)
                                            <div class="w-0.5 h-8 {{ $index < $currentIndex ? 'bg-green-500' : 'bg-gray-200' }}"></div>
                                        @endif
                                    </div>
                                    <div class="flex-1 {{ $passed && !$current ? 'text-green-700' : ($current ? 'text-blue-700 font-medium' : 'text-gray-400') }}">
                                        <p>{{ ucfirst(str_replace('_', ' ', $status)) }}</p>
                                        @if($ts)
                                            <p class="text-xs {{ $passed && !$current ? 'text-green-500' : ($current ? 'text-blue-500' : 'text-gray-400') }}">
                                                {{ $ts instanceof \Carbon\Carbon ? $ts->format('M d, Y h:i A') : '' }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                @if(!in_array($booking->status, ['delivered', 'received', 'cancelled']))
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Update Status</h3>
                        <form action="{{ route('staff.bookings.update-status', $booking) }}" method="POST">
                            @csrf
                            @php
                                $validTransitions = [
                                    'pending' => ['confirmed'],
                                    'confirmed' => ['picked_up'],
                                    'picked_up' => ['cleaning'],
                                    'cleaning' => ['completed'],
                                    'completed' => ['delivered'],
                                ];
                                $nextStatuses = $validTransitions[$booking->status] ?? [];
                            @endphp
                            @if(count($nextStatuses) > 0)
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Next Status</label>
                                    <select name="status" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        @foreach($nextStatuses as $next)
                                            <option value="{{ $next }}">{{ ucfirst(str_replace('_', ' ', $next)) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <button type="submit" class="w-full bg-indigo-600 text-white py-2 px-4 rounded-md hover:bg-indigo-700 font-medium">
                                    Update Status
                                </button>
                            @else
                                <p class="text-gray-500 text-sm">No further status updates available.</p>
                            @endif
                        </form>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <div class="mt-6">
            <a href="{{ route('staff.bookings.index') }}" class="text-indigo-600 hover:text-indigo-800">&larr; Back to Bookings</a>
        </div>
    </div>
</div>
@endsection

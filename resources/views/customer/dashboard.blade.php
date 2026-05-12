@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('My Dashboard') }}
    </h2>
@endsection

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
                    {{ session('error') }}
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-gray-500">Active Bookings</p>
                            <p class="text-2xl font-semibold text-gray-800">{{ $activeBookings ? $activeBookings->count() : 0 }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-green-100 text-green-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-gray-500">Completed Bookings</p>
                            <p class="text-2xl font-semibold text-gray-800">{{ $completedBookings ?? 0 }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-gray-500">Total Spent</p>
                            <p class="text-2xl font-semibold text-gray-800">₱{{ number_format($totalSpent ?? 0, 2) }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-gray-500">Unread Notifications</p>
                            <p class="text-2xl font-semibold text-gray-800">{{ $unreadNotifications ? $unreadNotifications->count() : 0 }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-800">Active Bookings</h3>
                    </div>
                    <div class="p-6">
                        @if(isset($activeBookings) && $activeBookings->count() > 0)
                            <div class="space-y-4">
                                @foreach($activeBookings as $booking)
                                    <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <p class="text-sm text-gray-500">Booking #{{ $booking->booking_code }}</p>
                                                <p class="font-semibold text-gray-800">{{ ucfirst(str_replace('_', ' ', $booking->service_type ?? 'N/A')) }}</p>
                                                <p class="text-sm text-gray-600 mt-1">
                                                    Pickup: {{ $booking->pickup_date ? \Carbon\Carbon::parse($booking->pickup_date)->format('M d, Y h:i A') : 'N/A' }}
                                                </p>
                                            </div>
                                            @php
                                                $statusColors = [
                                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                                    'confirmed' => 'bg-blue-100 text-blue-800',
                                                    'picked_up' => 'bg-indigo-100 text-indigo-800',
                                                    'processing' => 'bg-purple-100 text-purple-800',
                                                    'cleaning' => 'bg-blue-100 text-blue-800',
                                                    'washing' => 'bg-cyan-100 text-cyan-800',
                                                    'drying' => 'bg-orange-100 text-orange-800',
                                                    'folding' => 'bg-teal-100 text-teal-800',
                                                    'ironing' => 'bg-pink-100 text-pink-800',
                                                    'quality_check' => 'bg-amber-100 text-amber-800',
                                                    'ready_for_delivery' => 'bg-green-100 text-green-800',
                                                    'out_for_delivery' => 'bg-emerald-100 text-emerald-800',
                                                    'delivered' => 'bg-green-100 text-green-800',
                                                    'completed' => 'bg-green-100 text-green-800',
                                                    'cancelled' => 'bg-red-100 text-red-800',
                                                ];
                                                $badgeColor = $statusColors[$booking->status] ?? 'bg-gray-100 text-gray-800';
                                            @endphp
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $badgeColor }}">
                                                {{ ucfirst(str_replace('_', ' ', $booking->status)) }}
                                            </span>
                                        </div>
                                        <div class="mt-3 flex justify-between items-center">
                                            <span class="text-lg font-bold text-gray-800">₱{{ number_format($booking->total_price ?? 0, 2) }}</span>
                                            <a href="{{ route('customer.bookings.show', $booking) }}" class="text-sm text-indigo-600 hover:text-indigo-800">View Details</a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500 text-center py-4">No active bookings.</p>
                            <div class="text-center">
                                <a href="{{ route('customer.bookings.create') }}" class="inline-block bg-indigo-600 text-white px-6 py-2 rounded-md hover:bg-indigo-700 transition">Book Now</a>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-800">Recent Bookings</h3>
                    </div>
                    <div class="p-6">
                        @if(isset($recentBookings) && $recentBookings->count() > 0)
                            <div class="space-y-3">
                                @foreach($recentBookings as $booking)
                                    <div class="flex justify-between items-center py-2 border-b border-gray-100 last:border-0">
                                        <div>
                                            <p class="font-medium text-gray-800">{{ ucfirst(str_replace('_', ' ', $booking->service_type)) }}</p>
                                            <p class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($booking->created_at)->format('M d, Y') }}</p>
                                        </div>
                                        <div class="text-right">
                                            <span class="text-sm font-semibold text-gray-800">₱{{ number_format($booking->total_price ?? 0, 2) }}</span>
                                            <br>
                                            @php
                                                $statusColors = [
                                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                                    'confirmed' => 'bg-blue-100 text-blue-800',
                                                    'picked_up' => 'bg-indigo-100 text-indigo-800',
                                                    'processing' => 'bg-purple-100 text-purple-800',
                                                    'cleaning' => 'bg-blue-100 text-blue-800',
                                                    'washing' => 'bg-cyan-100 text-cyan-800',
                                                    'drying' => 'bg-orange-100 text-orange-800',
                                                    'folding' => 'bg-teal-100 text-teal-800',
                                                    'ironing' => 'bg-pink-100 text-pink-800',
                                                    'quality_check' => 'bg-amber-100 text-amber-800',
                                                    'ready_for_delivery' => 'bg-green-100 text-green-800',
                                                    'out_for_delivery' => 'bg-emerald-100 text-emerald-800',
                                                    'delivered' => 'bg-green-100 text-green-800',
                                                    'completed' => 'bg-green-100 text-green-800',
                                                    'cancelled' => 'bg-red-100 text-red-800',
                                                ];
                                                $badgeColor = $statusColors[$booking->status] ?? 'bg-gray-100 text-gray-800';
                                            @endphp
                                            <span class="px-2 py-0.5 text-xs font-semibold rounded-full {{ $badgeColor }}">
                                                {{ ucfirst(str_replace('_', ' ', $booking->status)) }}
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="mt-4 text-center">
                                <a href="{{ route('customer.bookings.index') }}" class="text-sm text-indigo-600 hover:text-indigo-800">View All Bookings</a>
                            </div>
                        @else
                            <p class="text-gray-500 text-center py-4">No bookings yet.</p>
                            <div class="text-center">
                                <a href="{{ route('customer.bookings.create') }}" class="inline-block bg-indigo-600 text-white px-6 py-2 rounded-md hover:bg-indigo-700 transition">Create Your First Booking</a>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="lg:col-span-2 bg-white rounded-lg shadow overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-800">Unread Notifications</h3>
                    </div>
                    <div class="p-6">
                        @if(isset($unreadNotifications) && $unreadNotifications->count() > 0)
                            <div class="space-y-3">
                                @foreach($unreadNotifications as $notification)
                                    <div class="flex items-start p-3 bg-indigo-50 rounded-lg">
                                        <div class="flex-shrink-0 mt-1">
                                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                                            </svg>
                                        </div>
                                        <div class="ml-3 flex-1">
                                            <p class="text-sm font-medium text-gray-800">{{ $notification->type ?? 'Notification' }}</p>
                                            <p class="text-sm text-gray-600">{{ $notification->message ?? '' }}</p>
                                            <p class="text-xs text-gray-400 mt-1">{{ $notification->created_at ? \Carbon\Carbon::parse($notification->created_at)->diffForHumans() : '' }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="mt-4 text-center">
                                <a href="{{ route('customer.notifications.index') }}" class="text-sm text-indigo-600 hover:text-indigo-800">View All Notifications</a>
                            </div>
                        @else
                            <p class="text-gray-500 text-center py-4">No unread notifications.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

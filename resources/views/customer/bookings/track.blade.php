@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Tracking: {{ $booking->booking_code }}
    </h2>
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <div class="text-center mb-8">
                    <h3 class="text-lg font-semibold text-gray-800">Booking #{{ $booking->booking_code }}</h3>
                    <p class="text-sm text-gray-500">{{ ucfirst(str_replace('_', ' ', $booking->service_type)) }}</p>
                </div>

                @php
                    $allStatuses = ['pending', 'confirmed', 'picked_up', 'cleaning', 'completed', 'delivered'];
                    $currentIndex = array_search($booking->status, $allStatuses);
                    $timestamps = [
                        'pending' => $booking->created_at,
                        'confirmed' => $booking->confirmed_at ?? null,
                        'picked_up' => $booking->picked_up_at,
                        'cleaning' => $booking->cleaning_started_at,
                        'completed' => $booking->completed_at,
                        'delivered' => $booking->delivered_at,
                    ];
                    $labels = [
                        'pending' => 'Pending',
                        'confirmed' => 'Confirmed',
                        'picked_up' => 'Picked Up',
                        'cleaning' => 'Cleaning',
                        'completed' => 'Completed',
                        'delivered' => 'Delivered',
                    ];
                    $icons = [
                        'pending' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
                        'confirmed' => 'M5 13l4 4L19 7',
                        'picked_up' => 'M5 13l4 4L19 7',
                        'cleaning' => 'M5 13l4 4L19 7',
                        'completed' => 'M5 13l4 4L19 7',
                        'delivered' => 'M5 13l4 4L19 7',
                    ];
                @endphp

                <div class="relative">
                    <div class="absolute left-8 top-0 bottom-0 w-0.5 bg-gray-200"></div>

                    <div class="space-y-0">
                        @foreach($allStatuses as $index => $status)
                            @php
                                $passed = $index <= $currentIndex;
                                $current = $index === $currentIndex;
                                $ts = $timestamps[$status] ?? null;

                                $circleColor = $passed && !$current ? 'bg-green-500' : ($current ? 'bg-blue-500' : 'bg-gray-200');
                                $iconColor = $passed ? 'text-white' : 'text-gray-400';
                                $textColor = $passed && !$current ? 'text-green-700' : ($current ? 'text-blue-700' : 'text-gray-400');
                                $lineColor = $index < $currentIndex ? 'bg-green-500' : 'bg-gray-200';
                            @endphp

                            <div class="relative flex items-start pb-8 {{ $loop->last ? 'pb-0' : '' }}">
                                <div class="flex flex-col items-center">
                                    <div class="w-16 h-16 rounded-full {{ $circleColor }} flex items-center justify-center z-10 shadow-md transition-all duration-300">
                                        @if($passed && !$current)
                                            <svg class="w-8 h-8 {{ $iconColor }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                        @elseif($current)
                                            <svg class="w-8 h-8 {{ $iconColor }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        @else
                                            <svg class="w-8 h-8 {{ $iconColor }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        @endif
                                    </div>
                                    @if(!$loop->last)
                                        <div class="w-1 h-12 {{ $lineColor }}"></div>
                                    @endif
                                </div>
                                <div class="ml-6 flex-1 {{ $textColor }}">
                                    <p class="text-lg font-semibold">{{ $labels[$status] }}</p>
                                    @if($ts)
                                        <p class="text-sm {{ $passed && !$current ? 'text-green-500' : ($current ? 'text-blue-500' : 'text-gray-400') }}">
                                            {{ $ts instanceof \Carbon\Carbon ? $ts->format('M d, Y h:i A') : '' }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="mt-8 text-center">
                    <a href="{{ route('customer.bookings.show', $booking) }}" class="text-indigo-600 hover:text-indigo-800">View Full Details</a>
                    <span class="mx-2 text-gray-300">|</span>
                    <a href="{{ route('customer.bookings.index') }}" class="text-indigo-600 hover:text-indigo-800">Back to Bookings</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

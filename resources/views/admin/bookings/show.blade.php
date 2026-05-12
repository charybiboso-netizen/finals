@extends('layouts.app')

@section('header', 'Booking Details')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        @if(session('success'))
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">{{ session('success') }}</div>
        @endif

    <div class="mb-4 flex justify-between items-center">
        <div>
            <a href="{{ route('admin.bookings.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">Back to List</a>
        </div>
        <div class="space-x-2">
            <a href="{{ route('admin.bookings.edit', $booking) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">Edit</a>
            @if(!in_array($booking->status, ['completed', 'delivered', 'cancelled']))
                <form action="{{ route('admin.bookings.cancel', $booking) }}" method="POST" class="inline" onsubmit="return confirm('Cancel this booking?')">
                    @csrf
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700">Cancel Booking</button>
                </form>
            @endif
        </div>
    </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-800">Booking Information</h3>
                    </div>
                    <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <span class="text-sm font-medium text-gray-500">Booking Code</span>
                            <p class="text-gray-800 font-mono">{{ $booking->booking_code }}</p>
                        </div>
                        <div>
                            <span class="text-sm font-medium text-gray-500">Status</span>
                            <p class="mt-1">
                                @php
                                    $statusColors = ['pending'=>'yellow','confirmed'=>'blue','picked_up'=>'indigo','cleaning'=>'purple','completed'=>'green','delivered'=>'green','cancelled'=>'red'];
                                    $sc = $statusColors[$booking->status] ?? 'gray';
                                @endphp
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-{{ $sc }}-100 text-{{ $sc }}-800">{{ ucfirst(str_replace('_', ' ', $booking->status)) }}</span>
                            </p>
                        </div>
                        <div>
                            <span class="text-sm font-medium text-gray-500">Customer Name</span>
                            <p class="text-gray-800">{{ $booking->customer->name ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <span class="text-sm font-medium text-gray-500">Email</span>
                            <p class="text-gray-800">{{ $booking->customer->email ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <span class="text-sm font-medium text-gray-500">Phone</span>
                            <p class="text-gray-800">{{ $booking->customer->phone ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <span class="text-sm font-medium text-gray-500">Service Type</span>
                            <p class="text-gray-800">{{ ucfirst(str_replace('_', ' ', $booking->service_type)) }}</p>
                        </div>
                        <div>
                            <span class="text-sm font-medium text-gray-500">Total Price</span>
                            <p class="text-gray-800 font-semibold">₱{{ number_format($booking->total_price, 2) }}</p>
                        </div>
                        <div>
                            <span class="text-sm font-medium text-gray-500">Payment Status</span>
                            <p class="mt-1">
                                @if($booking->is_paid)
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Paid</span>
                                    @if($booking->paid_at)
                                        <span class="text-xs text-gray-500 ml-1">{{ $booking->paid_at->format('M d, Y h:i A') }}</span>
                                    @endif
                                    @if($booking->payment_method)
                                        <span class="text-xs text-gray-500 ml-1">({{ $booking->payment_method }})</span>
                                    @endif
                                @else
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Unpaid</span>
                                @endif
                            </p>
                        </div>
                        <div>
                            <span class="text-sm font-medium text-gray-500">Payment Method</span>
                            <p class="text-gray-800">{{ $booking->payment_method ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <span class="text-sm font-medium text-gray-500">Pickup Date</span>
                            <p class="text-gray-800">{{ $booking->pickup_date ? $booking->pickup_date->format('M d, Y') : 'N/A' }}</p>
                        </div>
                        <div>
                            <span class="text-sm font-medium text-gray-500">Delivery Date</span>
                            <p class="text-gray-800">{{ $booking->delivery_date ? $booking->delivery_date->format('M d, Y') : 'N/A' }}</p>
                        </div>
                        <div class="md:col-span-2">
                            <span class="text-sm font-medium text-gray-500">Notes</span>
                            <p class="text-gray-800 mt-1">{{ $booking->notes ?? 'No notes' }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-800">Services</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Service</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Quantity</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Unit Price</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($booking->services as $service)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">{{ $service->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $service->pivot->quantity }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">₱{{ number_format($service->price, 2) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">₱{{ number_format($service->pivot->price, 2) }}</td>
                                </tr>
                                @empty
                                <tr><td colspan="4" class="px-6 py-4 text-center text-gray-500">No services listed</td></tr>
                                @endforelse
                            </tbody>
                            <tfoot class="bg-gray-50">
                                <tr>
                                    <td colspan="3" class="px-6 py-3 text-right text-sm font-semibold text-gray-700">Total</td>
                                    <td class="px-6 py-3 whitespace-nowrap text-sm font-bold text-gray-800">₱{{ number_format($booking->total_price, 2) }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-800">Timeline</h3>
                    </div>
                    <div class="p-6">
                        <div class="relative">
                            @php
                                $timelineEvents = [];
                                if ($booking->created_at) $timelineEvents[] = ['label' => 'Booking Created', 'date' => $booking->created_at, 'icon' => 'plus'];
                                if ($booking->picked_up_at) $timelineEvents[] = ['label' => 'Picked Up', 'date' => $booking->picked_up_at, 'icon' => 'truck'];
                                if ($booking->delivered_at) $timelineEvents[] = ['label' => 'Delivered', 'date' => $booking->delivered_at, 'icon' => 'check'];
                                if ($booking->completed_at) $timelineEvents[] = ['label' => 'Completed', 'date' => $booking->completed_at, 'icon' => 'check'];
                                if ($booking->cancelled_at) $timelineEvents[] = ['label' => 'Cancelled', 'date' => $booking->cancelled_at, 'icon' => 'x'];
                                                                $statusTimestamps = [];
                                                                if ($booking->confirmed_at) $statusTimestamps[] = ['status' => 'confirmed', 'created_at' => $booking->confirmed_at];
                                                                if ($booking->cleaning_started_at) $statusTimestamps[] = ['status' => 'cleaning', 'created_at' => $booking->cleaning_started_at];
                                                                foreach ($statusTimestamps as $st) {
                                                                    $timelineEvents[] = ['label' => ucfirst(str_replace('_', ' ', $st['status'])), 'date' => $st['created_at'], 'icon' => 'circle'];
                                                                }
                                usort($timelineEvents, function($a, $b) { return strtotime($a['date']) - strtotime($b['date']); });
                            @endphp
                            @forelse($timelineEvents as $i => $event)
                                <div class="flex items-start mb-6 {{ $i === count($timelineEvents) - 1 ? '' : '' }}">
                                    <div class="flex items-center justify-center w-8 h-8 rounded-full {{ $i === count($timelineEvents) - 1 ? 'bg-green-500' : 'bg-gray-300' }} text-white text-sm font-bold flex-shrink-0">
                                        @if($event['icon'] === 'check')
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        @elseif($event['icon'] === 'truck')
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a2 2 0 11-4 0 2 2 0 014 0zm14 0a2 2 0 11-4 0 2 2 0 014 0zM5 12h14M5 8h14"/></svg>
                                        @elseif($event['icon'] === 'x')
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                        @elseif($event['icon'] === 'plus')
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                                        @else
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                                        @endif
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-sm font-medium text-gray-800">{{ $event['label'] }}</p>
                                        <p class="text-xs text-gray-500">{{ $event['date'] instanceof \Carbon\Carbon ? $event['date']->format('M d, Y h:i A') : \Carbon\Carbon::parse($event['date'])->format('M d, Y h:i A') }}</p>
                                    </div>
                                </div>
                            @empty
                                <p class="text-gray-500 text-sm">No timeline events available</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-800">Staff Assignments</h3>
                    </div>
                    <div class="p-6">
                        @php $assignments = $booking->staffAssignments ?? collect(); @endphp
                        @forelse($assignments as $assignment)
                            <div class="flex items-center justify-between py-2 border-b border-gray-100 last:border-0">
                                <div>
                                    <p class="text-sm font-medium text-gray-800">{{ $assignment->staff->name ?? 'N/A' }}</p>
                                    <p class="text-xs text-gray-500">{{ $assignment->role ?? 'Staff' }}</p>
                                </div>
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-{{ $assignment->status === 'completed' ? 'green' : 'yellow' }}-100 text-{{ $assignment->status === 'completed' ? 'green' : 'yellow' }}-800">{{ ucfirst($assignment->status ?? 'assigned') }}</span>
                            </div>
                        @empty
                            <p class="text-gray-500 text-sm">No staff assigned</p>
                        @endforelse
                    </div>
                </div>

                @if($booking->rating)
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-800">Rating</h3>
                    </div>
                    <div class="p-6">
                        <div class="flex items-center mb-2">
                            @for($i = 1; $i <= 5; $i++)
                                <svg class="w-5 h-5 {{ $i <= $booking->rating->rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            @endfor
                        </div>
                        @if($booking->rating->review)
                            <p class="text-sm text-gray-600">"{{ $booking->rating->review }}"</p>
                        @endif
                        <p class="text-xs text-gray-500 mt-2">Submitted {{ $booking->rating->created_at->format('M d, Y') }}</p>
                    </div>
                </div>
                @endif

                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-800">Payment Action</h3>
                    </div>
                    <div class="p-6">
                        @if($booking->is_paid)
                            <form action="{{ route('admin.bookings.mark-unpaid', $booking) }}" method="POST" class="inline" onsubmit="return confirm('Mark this booking as unpaid?')">
                                @csrf
                                <button type="submit" class="w-full px-4 py-2 bg-yellow-600 text-white text-sm font-semibold rounded-md hover:bg-yellow-700">Mark as Unpaid</button>
                            </form>
                        @else
                            <form action="{{ route('admin.bookings.mark-paid', $booking) }}" method="POST" class="inline" onsubmit="return confirm('Mark this booking as paid?')">
                                @csrf
                                <button type="submit" class="w-full px-4 py-2 bg-green-600 text-white text-sm font-semibold rounded-md hover:bg-green-700">Mark as Paid</button>
                            </form>
                        @endif
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-800">Additional Info</h3>
                    </div>
                    <div class="p-6 space-y-3">
                        <div>
                            <span class="text-xs font-medium text-gray-500">Weight</span>
                            <p class="text-sm text-gray-800">{{ $booking->total_weight ? $booking->total_weight . ' kg' : 'N/A' }}</p>
                        </div>
                        <div>
                            <span class="text-xs font-medium text-gray-500">Pickup Address</span>
                            <p class="text-sm text-gray-800">{{ $booking->pickup_address ?? $booking->customer->address ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <span class="text-xs font-medium text-gray-500">Delivery Address</span>
                            <p class="text-sm text-gray-800">{{ $booking->delivery_address ?? $booking->customer->address ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <span class="text-xs font-medium text-gray-500">Created At</span>
                            <p class="text-sm text-gray-800">{{ $booking->created_at->format('M d, Y h:i A') }}</p>
                        </div>
                        <div>
                            <span class="text-xs font-medium text-gray-500">Updated At</span>
                            <p class="text-sm text-gray-800">{{ $booking->updated_at->format('M d, Y h:i A') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
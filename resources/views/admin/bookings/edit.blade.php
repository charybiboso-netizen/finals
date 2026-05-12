@extends('layouts.app')

@section('header', 'Edit Booking')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800">Edit Booking: {{ $booking->booking_code }}</h3>
            </div>
            <form action="{{ route('admin.bookings.update', $booking) }}" method="POST" class="p-6">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                        <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('status') border-red-500 @enderror">
                            <option value="pending" {{ old('status', $booking->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="confirmed" {{ old('status', $booking->status) == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                            <option value="picked_up" {{ old('status', $booking->status) == 'picked_up' ? 'selected' : '' }}>Picked Up</option>
                            <option value="cleaning" {{ old('status', $booking->status) == 'cleaning' ? 'selected' : '' }}>Cleaning</option>
                            <option value="completed" {{ old('status', $booking->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="delivered" {{ old('status', $booking->status) == 'delivered' ? 'selected' : '' }}>Delivered</option>
                            <option value="received" {{ old('status', $booking->status) == 'received' ? 'selected' : '' }}>Received</option>
                            <option value="cancelled" {{ old('status', $booking->status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                        @error('status') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="staff_id" class="block text-sm font-medium text-gray-700">Assign Staff</label>
                        <select name="staff_id" id="staff_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('staff_id') border-red-500 @enderror">
                            <option value="">-- Select Staff --</option>
                            @foreach($staff as $member)
                                <option value="{{ $member->id }}" {{ old('staff_id', $booking->staff_id) == $member->id ? 'selected' : '' }}>{{ $member->name }}</option>
                            @endforeach
                        </select>
                        @error('staff_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="total_price" class="block text-sm font-medium text-gray-700">Total Price</label>
                        <input type="number" step="0.01" name="total_price" id="total_price" value="{{ old('total_price', $booking->total_price) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('total_price') border-red-500 @enderror">
                        @error('total_price') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Payment Status</label>
                        <div class="mt-2 space-x-4">
                            <label class="inline-flex items-center">
                                <input type="radio" name="is_paid" value="1" {{ old('is_paid', $booking->is_paid) ? 'checked' : '' }} class="rounded-full border-gray-300 text-green-600 shadow-sm focus:ring-green-500">
                                <span class="ml-2 text-sm text-gray-700">Paid</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="radio" name="is_paid" value="0" {{ !old('is_paid', $booking->is_paid) ? 'checked' : '' }} class="rounded-full border-gray-300 text-red-600 shadow-sm focus:ring-red-500">
                                <span class="ml-2 text-sm text-gray-700">Unpaid</span>
                            </label>
                        </div>
                    </div>
                    <div>
                        <label for="payment_method" class="block text-sm font-medium text-gray-700">Payment Method</label>
                        <select name="payment_method" id="payment_method" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">-- Select --</option>
                            <option value="cash" {{ old('payment_method', $booking->payment_method) == 'cash' ? 'selected' : '' }}>Cash</option>
                            <option value="gcash" {{ old('payment_method', $booking->payment_method) == 'gcash' ? 'selected' : '' }}>GCash</option>
                            <option value="maya" {{ old('payment_method', $booking->payment_method) == 'maya' ? 'selected' : '' }}>Maya</option>
                            <option value="bank_transfer" {{ old('payment_method', $booking->payment_method) == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                            <option value="card" {{ old('payment_method', $booking->payment_method) == 'card' ? 'selected' : '' }}>Card</option>
                        </select>
                        @error('payment_method') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="pickup_date" class="block text-sm font-medium text-gray-700">Pickup Date</label>
                        <input type="date" name="pickup_date" id="pickup_date" value="{{ old('pickup_date', $booking->pickup_date ? $booking->pickup_date->format('Y-m-d') : '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('pickup_date') border-red-500 @enderror">
                        @error('pickup_date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="delivery_date" class="block text-sm font-medium text-gray-700">Delivery Date</label>
                        <input type="date" name="delivery_date" id="delivery_date" value="{{ old('delivery_date', $booking->delivery_date ? $booking->delivery_date->format('Y-m-d') : '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('delivery_date') border-red-500 @enderror">
                        @error('delivery_date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="md:col-span-2">
                        <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
                        <textarea name="notes" id="notes" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('notes') border-red-500 @enderror">{{ old('notes', $booking->notes) }}</textarea>
                        @error('notes') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
                <div class="mt-6 flex items-center justify-end">
                    <a href="{{ route('admin.bookings.show', $booking) }}" class="px-4 py-2 text-gray-700 hover:underline">Cancel</a>
                    <button type="submit" class="ml-3 inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">Update Booking</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
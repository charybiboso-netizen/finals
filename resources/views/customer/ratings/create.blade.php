@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Rate Your Experience') }}
    </h2>
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
            <div class="p-4 bg-gray-50 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Booking #{{ $booking->booking_code }}</p>
                        <p class="font-semibold text-gray-800">{{ ucfirst(str_replace('_', ' ', $booking->service_type)) }}</p>
                    </div>
                    <p class="text-lg font-bold text-gray-800">₱{{ number_format($booking->total_price, 2) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <form method="POST" action="{{ route('customer.ratings.store', $booking) }}">
                    @csrf

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-3">Rating</label>
                        <div class="flex items-center space-x-2" x-data="{ rating: {{ old('rating', 0) }} }">
                            @for($i = 1; $i <= 5; $i++)
                                <button type="button" @click="rating = {{ $i }}" class="focus:outline-none transition-transform duration-150 hover:scale-110">
                                    <svg class="w-10 h-10" :class="rating >= {{ $i }} ? 'text-yellow-400' : 'text-gray-300'" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                </button>
                            @endfor
                            <input type="hidden" name="rating" x-model="rating" />
                            <div class="ml-3">
                                <template x-if="rating == 1"><span class="text-sm text-gray-500">Poor</span></template>
                                <template x-if="rating == 2"><span class="text-sm text-gray-500">Fair</span></template>
                                <template x-if="rating == 3"><span class="text-sm text-gray-500">Good</span></template>
                                <template x-if="rating == 4"><span class="text-sm text-gray-500">Very Good</span></template>
                                <template x-if="rating == 5"><span class="text-sm text-gray-500">Excellent</span></template>
                                <template x-if="rating == 0"><span class="text-sm text-gray-400">Click to rate</span></template>
                            </div>
                        </div>
                        <x-input-error :messages="$errors->get('rating')" class="mt-2" />
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Review (Optional)</label>
                        <textarea name="review" rows="4" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Share your experience...">{{ old('review') }}</textarea>
                        <x-input-error :messages="$errors->get('review')" class="mt-2" />
                    </div>

                    <div class="flex items-center justify-between">
                        <a href="{{ route('customer.bookings.show', $booking) }}" class="text-indigo-600 hover:text-indigo-800">&larr; Back</a>
                        <button type="submit" class="bg-indigo-600 text-white py-2 px-6 rounded-md hover:bg-indigo-700 font-medium">Submit Rating</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
@endpush

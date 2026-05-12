@extends('layouts.app')

@section('header', 'Rating Details')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="mb-4">
            <a href="{{ route('admin.ratings.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">Back to List</a>
        </div>

        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800">Rating Information</h3>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <span class="text-sm font-medium text-gray-500">Customer Name</span>
                    <p class="text-gray-800">{{ $rating->customer->name ?? $rating->booking->customer->name ?? 'N/A' }}</p>
                    @if($rating->customer)
                        <p class="text-xs text-gray-500">{{ $rating->customer->email }}</p>
                    @endif
                </div>
                <div>
                    <span class="text-sm font-medium text-gray-500">Booking Code</span>
                    <p class="text-gray-800 font-mono">{{ $rating->booking->booking_code ?? 'N/A' }}</p>
                </div>
                <div>
                    <span class="text-sm font-medium text-gray-500">Star Rating</span>
                    <div class="flex items-center mt-1">
                        @for($i = 1; $i <= 5; $i++)
                            <svg class="w-5 h-5 {{ $i <= $rating->rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        @endfor
                        <span class="ml-2 text-sm text-gray-600">({{ $rating->rating }}/5)</span>
                    </div>
                </div>
                <div>
                    <span class="text-sm font-medium text-gray-500">Status</span>
                    <p class="mt-1">
                        @if($rating->is_approved)
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Approved</span>
                        @else
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Pending</span>
                        @endif
                    </p>
                </div>
                <div class="md:col-span-2">
                    <span class="text-sm font-medium text-gray-500">Review</span>
                    <p class="text-gray-800 mt-1 whitespace-pre-wrap">{{ $rating->review ?? 'No review provided' }}</p>
                </div>
                <div>
                    <span class="text-sm font-medium text-gray-500">Created At</span>
                    <p class="text-gray-800">{{ $rating->created_at->format('M d, Y h:i A') }}</p>
                </div>
                @if($rating->approved_at)
                <div>
                    <span class="text-sm font-medium text-gray-500">Approved At</span>
                    <p class="text-gray-800">{{ $rating->approved_at instanceof \Carbon\Carbon ? $rating->approved_at->format('M d, Y h:i A') : \Carbon\Carbon::parse($rating->approved_at)->format('M d, Y h:i A') }}</p>
                </div>
                @endif
            </div>
        </div>

        @if(!$rating->is_approved)
        <div class="mt-4">
            <form action="{{ route('admin.ratings.approve', $rating) }}" method="POST" class="inline">
                @csrf
                @method('PUT')
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700">Approve Rating</button>
            </form>
        </div>
        @endif
    </div>
</div>
@endsection
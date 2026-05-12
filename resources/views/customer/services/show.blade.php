@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ $service->name }}
    </h2>
@endsection

@section('content')
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="p-8">
                    <div class="mb-6">
                        <span class="text-sm font-medium text-indigo-600 bg-indigo-100 px-3 py-1 rounded-full">
                            {{ ucfirst(str_replace('_', ' ', $service->type ?? '')) }}
                        </span>
                    </div>

                    <h3 class="text-2xl font-bold text-gray-800 mb-4">{{ $service->name }}</h3>

                    <div class="prose max-w-none mb-6">
                        <p class="text-gray-600">{{ $service->description ?? 'No description available.' }}</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                        <div class="bg-gray-50 rounded-lg p-4 text-center">
                            <p class="text-sm text-gray-500 mb-1">Price</p>
                            <p class="text-3xl font-bold text-indigo-600">₱{{ number_format($service->price, 2) }}</p>
                            <p class="text-sm text-gray-500">per {{ $service->unit ?? 'item' }}</p>
                        </div>

                        @if($service->estimated_time)
                            <div class="bg-gray-50 rounded-lg p-4 text-center">
                                <p class="text-sm text-gray-500 mb-1">Estimated Time</p>
                                <p class="text-xl font-semibold text-gray-800">{{ $service->estimated_time }}</p>
                            </div>
                        @endif

                        <div class="bg-gray-50 rounded-lg p-4 text-center">
                            <p class="text-sm text-gray-500 mb-1">Status</p>
                            @if($service->is_active ?? true)
                                <p class="text-xl font-semibold text-green-600">Available</p>
                            @else
                                <p class="text-xl font-semibold text-red-600">Unavailable</p>
                            @endif
                        </div>
                    </div>

                    <div class="flex justify-center">
                        <a href="{{ route('customer.bookings.create') }}?service={{ $service->id }}"
                           class="inline-flex items-center px-8 py-3 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-700 transition">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Book This Service
                        </a>
                    </div>
                </div>
            </div>

            <div class="mt-6 text-center">
                <a href="{{ route('customer.services.index') }}" class="text-indigo-600 hover:text-indigo-800">
                    &larr; Back to Services
                </a>
            </div>
        </div>
    </div>
@endsection

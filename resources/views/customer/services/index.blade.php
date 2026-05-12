@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Our Services') }}
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

            @if(isset($services) && $services->count() > 0)
                @php
                    $typeLabels = [
                        'wash' => 'Wash Services',
                        'dry' => 'Dry Cleaning',
                        'fold' => 'Folding Services',
                        'iron' => 'Ironing Services',
                        'wash_dry_fold' => 'Wash, Dry & Fold',
                        'wash_iron' => 'Wash & Iron',
                        'full_service' => 'Full Service',
                    ];
                @endphp

                @foreach($services as $type => $typeServices)
                    <div class="mb-10">
                        <h3 class="text-2xl font-bold text-gray-800 mb-4 border-b border-gray-200 pb-2">
                            {{ $typeLabels[$type] ?? ucfirst(str_replace('_', ' ', $type)) }}
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($typeServices as $service)
                                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
                                    <div class="p-6">
                                        <h4 class="text-lg font-semibold text-gray-800 mb-2">{{ $service->name }}</h4>
                                        <p class="text-gray-600 text-sm mb-4">{{ $service->description ?? 'No description available.' }}</p>
                                        <div class="flex items-center justify-between mb-3">
                                            <span class="text-2xl font-bold text-indigo-600">₱{{ number_format($service->price, 2) }}</span>
                                            <span class="text-sm text-gray-500">per {{ str_replace('_', ' ', $service->unit) }}</span>
                                        </div>
                                        @if($service->estimated_hours)
                                            <div class="flex items-center text-sm text-gray-500 mb-4">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                ~{{ $service->estimated_hours }} hours
                                            </div>
                                        @endif
                                        <a href="{{ route('customer.bookings.create') }}?service={{ $service->id }}"
                                           class="block w-full text-center bg-indigo-600 text-white py-2 px-4 rounded-md hover:bg-indigo-700 transition font-medium">
                                            Book Now
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            @else
                <div class="bg-white rounded-lg shadow p-8 text-center">
                    <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                    <p class="text-gray-500 text-lg">No services available at the moment.</p>
                </div>
            @endif
        </div>
    </div>
@endsection

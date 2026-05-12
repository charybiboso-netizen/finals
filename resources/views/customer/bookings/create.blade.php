@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Book a Laundry Service') }}
    </h2>
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">{{ session('error') }}</div>
        @endif

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <form method="POST" action="{{ route('customer.bookings.store') }}">
                    @csrf

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Service Type</label>
                        <select name="service_type" id="service_type" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Select Service Type</option>
                            <option value="wash" {{ old('service_type') == 'wash' ? 'selected' : '' }}>Wash</option>
                            <option value="dry" {{ old('service_type') == 'dry' ? 'selected' : '' }}>Dry</option>
                            <option value="fold" {{ old('service_type') == 'fold' ? 'selected' : '' }}>Fold</option>
                            <option value="iron" {{ old('service_type') == 'iron' ? 'selected' : '' }}>Iron</option>
                            <option value="wash_dry_fold" {{ old('service_type') == 'wash_dry_fold' ? 'selected' : '' }}>Wash, Dry & Fold</option>
                            <option value="wash_iron" {{ old('service_type') == 'wash_iron' ? 'selected' : '' }}>Wash & Iron</option>
                            <option value="full_service" {{ old('service_type') == 'full_service' ? 'selected' : '' }}>Full Service</option>
                        </select>
                        <x-input-error :messages="$errors->get('service_type')" class="mt-2" />
                    </div>

                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-3">Select Services</h3>
                        @if(isset($services) && $services->count() > 0)
                            @php
                                $grouped = $services->groupBy('type');
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
                            @foreach($grouped as $type => $typeServices)
                                <div class="mb-4">
                                    <h4 class="text-md font-medium text-gray-700 mb-2 border-b border-gray-100 pb-1">{{ $typeLabels[$type] ?? ucfirst(str_replace('_', ' ', $type)) }}</h4>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                        @foreach($typeServices as $service)
                                            <label class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer">
                                                <input type="checkbox" name="services[{{ $service->id }}][selected]" value="1" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                                    {{ old('services.' . $service->id . '.selected') ? 'checked' : '' }}>
                                                <div class="ml-3 flex-1">
                                                    <span class="text-sm font-medium text-gray-800">{{ $service->name }}</span>
                                                    <span class="text-sm text-indigo-600 ml-2">₱{{ number_format($service->price, 2) }}</span>
                                                </div>
                                                <div class="ml-2">
                                                    <input type="number" name="services[{{ $service->id }}][quantity]" value="{{ old('services.' . $service->id . '.quantity', 1) }}"
                                                        min="1" class="w-16 text-sm rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Qty">
                                                </div>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <p class="text-gray-500">No services available.</p>
                        @endif
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Pickup Address</label>
                            <textarea name="pickup_address" rows="3" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('pickup_address') }}</textarea>
                            <x-input-error :messages="$errors->get('pickup_address')" class="mt-2" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Delivery Address</label>
                            <textarea name="delivery_address" rows="3" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('delivery_address') }}</textarea>
                            <x-input-error :messages="$errors->get('delivery_address')" class="mt-2" />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Pickup Date & Time</label>
                            <input type="datetime-local" name="pickup_date" value="{{ old('pickup_date') }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <x-input-error :messages="$errors->get('pickup_date')" class="mt-2" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Delivery Date & Time</label>
                            <input type="datetime-local" name="delivery_date" value="{{ old('delivery_date') }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <x-input-error :messages="$errors->get('delivery_date')" class="mt-2" />
                        </div>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                        <textarea name="notes" rows="3" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Special instructions...">{{ old('notes') }}</textarea>
                        <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                    </div>

                    <div class="flex items-center justify-between">
                        <a href="{{ route('customer.dashboard') }}" class="text-indigo-600 hover:text-indigo-800">&larr; Back</a>
                        <button type="submit" class="bg-indigo-600 text-white py-2 px-6 rounded-md hover:bg-indigo-700 font-medium">
                            Create Booking
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

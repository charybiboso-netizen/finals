@extends('layouts.app')

@section('header', 'Add Service')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800">New Service</h3>
            </div>
            <form action="{{ route('admin.services.store') }}" method="POST" class="p-6">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('name') border-red-500 @enderror">
                        @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700">Type</label>
                        <select name="type" id="type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('type') border-red-500 @enderror">
                            <option value="wash" {{ old('type') == 'wash' ? 'selected' : '' }}>Wash</option>
                            <option value="dry" {{ old('type') == 'dry' ? 'selected' : '' }}>Dry</option>
                            <option value="fold" {{ old('type') == 'fold' ? 'selected' : '' }}>Fold</option>
                            <option value="iron" {{ old('type') == 'iron' ? 'selected' : '' }}>Iron</option>
                            <option value="wash_dry_fold" {{ old('type') == 'wash_dry_fold' ? 'selected' : '' }}>Wash Dry Fold</option>
                            <option value="wash_iron" {{ old('type') == 'wash_iron' ? 'selected' : '' }}>Wash Iron</option>
                            <option value="full_service" {{ old('type') == 'full_service' ? 'selected' : '' }}>Full Service</option>
                        </select>
                        @error('type') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="price" class="block text-sm font-medium text-gray-700">Price</label>
                        <input type="number" step="0.01" name="price" id="price" value="{{ old('price') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('price') border-red-500 @enderror">
                        @error('price') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="unit" class="block text-sm font-medium text-gray-700">Unit</label>
                        <select name="unit" id="unit" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('unit') border-red-500 @enderror">
                            <option value="per_piece" {{ old('unit') == 'per_piece' ? 'selected' : '' }}>Per Piece</option>
                            <option value="per_kg" {{ old('unit') == 'per_kg' ? 'selected' : '' }}>Per Kg</option>
                            <option value="per_load" {{ old('unit') == 'per_load' ? 'selected' : '' }}>Per Load</option>
                        </select>
                        @error('unit') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="estimated_hours" class="block text-sm font-medium text-gray-700">Estimated Hours</label>
                        <input type="number" step="0.5" name="estimated_hours" id="estimated_hours" value="{{ old('estimated_hours') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('estimated_hours') border-red-500 @enderror">
                        @error('estimated_hours') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="md:col-span-2">
                        <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea name="description" id="description" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                        @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
                <div class="mt-6 flex items-center justify-end">
                    <a href="{{ route('admin.services.index') }}" class="px-4 py-2 text-gray-700 hover:underline">Cancel</a>
                    <button type="submit" class="ml-3 inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">Save Service</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
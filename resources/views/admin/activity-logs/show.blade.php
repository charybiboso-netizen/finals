@extends('layouts.app')

@section('header', 'Activity Log Details')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="mb-4">
            <a href="{{ route('admin.activity-logs.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">Back to List</a>
        </div>

        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800">Activity Log Entry</h3>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <span class="text-sm font-medium text-gray-500">User</span>
                    <p class="text-gray-800">{{ $log->user->name ?? 'System' }}</p>
                    @if($log->user)
                        <p class="text-xs text-gray-500">{{ $log->user->email }}</p>
                    @endif
                </div>
                <div>
                    <span class="text-sm font-medium text-gray-500">Action</span>
                    <p class="text-gray-800">{{ ucfirst($log->action) }}</p>
                </div>
                <div class="md:col-span-2">
                    <span class="text-sm font-medium text-gray-500">Description</span>
                    <p class="text-gray-800 mt-1">{{ $log->description }}</p>
                </div>
                <div>
                    <span class="text-sm font-medium text-gray-500">IP Address</span>
                    <p class="text-gray-800 font-mono">{{ $log->ip_address ?? 'N/A' }}</p>
                </div>
                <div>
                    <span class="text-sm font-medium text-gray-500">User Agent</span>
                    <p class="text-gray-800 text-sm break-all">{{ $log->user_agent ?? 'N/A' }}</p>
                </div>
                <div>
                    <span class="text-sm font-medium text-gray-500">Created At</span>
                    <p class="text-gray-800">{{ $log->created_at->format('M d, Y h:i A') }}</p>
                </div>
                <div>
                    <span class="text-sm font-medium text-gray-500">Updated At</span>
                    <p class="text-gray-800">{{ $log->updated_at->format('M d, Y h:i A') }}</p>
                </div>
                @if($log->properties)
                <div class="md:col-span-2">
                    <span class="text-sm font-medium text-gray-500">Properties</span>
                    <pre class="mt-1 bg-gray-100 p-3 rounded text-sm text-gray-800 overflow-x-auto">{{ json_encode($log->properties, JSON_PRETTY_PRINT) }}</pre>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
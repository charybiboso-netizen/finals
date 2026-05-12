@extends('layouts.app')

@section('header', 'Notification Details')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="mb-4">
            <a href="{{ route('admin.notifications.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">Back to List</a>
        </div>

        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800">Notification Information</h3>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <span class="text-sm font-medium text-gray-500">User</span>
                    <p class="text-gray-800">{{ $notification->user->name ?? 'All Users' }}</p>
                    @if($notification->user)
                        <p class="text-xs text-gray-500">{{ $notification->user->email }}</p>
                    @endif
                </div>
                <div>
                    <span class="text-sm font-medium text-gray-500">Type</span>
                    <p class="text-gray-800">{{ ucfirst($notification->type) }}</p>
                </div>
                <div class="md:col-span-2">
                    <span class="text-sm font-medium text-gray-500">Message</span>
                    <p class="text-gray-800 mt-1 whitespace-pre-wrap">{{ $notification->message }}</p>
                </div>
                <div>
                    <span class="text-sm font-medium text-gray-500">Channel</span>
                    <p class="text-gray-800">{{ ucfirst($notification->channel) }}</p>
                </div>
                <div>
                    <span class="text-sm font-medium text-gray-500">Read Status</span>
                    <p class="mt-1">
                        @if($notification->read_at)
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Read</span>
                        @else
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Unread</span>
                        @endif
                    </p>
                </div>
                @if($notification->read_at)
                <div>
                    <span class="text-sm font-medium text-gray-500">Read At</span>
                    <p class="text-gray-800">{{ $notification->read_at instanceof \Carbon\Carbon ? $notification->read_at->format('M d, Y h:i A') : \Carbon\Carbon::parse($notification->read_at)->format('M d, Y h:i A') }}</p>
                </div>
                @endif
                <div>
                    <span class="text-sm font-medium text-gray-500">Created At</span>
                    <p class="text-gray-800">{{ $notification->created_at->format('M d, Y h:i A') }}</p>
                </div>
                <div>
                    <span class="text-sm font-medium text-gray-500">Updated At</span>
                    <p class="text-gray-800">{{ $notification->updated_at->format('M d, Y h:i A') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
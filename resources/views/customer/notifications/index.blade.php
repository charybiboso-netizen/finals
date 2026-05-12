@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Notifications') }}
    </h2>
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">{{ session('error') }}</div>
        @endif

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <p class="text-sm text-gray-500">Manage your notifications</p>
                    <form action="{{ route('customer.notifications.mark-all-read') }}" method="POST">
                        @csrf
                        <button type="submit" class="bg-indigo-600 text-white py-1.5 px-4 rounded-md hover:bg-indigo-700 text-sm font-medium">
                            Mark All as Read
                        </button>
                    </form>
                </div>

                @if(isset($notifications) && $notifications->count() > 0)
                    <div class="space-y-3">
                        @foreach($notifications as $notification)
                            <div class="flex items-start p-4 rounded-lg border {{ $notification->is_read ? 'border-gray-200 bg-white' : 'border-indigo-200 bg-indigo-50' }}">
                                <div class="flex-shrink-0 mt-1">
                                    @if($notification->type === 'booking_update')
                                        <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    @elseif($notification->type === 'status_change')
                                        <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                        </svg>
                                    @else
                                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                                        </svg>
                                    @endif
                                </div>
                                <div class="ml-3 flex-1">
                                    <div class="flex items-center justify-between">
                                        <p class="text-sm font-medium {{ $notification->is_read ? 'text-gray-800' : 'text-indigo-800' }}">
                                            {{ ucfirst(str_replace('_', ' ', $notification->type)) }}
                                        </p>
                                        @if(!$notification->is_read)
                                            <form action="{{ route('customer.notifications.read', $notification) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="text-xs text-indigo-600 hover:text-indigo-800 font-medium">Mark Read</button>
                                            </form>
                                        @else
                                            <span class="text-xs text-gray-400">Read</span>
                                        @endif
                                    </div>
                                    <p class="text-sm text-gray-600 mt-1">{{ $notification->message }}</p>
                                    <div class="flex items-center mt-2 text-xs text-gray-400">
                                        <span class="px-2 py-0.5 rounded-full bg-gray-100 text-gray-600 mr-2">{{ $notification->channel ?? 'in-app' }}</span>
                                        <span>{{ $notification->created_at ? $notification->created_at->format('M d, Y h:i A') : '' }}</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-4">
                        {{ $notifications->links() }}
                    </div>
                @else
                    <div class="text-center py-8">
                        <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                        </svg>
                        <p class="text-gray-500">No notifications found.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

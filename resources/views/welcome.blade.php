<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'CleanSwift') }} - Laundry Management System</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Figtree', sans-serif; }
        .hero-gradient { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .feature-card:hover { transform: translateY(-4px); box-shadow: 0 12px 24px rgba(0,0,0,0.1); }
    </style>
</head>
<body class="antialiased">
    <div class="min-h-screen" style="background-color: #DDBEA9;">
        <header class="relative z-10">
            <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-2">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                        <span class="text-xl font-bold text-white">CleanSwift</span>
                    </div>
                    @if (Route::has('login'))
                        <div class="flex items-center space-x-4">
                            @auth
                                <a href="{{ url('/dashboard') }}" class="px-5 py-2 bg-white text-indigo-700 font-semibold rounded-lg hover:bg-indigo-50 transition">Dashboard</a>
                            @else

                            @endauth
                        </div>
                    @endif
                </div>
            </nav>
        </header>

        <main>
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 lg:py-24">
                <div class="text-center">
                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold text-white mb-6 leading-tight">
                        Your Laundry, <br><span class="text-indigo-900">Made Simple</span>
                    </h1>
                    <p class="text-xl text-white/80 max-w-2xl mx-auto mb-10">
                        Schedule pickups, track in real-time, and get fresh laundry delivered to your doorstep. The smartest way to handle your laundry.
                    </p>
                    @guest
                        <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                            <a href="{{ route('register') }}" class="px-8 py-3 bg-indigo-700 text-white font-semibold rounded-lg text-lg hover:bg-indigo-800 transition shadow-lg">
                                Get Started Free
                            </a>
                            <a href="{{ route('login') }}" class="px-8 py-3 bg-white text-indigo-700 font-semibold rounded-lg text-lg hover:bg-indigo-50 transition shadow-lg">
                                Sign In
                            </a>
                        </div>
                    @endguest
                    @auth
                        <a href="{{ route('dashboard') }}" class="inline-block px-8 py-3 bg-indigo-700 text-white font-semibold rounded-lg text-lg hover:bg-indigo-800 transition shadow-lg">
                            Go to Dashboard
                        </a>
                    @endauth
                </div>
            </div>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-16">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="bg-white/95 backdrop-blur-sm rounded-xl p-8 shadow-lg feature-card transition duration-300">
                        <div class="w-14 h-14 bg-indigo-100 rounded-lg flex items-center justify-center mb-5">
                            <svg class="w-7 h-7 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">Easy Booking</h3>
                        <p class="text-gray-600">Book your laundry service online in minutes. Choose your services, schedule pickup, and we handle the rest.</p>
                    </div>

                    <div class="bg-white/95 backdrop-blur-sm rounded-xl p-8 shadow-lg feature-card transition duration-300">
                        <div class="w-14 h-14 bg-green-100 rounded-lg flex items-center justify-center mb-5">
                            <svg class="w-7 h-7 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">Real-Time Tracking</h3>
                        <p class="text-gray-600">Track your laundry status from pickup to delivery. Get notified at every step of the process.</p>
                    </div>

                    <div class="bg-white/95 backdrop-blur-sm rounded-xl p-8 shadow-lg feature-card transition duration-300">
                        <div class="w-14 h-14 bg-purple-100 rounded-lg flex items-center justify-center mb-5">
                            <svg class="w-7 h-7 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">Fast & Reliable</h3>
                        <p class="text-gray-600">Professional cleaning with quick turnaround. Your clothes are handled with care and delivered on time.</p>
                    </div>
                </div>
            </div>



            <footer class="border-t border-white/20">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                    <p class="text-center text-white/60 text-sm">&copy; {{ date('Y') }} {{ config('app.name', 'CleanSwift') }}. All rights reserved.</p>
                </div>
            </footer>
        </main>
    </div>
</body>
</html>

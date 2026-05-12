<nav x-data="{ open: false }" class="sm:flex">
    {{-- Mobile hamburger --}}
    <div class="fixed top-0 left-0 z-50 flex items-center h-16 px-4 sm:hidden">
        <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    {{-- Overlay for mobile --}}
    <div x-show="open" class="fixed inset-0 z-30 bg-black/50 sm:hidden" @click="open = false" style="display: none;"></div>

    {{-- Sidebar --}}
    <div :class="{'translate-x-0': open, '-translate-x-full': ! open}"
         class="fixed top-0 left-0 z-40 w-64 h-screen bg-white shadow-lg transform transition-transform duration-300 ease-in-out sm:translate-x-0 sm:static sm:z-auto flex flex-col">
        
        {{-- Logo --}}
        <div class="flex items-center h-16 px-6 border-b border-gray-200 shrink-0">
            <a href="{{ route('dashboard') }}" class="flex items-center">
                <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
            </a>
        </div>

        {{-- User Info --}}
        <div class="px-6 py-4 border-b border-gray-200 shrink-0">
            <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
            <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
        </div>

        {{-- Navigation Links --}}
        <div class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">
            @auth
            @if(auth()->user()->isAdmin())
                <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">Dashboard</x-nav-link>
                <x-nav-link :href="route('admin.staff.index')" :active="request()->routeIs('admin.staff*')">Staff</x-nav-link>
                <x-nav-link :href="route('admin.customers.index')" :active="request()->routeIs('admin.customers*')">Customers</x-nav-link>
                <x-nav-link :href="route('admin.bookings.index')" :active="request()->routeIs('admin.bookings*')">Bookings</x-nav-link>
                <x-nav-link :href="route('admin.services.index')" :active="request()->routeIs('admin.services*')">Services</x-nav-link>
                <x-nav-link :href="route('admin.reports.index')" :active="request()->routeIs('admin.reports*')">Reports</x-nav-link>
            @elseif(auth()->user()->isStaff())
                <x-nav-link :href="route('staff.dashboard')" :active="request()->routeIs('staff.dashboard')">Dashboard</x-nav-link>
                <x-nav-link :href="route('staff.bookings.index')" :active="request()->routeIs('staff.bookings*')">Bookings</x-nav-link>
                <x-nav-link :href="route('staff.pickup-schedule')" :active="request()->routeIs('staff.pickup-schedule')">Pickups</x-nav-link>
                <x-nav-link :href="route('staff.delivery-schedule')" :active="request()->routeIs('staff.delivery-schedule')">Deliveries</x-nav-link>
            @elseif(auth()->user()->isCustomer())
                <x-nav-link :href="route('customer.dashboard')" :active="request()->routeIs('customer.dashboard')">Dashboard</x-nav-link>
                <x-nav-link :href="route('customer.services.index')" :active="request()->routeIs('customer.services*')">Services</x-nav-link>
                <x-nav-link :href="route('customer.bookings.create')" :active="request()->routeIs('customer.bookings.create')">Book Now</x-nav-link>
                <x-nav-link :href="route('customer.bookings.index')" :active="request()->routeIs('customer.bookings.index')">My Bookings</x-nav-link>
                <x-nav-link :href="route('customer.notifications.index')" :active="request()->routeIs('customer.notifications*')">Notifications</x-nav-link>
            @endif
            @endauth
        </div>

        {{-- Bottom: Profile & Logout --}}
        <div class="px-3 py-4 border-t border-gray-200 shrink-0 space-y-1">
            <x-nav-link :href="route('profile.edit')" :active="request()->routeIs('profile.edit')">Profile</x-nav-link>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <x-nav-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">Log Out</x-nav-link>
            </form>
        </div>
    </div>
</nav>

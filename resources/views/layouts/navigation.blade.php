<nav class="bg-white border-b border-gray-200 fixed w-full z-50 shadow-sm">
    <div class="max-w-screen-2xl mx-auto px-6">
        <div class="h-24 flex items-center justify-between">

            <!-- Logo -->
            <a href="{{ auth()->user()->dashboardRoute() }}" class="flex items-center gap-x-3">
                <img src="{{ asset('image/logo.png') }}"
                     alt="TutorMatch"
                     class="h-45 w-auto">
            </a>

            <!-- User Area -->
            <div class="flex items-center gap-4" x-data="{ dropdown: false }">
                <button @click="dropdown = !dropdown"
                        class="flex items-center gap-3 hover:bg-gray-50 px-4 py-2 rounded-2xl transition-all border border-transparent hover:border-gray-200">

                    <div class="w-8 h-8 bg-blue-700 text-white text-sm font-bold rounded-2xl flex items-center justify-center">
                        {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 2)) }}
                    </div>

                    <div class="hidden sm:block text-left">
                        <p class="text-sm font-semibold text-gray-800">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-gray-500">{{ ucfirst(Auth::user()->role ?? '') }}</p>
                    </div>

                    <i class="fa-solid fa-chevron-down text-xs"></i>
                </button>

                <!-- Dropdown -->
                <div x-show="dropdown" @click.away="dropdown = false"
                     class="absolute right-6 top-16 w-56 bg-white rounded-2xl shadow-2xl border border-gray-100 py-2 text-sm z-50">
                    <div class="px-5 py-3 border-b">
                        <p class="font-semibold">{{ Auth::user()->name }}</p>
                        <p class="text-gray-500 text-xs">{{ Auth::user()->email }}</p>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full px-5 py-3 text-red-600 hover:bg-red-50 flex items-center gap-3 text-left">
                            <i class="fa-solid fa-arrow-right-from-bracket"></i>
                            Keluar
                        </button>
                    </form>
                </div>

                <!-- Mobile Hamburger -->
                <button class="md:hidden text-gray-600 p-2" @click="dropdown = !dropdown">
                    <i class="fa-solid fa-bars text-xl"></i>
                </button>
            </div>
        </div>
    </div>
</nav>

<nav x-data="{ open: false }" class="sticky top-0 z-50 bg-white border-b border-gray-100 shadow-sm">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <!-- Logo (Left) -->
            <div class="shrink-0 flex items-center">
                <a href="{{ route('home') }}" class="flex items-center gap-2">
                    <span class="text-xl font-bold tracking-wider text-[#c9a227]">ARCHOFESA</span>
                </a>
            </div>

            <!-- Navigation Links (Center) -->
            <div class="hidden sm:flex sm:items-center sm:space-x-8">
                <a href="{{ route('home') }}" class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition {{ request()->routeIs('home') ? 'border-[#c9a227] text-gray-900 font-semibold' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    Beranda
                </a>
                <a href="{{ route('rooms') }}" class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition {{ request()->routeIs('rooms') ? 'border-[#c9a227] text-gray-900 font-semibold' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    Kamar
                </a>
                <a href="{{ route('gallery') }}" class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition {{ request()->routeIs('gallery') ? 'border-[#c9a227] text-gray-900 font-semibold' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    Galeri
                </a>
                <a href="{{ route('reviews') }}" class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition {{ request()->routeIs('reviews') ? 'border-[#c9a227] text-gray-900 font-semibold' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    Ulasan
                </a>
                <a href="{{ route('facilities') }}" class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition {{ request()->routeIs('facilities') ? 'border-[#c9a227] text-gray-900 font-semibold' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    Fasilitas
                </a>
                <a href="{{ route('about') }}" class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition {{ request()->routeIs('about') ? 'border-[#c9a227] text-gray-900 font-semibold' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    Tentang
                </a>
                <a href="{{ route('contact') }}" class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition {{ request()->routeIs('contact') ? 'border-[#c9a227] text-gray-900 font-semibold' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    Kontak
                </a>
            </div>

            <!-- Right Side (Desktop) -->
            <div class="hidden sm:flex sm:items-center sm:gap-3">
                @auth
                    @php
                        $userRole = auth()->user()->role ?? 'user';
                        $dashUrl = match($userRole) {
                            'admin' => route('admin.dashboard'),
                            'owner' => route('owner.dashboard'),
                            default => route('customer.dashboard'),
                        };
                    @endphp
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center gap-2 px-3 py-1.5 border border-gray-200 text-sm leading-4 font-medium rounded-full text-gray-700 bg-white hover:border-[#c9a227] hover:text-[#c9a227] focus:outline-none transition ease-in-out duration-150 shadow-sm cursor-pointer">
                                <span class="flex h-7 w-7 items-center justify-center rounded-full bg-[#c9a227] text-xs font-bold text-white">
                                    {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
                                </span>
                                <span>{{ Auth::user()->name }}</span>
                                <svg class="fill-current h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <div class="px-4 py-2 text-xs text-gray-400 border-b border-gray-100">
                                Akun ({{ strtoupper($userRole) }})
                            </div>
                            <x-dropdown-link :href="$dashUrl">
                                <span class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                                    Dashboard
                                </span>
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('profile.edit')">
                                <span class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                    Profil Saya
                                </span>
                            </x-dropdown-link>
                            <div class="border-t border-gray-100 my-1"></div>
                            <form method="POST" action="{{ route('logout') }}" id="logout-form-desktop">
                                @csrf
                                <button type="submit" class="w-full flex items-center gap-2 text-left px-4 py-2 text-sm leading-5 text-red-600 hover:bg-red-50 transition cursor-pointer">
                                    <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                    Keluar (Logout)
                                </button>
                            </form>
                        </x-slot>
                    </x-dropdown>
                @else
                    <div class="flex items-center gap-3">
                        <a href="{{ route('login') }}" class="text-sm font-medium text-gray-700 hover:text-[#c9a227] transition">Masuk</a>
                        <a href="{{ route('register') }}" class="rounded-full bg-[#c9a227] px-4 py-2 text-sm font-semibold text-white hover:bg-[#b68d1f] transition">Daftar</a>
                    </div>
                @endauth
            </div>

            <!-- Hamburger (Mobile) -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out cursor-pointer">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu (Mobile) -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden border-t border-gray-200">
        <!-- Navigation Links -->
        <div class="pt-2 pb-3 space-y-1">
            <a href="{{ route('home') }}" class="block pl-3 pr-4 py-2 border-l-4 text-base font-medium transition {{ request()->routeIs('home') ? 'border-[#c9a227] text-[#c9a227] bg-[#c9a227]/5 font-semibold' : 'border-transparent text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300' }}">
                Beranda
            </a>
            <a href="{{ route('rooms') }}" class="block pl-3 pr-4 py-2 border-l-4 text-base font-medium transition {{ request()->routeIs('rooms') ? 'border-[#c9a227] text-[#c9a227] bg-[#c9a227]/5 font-semibold' : 'border-transparent text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300' }}">
                Kamar
            </a>
            <a href="{{ route('gallery') }}" class="block pl-3 pr-4 py-2 border-l-4 text-base font-medium transition {{ request()->routeIs('gallery') ? 'border-[#c9a227] text-[#c9a227] bg-[#c9a227]/5 font-semibold' : 'border-transparent text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300' }}">
                Galeri
            </a>
            <a href="{{ route('reviews') }}" class="block pl-3 pr-4 py-2 border-l-4 text-base font-medium transition {{ request()->routeIs('reviews') ? 'border-[#c9a227] text-[#c9a227] bg-[#c9a227]/5 font-semibold' : 'border-transparent text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300' }}">
                Ulasan
            </a>
            <a href="{{ route('facilities') }}" class="block pl-3 pr-4 py-2 border-l-4 text-base font-medium transition {{ request()->routeIs('facilities') ? 'border-[#c9a227] text-[#c9a227] bg-[#c9a227]/5 font-semibold' : 'border-transparent text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300' }}">
                Fasilitas
            </a>
            <a href="{{ route('about') }}" class="block pl-3 pr-4 py-2 border-l-4 text-base font-medium transition {{ request()->routeIs('about') ? 'border-[#c9a227] text-[#c9a227] bg-[#c9a227]/5 font-semibold' : 'border-transparent text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300' }}">
                Tentang
            </a>
            <a href="{{ route('contact') }}" class="block pl-3 pr-4 py-2 border-l-4 text-base font-medium transition {{ request()->routeIs('contact') ? 'border-[#c9a227] text-[#c9a227] bg-[#c9a227]/5 font-semibold' : 'border-transparent text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300' }}">
                Kontak
            </a>
        </div>

        <!-- Responsive Settings Options -->
        @auth
            <div class="pt-4 pb-3 border-t border-gray-200 bg-gray-50">
                <div class="px-4 flex items-center gap-3">
                    <span class="flex h-10 w-10 items-center justify-center rounded-full bg-[#c9a227] text-sm font-bold text-white">
                        {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
                    </span>
                    <div>
                        <div class="font-semibold text-base text-gray-800">{{ Auth::user()->name }}</div>
                        <div class="font-medium text-xs text-gray-500">{{ Auth::user()->email }}</div>
                    </div>
                </div>

                <div class="mt-3 space-y-1">
                    @php
                        $dashUrl = match(auth()->user()->role ?? 'user') {
                            'admin' => route('admin.dashboard'),
                            'owner' => route('owner.dashboard'),
                            default => route('customer.dashboard'),
                        };
                    @endphp
                    <a href="{{ $dashUrl }}" class="block pl-3 pr-4 py-2 border-l-4 border-transparent text-base font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-100 hover:border-[#c9a227] transition">
                        Dashboard
                    </a>
                    <a href="{{ route('profile.edit') }}" class="block pl-3 pr-4 py-2 border-l-4 border-transparent text-base font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-100 hover:border-[#c9a227] transition">
                        Profil Saya
                    </a>
                    <form method="POST" action="{{ route('logout') }}" id="logout-form-mobile">
                        @csrf
                        <button type="submit" class="w-full text-left pl-3 pr-4 py-2 border-l-4 border-transparent text-base font-medium text-red-600 hover:text-red-800 hover:bg-red-50 hover:border-red-500 transition cursor-pointer">
                            Keluar (Logout)
                        </button>
                    </form>
                </div>
            </div>
        @else
            <div class="pt-4 pb-3 border-t border-gray-200">
                <div class="px-4 space-y-2">
                    <a href="{{ route('login') }}" class="block w-full text-center rounded-full border border-[#c9a227] px-4 py-2 text-sm font-semibold text-[#c9a227] hover:bg-[#c9a227]/5 transition">
                        Masuk
                    </a>
                    <a href="{{ route('register') }}" class="block w-full text-center rounded-full bg-[#c9a227] px-4 py-2 text-sm font-semibold text-white hover:bg-[#b68d1f] transition">
                        Daftar
                    </a>
                </div>
            </div>
        @endauth
    </div>
</nav>

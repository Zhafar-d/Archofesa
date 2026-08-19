<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin · ARCHOFESA KOST')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="min-h-full bg-[#faf8f5] font-[Inter] text-[#1f2937] antialiased">
<div class="flex min-h-screen">

    {{-- ── Sidebar ── --}}
    <aside class="sticky top-0 h-screen w-72 shrink-0 overflow-y-auto border-r border-[#e7e2d8] bg-white">
        {{-- Logo --}}
        <div class="flex items-center gap-2 border-b border-[#e7e2d8] px-7 py-6">
            <div>
                <p class="text-base font-bold tracking-[0.2em] text-[#c9a227]">ARCHOFESA</p>
                <p class="text-xs font-semibold tracking-wider text-[#6b7280]">Admin Panel</p>
            </div>
        </div>

        {{-- Nav --}}
        <nav class="space-y-1 p-5">
            @php
                $adminNav = [
                    ['route' => 'admin.dashboard',        'label' => 'Dashboard',           'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
                    ['route' => 'admin.bookings.index',   'label' => 'Booking Masuk',        'icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z'],
                    ['route' => 'admin.kamar.index',      'label' => 'Manajemen Kamar',      'icon' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4'],
                    ['route' => 'admin.pembayaran.index', 'label' => 'Manajemen Keuangan',   'icon' => 'M3 10h18M7 15h1m4 0h1M5 21h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v14a2 2 0 002 2z'],
                    ['route' => 'admin.penghuni.index',   'label' => 'Manajemen Penghuni',   'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z'],
                    ['route' => 'admin.chat',             'label' => 'Chat Owner',           'icon' => 'M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z'],
                ];
            @endphp

            @foreach($adminNav as $nav)
                @php $active = request()->routeIs($nav['route'] . '*'); @endphp
                <a href="{{ route($nav['route']) }}"
                   class="flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-medium transition
                          {{ $active ? 'bg-[#c9a227]/10 text-[#c9a227]' : 'text-[#374151] hover:bg-[#faf8f5] hover:text-[#c9a227]' }}">
                    <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $nav['icon'] }}"/>
                    </svg>
                    {{ $nav['label'] }}
                </a>
            @endforeach
        </nav>

        {{-- User + Logout --}}
        <div class="absolute bottom-0 left-0 right-0 border-t border-[#e7e2d8] bg-white p-5">
            <div class="flex items-center gap-3 rounded-xl bg-[#faf8f5] px-4 py-3">
                <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 min-w-0 flex-1 hover:opacity-80 transition" title="Lihat Profil">
                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-[#c9a227] text-sm font-bold text-white">
                        {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="truncate text-sm font-semibold text-[#1f2937]">{{ auth()->user()->name ?? 'Admin' }}</p>
                        <p class="truncate text-xs text-[#6b7280]">Administrator</p>
                    </div>
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" title="Logout" class="text-[#9ca3af] transition hover:text-red-500">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    {{-- ── Content ── --}}
    <div class="flex-1 overflow-y-auto">
        @yield('content')
    </div>

</div>
@stack('scripts')
</body>
</html>

<header class="sticky top-0 z-40 border-b border-[#e7e2d8] bg-white/90 px-4 py-4 backdrop-blur sm:px-6 lg:px-8">
    <div class="mx-auto flex max-w-7xl items-center justify-between gap-4">
        <a href="{{ route('customer.dashboard') }}" class="flex items-center gap-2">
            <div>
                <p class="text-base font-bold tracking-[0.2em] text-[#c9a227]">ARCHOFESA</p>
                <p class="text-xs font-semibold tracking-wider text-[#6b7280]">KOST</p>
            </div>
        </a>

        <nav class="hidden items-center gap-6 text-sm font-medium text-[#4b5563] lg:flex">
            <a href="{{ route('customer.dashboard') }}" class="transition hover:text-[#c9a227]">Beranda</a>
            <a href="{{ route('customer.rooms') }}" class="transition hover:text-[#c9a227]">Kamar</a>
            <a href="{{ route('gallery') }}" class="transition hover:text-[#c9a227]">Galeri</a>
            <a href="{{ route('facilities') }}" class="transition hover:text-[#c9a227]">Fasilitas</a>
            <a href="{{ route('customer.bookings') }}" class="transition hover:text-[#c9a227]">Booking Saya</a>
            <a href="{{ route('customer.profile') }}" class="transition hover:text-[#c9a227]">Profil</a>
        </nav>

        <div class="flex items-center gap-3">
            <a href="{{ route('customer.profile') }}" class="flex items-center gap-2 rounded-full border border-[#e7e2d8] px-3 py-2 text-sm font-semibold text-[#374151] transition hover:border-[#c9a227] hover:text-[#c9a227]">
                <span class="flex h-8 w-8 items-center justify-center rounded-full bg-[#c9a227] text-sm font-semibold text-white">{{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}</span>
                <span class="hidden sm:inline">{{ auth()->user()->name ?? 'Tamu' }}</span>
            </a>
            <form method="POST" action="{{ route('logout') }}" class="ml-2">
                @csrf
                <button type="submit" class="rounded-full border border-[#e7e2d8] px-3 py-2 text-sm font-semibold text-[#374151] transition hover:border-red-500 hover:text-red-500">
                    Keluar
                </button>
            </form>
        </div>
    </div>
</header>

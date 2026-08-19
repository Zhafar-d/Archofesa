<header class="sticky top-0 z-40 border-b border-[#e7e2d8] bg-white/90 px-4 py-4 backdrop-blur sm:px-6 lg:px-8">
    <div class="mx-auto flex max-w-7xl items-center justify-between gap-4">
        @php
            $userRole = auth()->user()->role ?? 'user';
            $homeUrl = match($userRole) {
                'admin' => route('admin.dashboard'),
                'owner' => route('owner.dashboard'),
                default => route('customer.dashboard'),
            };
        @endphp
        <a href="{{ $homeUrl }}" class="flex items-center">
            <div class="leading-tight">
                <p class="text-base font-bold tracking-[0.2em] text-[#c9a227]">ARCHOFESA</p>
                <p class="text-xs font-semibold tracking-[0.15em] text-[#6b7280]">
                    {{ $userRole === 'admin' ? 'ADMIN' : ($userRole === 'owner' ? 'OWNER' : 'KOST') }}
                </p>
            </div>
        </a>

        <nav class="hidden items-center gap-6 text-sm font-medium text-[#4b5563] lg:flex">
            @if($userRole === 'admin')
                <a href="{{ route('admin.dashboard') }}" class="transition hover:text-[#c9a227]">Dashboard</a>
                <a href="{{ route('admin.bookings.index') }}" class="transition hover:text-[#c9a227]">Booking Masuk</a>
                <a href="{{ route('admin.kamar.index') }}" class="transition hover:text-[#c9a227]">Kamar</a>
                <a href="{{ route('admin.pembayaran.index') }}" class="transition hover:text-[#c9a227]">Keuangan</a>
                <a href="{{ route('profile.edit') }}" class="transition hover:text-[#c9a227]">Profil</a>
            @elseif($userRole === 'owner')
                <a href="{{ route('owner.dashboard') }}" class="transition hover:text-[#c9a227]">Dashboard</a>
                <a href="{{ route('owner.konfirmasi.index') }}" class="transition hover:text-[#c9a227]">Konfirmasi</a>
                <a href="{{ route('owner.laporan.index') }}" class="transition hover:text-[#c9a227]">Laporan</a>
                <a href="{{ route('profile.edit') }}" class="transition hover:text-[#c9a227]">Profil</a>
            @else
                <a href="{{ route('customer.dashboard') }}" class="transition hover:text-[#c9a227]">Beranda</a>
                <a href="{{ route('customer.rooms') }}" class="transition hover:text-[#c9a227]">Kamar</a>
                <a href="{{ route('gallery') }}" class="transition hover:text-[#c9a227]">Galeri</a>
                <a href="{{ route('facilities') }}" class="transition hover:text-[#c9a227]">Fasilitas</a>
                <a href="{{ route('customer.bookings') }}" class="transition hover:text-[#c9a227]">Booking Saya</a>
                <a href="{{ route('profile.edit') }}" class="transition hover:text-[#c9a227]">Profil</a>
            @endif
        </nav>

        <div class="flex items-center gap-3">
            <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 rounded-full border border-[#e7e2d8] px-3 py-2 text-sm font-semibold text-[#374151] transition hover:border-[#c9a227] hover:text-[#c9a227]">
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

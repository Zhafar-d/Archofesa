<header class="sticky top-0 z-40 border-b border-[#e7e2d8] bg-white/90 backdrop-blur">
    <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-4 sm:px-6 lg:px-8">
        <a href="{{ route('home') }}" class="flex items-center gap-2">
            <div>
                <p class="text-base font-bold tracking-[0.2em] text-[#c9a227]">ARCHOFESA</p>
                <p class="text-xs font-semibold tracking-wider text-[#6b7280]">KOST</p>
            </div>
        </a>

        <nav class="hidden items-center gap-8 text-sm font-medium text-[#4b5563] md:flex">
            <a href="{{ route('about') }}" class="transition hover:text-[#c9a227]">Tentang</a>
            <a href="{{ route('facilities') }}" class="transition hover:text-[#c9a227]">Fasilitas</a>
            <a href="{{ route('rooms') }}" class="transition hover:text-[#c9a227]">Kamar</a>
            <a href="{{ route('gallery') }}" class="transition hover:text-[#c9a227]">Galeri</a>
            <a href="{{ route('contact') }}" class="transition hover:text-[#c9a227]">Kontak</a>
        </nav>

        <div class="flex items-center gap-3">
            <a href="{{ route('login') }}" class="hidden rounded-full border border-[#e7e2d8] px-4 py-2 text-sm font-medium text-[#374151] transition hover:border-[#c9a227] hover:text-[#c9a227] sm:inline-flex">Masuk</a>
            <a href="{{ route('register') }}" class="inline-flex rounded-full bg-[#c9a227] px-4 py-2 text-sm font-semibold text-white shadow-lg shadow-[#c9a227]/20 transition hover:bg-[#b68d1f]">Booking Sekarang</a>
        </div>
    </div>
</header>

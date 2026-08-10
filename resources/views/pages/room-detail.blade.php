@extends(auth()->check() ? 'layouts.dashboard-app' : 'layouts.app')

@section('title', 'Kamar ' . $room->room_code . ' · ARCHOFESA KOST')

@section('content')
<section class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">

    {{-- Back Link --}}
    <div class="mb-6">
        <a href="{{ auth()->check() ? route('customer.rooms') : route('rooms') }}" class="inline-flex items-center gap-2 rounded-full border border-[#e7e2d8] bg-white px-4 py-2 text-sm font-medium text-[#4b5563] shadow-sm transition hover:border-[#c9a227] hover:text-[#c9a227]">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Kembali ke Daftar Kamar
        </a>
    </div>

    @php
        $isAvailable = $room->is_available;
        $roomImages = $room->all_images;
        $mainImage = $roomImages[0] ?? $room->image_url;
        $roomType = $room->price_monthly >= 1400000 ? 'Family Room' : 'Student Room';
    @endphp

    <div class="grid gap-8 lg:grid-cols-12 items-start">
        
        {{-- ── LEFT COLUMN: Photo Showcase & Room Details (7 Cols) ── --}}
        <div class="space-y-6 lg:col-span-7">
            
            {{-- Room Image Card --}}
            <div class="overflow-hidden rounded-[32px] border border-[#e7e2d8] bg-white p-3 shadow-[0_20px_50px_-25px_rgba(15,23,42,0.12)]">
                <div class="relative aspect-[16/10] overflow-hidden rounded-[24px] bg-[#faf8f5]">
                    <img id="main-room-photo" src="{{ $mainImage }}" alt="Kamar {{ $room->room_code }}" class="h-full w-full object-cover transition duration-300">
                    <div class="absolute top-4 left-4">
                        @if($isAvailable)
                            <span class="inline-flex items-center gap-2 rounded-full bg-emerald-500/90 px-3.5 py-1.5 text-xs font-semibold text-white shadow-md backdrop-blur">
                                <span class="h-2 w-2 rounded-full bg-white animate-pulse"></span> Tersedia untuk Dihuni
                            </span>
                        @else
                            <span class="inline-flex items-center gap-2 rounded-full bg-rose-500/90 px-3.5 py-1.5 text-xs font-semibold text-white shadow-md backdrop-blur">
                                <span class="h-2 w-2 rounded-full bg-white"></span> Terisi / Tidak Tersedia
                            </span>
                        @endif
                    </div>
                </div>

                {{-- Thumbnail Gallery (if more than 1 image) --}}
                @if(count($roomImages) > 1)
                    <div class="mt-3 flex items-center gap-3 overflow-x-auto p-1">
                        @foreach($roomImages as $idx => $img)
                            <button type="button" onclick="document.getElementById('main-room-photo').src='{{ $img }}'" class="relative h-16 w-24 shrink-0 overflow-hidden rounded-xl border-2 border-[#e7e2d8] transition hover:border-[#c9a227] focus:border-[#c9a227]">
                                <img src="{{ $img }}" alt="Preview {{ $idx + 1 }}" class="h-full w-full object-cover">
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Room Description & Title Card --}}
            <div class="rounded-[32px] border border-[#e7e2d8] bg-white p-6 shadow-sm sm:p-8">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <span class="inline-flex rounded-full border border-[#c9a227]/30 bg-[#c9a227]/10 px-3.5 py-1 text-xs font-semibold uppercase tracking-[0.2em] text-[#c9a227]">
                        {{ $roomType }}
                    </span>
                    <span class="text-sm font-medium text-[#6b7280]">Kode Kamar: <strong class="text-[#1f2937]">{{ $room->room_code }}</strong></span>
                </div>

                <h1 class="mt-4 text-3xl font-bold text-[#1f2937] sm:text-4xl">Kamar {{ $room->room_code }}</h1>
                <p class="mt-4 text-base leading-7 text-[#4b5563]">
                    {{ $room->description ?? 'Kamar kos eksklusif dengan fasilitas lengkap, ventilasi udara yang baik, dan suasana yang sangat tenang. Dirancang khusus untuk mahasiswa dan profesional yang menginginkan hunian berkualitas di kawasan Pedurungan Semarang.' }}
                </p>
            </div>

            {{-- Room Facilities Grid --}}
            <div class="rounded-[32px] border border-[#e7e2d8] bg-white p-6 shadow-sm sm:p-8">
                <p class="text-xs font-semibold uppercase tracking-[0.24em] text-[#c9a227]">Fasilitas Kamar & Properti</p>
                <h2 class="mt-2 text-xl font-bold text-[#1f2937]">Semua yang Anda butuhkan ada di sini.</h2>

                <div class="mt-6 grid gap-4 sm:grid-cols-2">
                    <div class="flex items-center gap-3.5 rounded-2xl border border-[#e7e2d8] bg-[#faf8f5] p-4">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-[#c9a227]/15 text-[#c9a227]">
                            ❄️
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-[#1f2937]">Air Conditioner (AC)</p>
                            <p class="text-xs text-[#6b7280]">Ruangan tetap sejuk 24 jam</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3.5 rounded-2xl border border-[#e7e2d8] bg-[#faf8f5] p-4">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-[#c9a227]/15 text-[#c9a227]">
                            🚿
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-[#1f2937]">Kamar Mandi Dalam</p>
                            <p class="text-xs text-[#6b7280]">Privasi bersih & terjaga</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3.5 rounded-2xl border border-[#e7e2d8] bg-[#faf8f5] p-4">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-[#c9a227]/15 text-[#c9a227]">
                            📶
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-[#1f2937]">Wi-Fi Kecepatan Tinggi</p>
                            <p class="text-xs text-[#6b7280]">Lancar untuk tugas & kerja</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3.5 rounded-2xl border border-[#e7e2d8] bg-[#faf8f5] p-4">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-[#c9a227]/15 text-[#c9a227]">
                            🪑
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-[#1f2937]">Full Furnished</p>
                            <p class="text-xs text-[#6b7280]">Kasur, lemari, & meja belajar</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3.5 rounded-2xl border border-[#e7e2d8] bg-[#faf8f5] p-4">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-[#c9a227]/15 text-[#c9a227]">
                            🍳
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-[#1f2937]">Dapur Bersama</p>
                            <p class="text-xs text-[#6b7280]">Lengkap perlengkapan masak</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3.5 rounded-2xl border border-[#e7e2d8] bg-[#faf8f5] p-4">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-[#c9a227]/15 text-[#c9a227]">
                            🔒
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-[#1f2937]">Keamanan 24/7</p>
                            <p class="text-xs text-[#6b7280]">CCTV & akses aman</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        {{-- ── RIGHT COLUMN: Sticky Pricing & Booking Action Card (5 Cols) ── --}}
        <div class="lg:sticky lg:top-24 lg:col-span-5">
            <div class="rounded-[32px] border border-[#e7e2d8] bg-white p-6 shadow-[0_24px_70px_-32px_rgba(15,23,42,0.16)] sm:p-8">
                
                {{-- Price Tag --}}
                <div class="rounded-[24px] border border-[#e7e2d8] bg-[#faf8f5] p-5">
                    <p class="text-xs font-medium uppercase tracking-wider text-[#6b7280]">Biaya Sewa Bulanan</p>
                    <div class="mt-2 flex items-baseline gap-2">
                        <span class="text-3xl font-extrabold tracking-tight text-[#1f2937] sm:text-4xl">
                            Rp{{ number_format($room->price_monthly, 0, ',', '.') }}
                        </span>
                        <span class="text-sm font-medium text-[#6b7280]">/ bulan</span>
                    </div>
                    <p class="mt-2 text-xs text-emerald-600 font-medium">✓ Sudah termasuk biaya fasilitas standar</p>
                </div>

                {{-- Room Specifications Specs Table --}}
                <div class="mt-6 space-y-3.5 border-t border-b border-[#e7e2d8] py-5 text-sm">
                    <div class="flex justify-between items-center text-[#4b5563]">
                        <span>Kode Kamar</span>
                        <span class="font-semibold text-[#1f2937]">{{ $room->room_code }}</span>
                    </div>
                    <div class="flex justify-between items-center text-[#4b5563]">
                        <span>Dimensi Kamar</span>
                        <span class="font-semibold text-[#1f2937]">{{ $room->size }}</span>
                    </div>
                    <div class="flex justify-between items-center text-[#4b5563]">
                        <span>Kamar Mandi</span>
                        <span class="font-semibold text-[#1f2937]">Dalam (Private)</span>
                    </div>
                    <div class="flex justify-between items-center text-[#4b5563]">
                        <span>Status Saat Ini</span>
                        @if($isAvailable)
                            <span class="font-semibold text-emerald-600">Tersedia</span>
                        @else
                            <span class="font-semibold text-rose-600">Terisi</span>
                        @endif
                    </div>
                </div>

                {{-- Booking CTA Action Button --}}
                <div class="mt-6">
                    @if($isAvailable)
                        <a href="{{ auth()->check() ? route('booking') : route('login') }}"
                           class="flex w-full items-center justify-center gap-2 rounded-full bg-[#c9a227] py-4 text-sm font-semibold text-white shadow-lg shadow-[#c9a227]/25 transition hover:bg-[#b68d1f] focus:outline-none focus:ring-2 focus:ring-[#c9a227] focus:ring-offset-2">
                            <span>{{ auth()->check() ? 'Booking Kamar Ini Sekarang' : 'Login untuk Booking Kamar' }}</span>
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </a>
                    @else
                        <button type="button" disabled
                           class="flex w-full items-center justify-center gap-2 rounded-full bg-slate-200 py-4 text-sm font-semibold text-slate-500 cursor-not-allowed">
                            <span>Kamar Ini Sedang Terisi</span>
                        </button>
                    @endif
                </div>

                {{-- Protection Guarantees --}}
                <div class="mt-6 rounded-2xl bg-[#faf8f5] p-4 text-xs text-[#6b7280] space-y-2">
                    <p class="flex items-center gap-2">
                        <span class="text-[#c9a227]">🛡️</span> Lingkungan kos aman & tertib (24 jam)
                    </p>
                    <p class="flex items-center gap-2">
                        <span class="text-[#c9a227]">⚡</span> Tanpa biaya tersembunyi
                    </p>
                </div>

            </div>
        </div>

    </div>

</section>
@endsection

@extends('layouts.app')

@section('title', 'Kost The Archofesa Pedurungan Semarang · Sistem Reservasi & Manajemen Kos Modern')

@section('content')

{{-- ── Hero ── --}}
<section class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8 lg:py-24">
    <div class="grid items-center gap-12 lg:grid-cols-[1.05fr_0.95fr]">
        <div>
            <div class="inline-flex items-center rounded-full border border-[#e7e2d8] bg-white px-3 py-1 text-sm font-medium text-[#c9a227] shadow-sm">
                Hunian premium untuk mahasiswa dan profesional
            </div>
            <h1 class="mt-6 max-w-2xl text-4xl font-semibold tracking-tight text-[#1f2937] sm:text-5xl lg:text-6xl">
                Temukan kenyamanan menginap di <span class="text-[#c9a227]">Kost The Archofesa</span> Pedurungan Semarang.
            </h1>
            <p class="mt-6 max-w-xl text-lg leading-8 text-[#4b5563]">
                Pengalaman kos modern dengan kamar elegan, fasilitas bersama yang nyaman, dan sistem pemesanan yang mudah.
            </p>
            <div class="mt-8 flex flex-wrap gap-4">
                <a href="{{ $ctaRoute }}" class="inline-flex items-center rounded-full bg-[#c9a227] px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-[#c9a227]/20 transition hover:bg-[#b68d1f]">{{ $ctaLabel }}</a>
                <a href="{{ route('about') }}" class="inline-flex items-center rounded-full border border-[#e7e2d8] bg-white px-6 py-3 text-sm font-semibold text-[#374151] transition hover:border-[#c9a227] hover:text-[#c9a227]">Tentang Kami</a>
            </div>
            <div class="mt-10 flex flex-wrap gap-6 text-sm text-[#6b7280]">
                <div><span class="block text-2xl font-semibold text-[#1f2937]">{{ $totalRooms }}</span> kamar</div>
                <div><span class="block text-2xl font-semibold text-[#1f2937]">{{ $roomTypeCount }}</span> tipe kamar</div>
                <div><span class="block text-2xl font-semibold text-[#1f2937]">24/7</span> layanan</div>
            </div>
        </div>

        {{-- Foto Hero --}}
        <div class="rounded-[36px] border border-[#e7e2d8] bg-white p-4 shadow-[0_30px_90px_-28px_rgba(15,23,42,0.12)] sm:p-6">
            <div class="overflow-hidden rounded-[28px] bg-[#faf8f5]">
                @if($heroImage)
                    <img src="{{ $heroImage }}"
                         alt="Interior kamar Kost The Archofesa"
                         class="h-[420px] w-full object-cover object-center"
                         style="image-rendering: auto;">
                @else
                    <div class="flex h-[420px] w-full items-center justify-center bg-[#faf8f5]">
                        <p class="text-sm text-[#9ca3af]">Foto akan segera tersedia</p>
                    </div>
                @endif
            </div>
            <div class="mt-5 grid gap-3 sm:grid-cols-3">
                <div class="rounded-2xl border border-[#e7e2d8] bg-[#faf8f5] p-4">
                    <p class="text-sm text-[#6b7280]">Mulai dari</p>
                    <p class="mt-2 text-xl font-semibold text-[#1f2937]">Rp{{ number_format($minPrice, 0, ',', '.') }}</p>
                </div>
                <div class="rounded-2xl border border-[#e7e2d8] bg-[#faf8f5] p-4">
                    <p class="text-sm text-[#6b7280]">Kepuasan</p>
                    <p class="mt-2 text-xl font-semibold text-[#1f2937]">98% puas</p>
                </div>
                <div class="rounded-2xl border border-[#e7e2d8] bg-[#faf8f5] p-4">
                    <p class="text-sm text-[#6b7280]">Lokasi</p>
                    <p class="mt-2 text-xl font-semibold text-[#1f2937]">Strategis</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ── Statistik ── --}}
<section class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
    <div class="grid gap-4 rounded-[32px] border border-[#e7e2d8] bg-[#fffdf9] p-8 shadow-sm md:grid-cols-3">
        @foreach ($stats as $stat)
            <div class="rounded-2xl border border-[#e7e2d8] bg-white p-5">
                <p class="text-3xl font-semibold text-[#1f2937]">{{ $stat['value'] }}</p>
                <p class="mt-2 text-sm font-medium text-[#c9a227]">{{ $stat['label'] }}</p>
                <p class="mt-2 text-sm text-[#6b7280]">{{ $stat['detail'] }}</p>
            </div>
        @endforeach
    </div>
</section>

{{-- ── Fasilitas ── --}}
<section class="mx-auto max-w-7xl px-4 py-20 sm:px-6 lg:px-8">
    <div class="grid gap-10 rounded-[36px] border border-[#e7e2d8] bg-white p-8 shadow-sm lg:grid-cols-[0.8fr_1.2fr] lg:p-12">
        <div>
            <p class="text-sm font-semibold uppercase tracking-[0.24em] text-[#c9a227]">Fasilitas</p>
            <h2 class="mt-3 text-3xl font-semibold text-[#1f2937]">Setiap sudut dirancang untuk kenyamanan sehari-hari.</h2>
            <p class="mt-4 text-lg leading-8 text-[#4b5563]">Dari kamar mandi pribadi hingga ruang bersama, setiap fasilitas mendukung gaya hidup yang seimbang.</p>
        </div>
        <div class="grid gap-4 sm:grid-cols-2">
            @foreach ($facilities as $facility)
                <div class="rounded-[24px] border border-[#e7e2d8] bg-[#faf8f5] p-5">
                    <h3 class="text-lg font-semibold text-[#1f2937]">{{ $facility['title'] }}</h3>
                    <p class="mt-2 text-sm leading-7 text-[#4b5563]">{{ $facility['description'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ── Mengapa Pilih Kami + Peta ── --}}
<section class="mx-auto max-w-7xl px-4 py-20 sm:px-6 lg:px-8">
    <div class="grid gap-6 lg:grid-cols-[1fr_1.1fr]">
        <div class="rounded-[32px] border border-[#e7e2d8] bg-[#1f2937] p-8 text-white shadow-sm">
            <p class="text-sm font-semibold uppercase tracking-[0.24em] text-[#f2d98d]">Mengapa Memilih Kami</p>
            <h2 class="mt-3 text-3xl font-semibold">Desain matang, aturan jelas, dan kehidupan yang nyaman.</h2>
            <ul class="mt-8 space-y-4 text-sm leading-7 text-slate-300">
                <li>• Khusus mahasiswa dan karyawan, dengan komunitas yang tertib dan kondusif.</li>
                <li>• Interior bersih dan terawat dengan furnitur serta pencahayaan modern.</li>
                <li>• Pilihan sewa bulanan yang fleksibel untuk penghuni jangka panjang.</li>
            </ul>
        </div>
        <div class="rounded-[32px] border border-[#e7e2d8] bg-white p-6 shadow-sm">
            <div class="mb-4 flex items-center justify-between gap-4">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-[0.24em] text-[#c9a227]">Lokasi</p>
                    <p class="mt-1 text-sm text-[#6b7280]">Pedurungan, Semarang, Jawa Tengah</p>
                </div>
                <a href="https://www.openstreetmap.org/#map=19/-6.995822/110.472230"
                   target="_blank" rel="noopener"
                   class="inline-flex shrink-0 items-center gap-1.5 rounded-full border border-[#e7e2d8] px-3 py-1.5 text-xs font-semibold text-[#374151] transition hover:border-[#c9a227] hover:text-[#c9a227]">
                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                    Buka Maps
                </a>
            </div>
            <x-leaflet-map height="300px" />
        </div>
    </div>
</section>

{{-- ── Testimoni ── --}}
<section class="mx-auto max-w-7xl px-4 py-20 sm:px-6 lg:px-8">
    <div class="flex items-end justify-between gap-4">
        <div>
            <p class="text-sm font-semibold uppercase tracking-[0.24em] text-[#c9a227]">Testimoni</p>
            <h2 class="mt-3 text-3xl font-semibold text-[#1f2937]">Para penghuni merasakan suasana yang tenang, nyaman, dan berkelas.</h2>
        </div>
    </div>
    <div class="mt-10 grid gap-6 lg:grid-cols-2">
        @foreach ($testimonials as $testimonial)
            <div class="rounded-[30px] border border-[#e7e2d8] bg-white p-8 shadow-sm">
                <p class="text-lg leading-8 text-[#4b5563]">"{{ $testimonial['quote'] }}"</p>
                <div class="mt-6">
                    <p class="font-semibold text-[#1f2937]">{{ $testimonial['name'] }}</p>
                    <p class="text-sm text-[#6b7280]">{{ $testimonial['role'] }}</p>
                </div>
            </div>
        @endforeach
    </div>
</section>

{{-- ── FAQ ── --}}
<section class="mx-auto max-w-7xl px-4 pb-24 sm:px-6 lg:px-8">
    <div class="rounded-[36px] border border-[#e7e2d8] bg-white p-8 shadow-sm lg:p-12">
        <div class="max-w-2xl">
            <p class="text-sm font-semibold uppercase tracking-[0.24em] text-[#c9a227]">FAQ</p>
            <h2 class="mt-3 text-3xl font-semibold text-[#1f2937]">Pertanyaan yang sering diajukan, dijawab dengan jelas.</h2>
        </div>
        <div class="mt-10 space-y-4">
            @foreach ($faq as $item)
                <details class="rounded-[24px] border border-[#e7e2d8] bg-[#faf8f5] p-5">
                    <summary class="cursor-pointer text-base font-semibold text-[#1f2937]">{{ $item['question'] }}</summary>
                    <p class="mt-3 text-sm leading-7 text-[#4b5563]">{{ $item['answer'] }}</p>
                </details>
            @endforeach
        </div>
    </div>
</section>

@endsection

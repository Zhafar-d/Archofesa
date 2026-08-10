@extends('layouts.app')

@section('title', 'Kamar · ARCHOFESA KOST')

@section('content')
<section class="mx-auto max-w-7xl px-4 py-20 sm:px-6 lg:px-8">
    <div class="max-w-3xl">
        <p class="text-sm font-semibold uppercase tracking-[0.24em] text-blue-600">Kamar</p>
        <h1 class="mt-4 text-4xl font-semibold tracking-tight text-slate-900 sm:text-5xl">Dua pilihan kamar terbaik untuk berbagai gaya hidup.</h1>
        <p class="mt-6 text-lg leading-8 text-slate-600">Pilih antara hunian keluarga yang luas atau tempat tinggal fokus untuk mahasiswa.</p>
    </div>

    <div class="mt-12 grid gap-8 lg:grid-cols-2">
        @forelse ($rooms as $room)
            <article class="overflow-hidden rounded-[32px] border border-slate-200 bg-white shadow-sm">
                <img src="{{ $room->image_url }}" alt="Kamar {{ $room->room_code }}" class="aspect-[4/5] w-full object-cover">
                <div class="p-8">
                    <div class="flex items-center justify-between gap-3">
                        <h2 class="text-2xl font-semibold text-slate-900">{{ $room->price_monthly >= 1400000 ? 'Family Room' : 'Student Room' }} ({{ $room->room_code }})</h2>
                        <span class="rounded-full bg-blue-50 px-3 py-1 text-sm font-semibold text-blue-700">Rp{{ number_format($room->price_monthly, 0, ',', '.') }}/bln</span>
                    </div>
                    <p class="mt-4 text-sm leading-7 text-slate-600">{{ $room->description ?? 'Nikmati kenyamanan tinggal di kamar dengan fasilitas lengkap dan suasana yang mendukung.' }}</p>
                    <div class="mt-6 flex items-center justify-between text-sm text-slate-500">
                        <span>{{ $room->size }}</span>
                        <a href="{{ route('room-detail', ['code' => $room->room_code]) }}" class="font-semibold text-blue-600 transition hover:text-blue-700">Lihat Detail</a>
                    </div>
                </div>
            </article>
        @empty
            <div class="col-span-full rounded-2xl border border-dashed border-slate-200 bg-slate-50 p-12 text-center">
                <h3 class="mt-4 text-lg font-semibold text-slate-900">Belum ada kamar</h3>
                <p class="mt-2 text-slate-600">Admin belum menambahkan daftar kamar ke dalam sistem.</p>
            </div>
        @endforelse
    </div>
</section>
@endsection

@extends('layouts.dashboard-app')

@section('title', 'Kamar Tersedia · ARCHOFESA KOST')

@section('content')
<div class="px-4 py-6 sm:px-6 lg:px-8 lg:py-8">
    <div class="mx-auto max-w-7xl">
        <div class="mb-8">
            <div class="inline-flex items-center gap-2 rounded-full bg-[#faf8f5] px-3.5 py-1 text-xs font-semibold uppercase tracking-wider text-[#c9a227] border border-[#e7e2d8]">
                <span>Katalog Unit</span>
            </div>
            <h1 class="mt-3 text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">Kamar Tersedia</h1>
            <p class="mt-1 text-sm text-slate-500">Temukan dan ajukan booking kamar impian Anda secara langsung</p>
        </div>

        <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
            @forelse ($rooms as $room)
                @php
                    $available = $room->is_available ?? ($room->status === 'available');
                    $roomType = $room->price_monthly >= 1400000 ? 'Family Room' : 'Student Room';
                @endphp
                <div class="flex flex-col justify-between rounded-3xl border border-[#e7e2d8] bg-white p-6 shadow-sm transition hover:border-[#c9a227]/60 hover:shadow-md">
                    <div>
                        <!-- Header Unit -->
                        <div class="flex items-start justify-between gap-2">
                            <div>
                                <span class="text-xs font-semibold text-[#c9a227]">{{ $roomType }}</span>
                                <h2 class="mt-0.5 text-lg font-bold text-slate-900">Kamar {{ $room->room_code }}</h2>
                            </div>
                            <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $available ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-100 text-slate-500 border border-slate-200' }}">
                                {{ $available ? 'Tersedia' : 'Terisi' }}
                            </span>
                        </div>

                        <!-- Spesifikasi -->
                        <div class="mt-4 flex flex-wrap gap-1.5 text-[11px]">
                            <span class="rounded-lg border border-slate-200 bg-slate-50 px-2 py-1 font-medium text-slate-700">
                                {{ $room->size ?: '3 x 4 m' }}
                            </span>
                            <span class="rounded-lg border border-slate-200 bg-slate-50 px-2 py-1 font-medium text-slate-700">
                                KM Dalam
                            </span>
                            <span class="rounded-lg border border-slate-200 bg-slate-50 px-2 py-1 font-medium text-slate-700">
                                WiFi
                            </span>
                        </div>

                        <!-- Deskripsi Singkat -->
                        <p class="mt-3 text-xs text-slate-600 leading-relaxed">
                            {{ $room->description ?: 'Kamar nyaman dan bersih siap huni dengan fasilitas lengkap.' }}
                        </p>
                    </div>

                    <!-- Footer Tarif & Aksi -->
                    <div class="mt-6 border-t border-slate-100 pt-4">
                        <div class="mb-3">
                            <span class="text-xs text-slate-400">Tarif Bulanan</span>
                            <p class="text-lg font-extrabold text-slate-900">Rp{{ number_format($room->price_monthly, 0, ',', '.') }}<span class="text-xs font-normal text-slate-500">/bln</span></p>
                        </div>

                        <div class="flex items-center gap-2">
                            <a href="{{ route('room-detail', ['code' => $room->room_code]) }}"
                               class="flex-1 rounded-xl border border-slate-200 bg-white py-2 text-center text-xs font-semibold text-slate-700 transition hover:bg-slate-50 hover:border-slate-300">
                                Detail
                            </a>
                            @if ($available)
                                <a href="{{ route('booking') }}"
                                   class="flex-1 rounded-xl bg-[#c9a227] py-2 text-center text-xs font-bold text-white shadow-sm transition hover:bg-[#b68d1f]">
                                    Booking
                                </a>
                            @else
                                <button disabled class="flex-1 rounded-xl bg-slate-100 py-2 text-center text-xs font-semibold text-slate-400 cursor-not-allowed">
                                    Penuh
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full rounded-3xl border border-dashed border-slate-200 bg-white p-12 text-center">
                    <p class="text-base font-bold text-slate-900">Belum ada kamar</p>
                    <p class="mt-1 text-xs text-slate-500">Daftar kamar belum tersedia di dalam sistem.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection

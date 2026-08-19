@extends('layouts.app')

@section('title', 'Daftar Kamar · ARCHOFESA KOST')

@section('content')
<div class="min-h-[calc(100vh-4rem)] bg-slate-50 py-10 sm:py-14">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        
        <!-- Header Page -->
        <div class="max-w-3xl">
            <div class="inline-flex items-center gap-2 rounded-full bg-[#faf8f5] px-3.5 py-1 text-xs font-semibold uppercase tracking-wider text-[#c9a227] border border-[#e7e2d8]">
                <span>Pilihan Kamar</span>
            </div>
            <h1 class="mt-3 text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl lg:text-4xl">
                Daftar Kamar ARCHOFESA KOST
            </h1>
            <p class="mt-2 text-sm sm:text-base leading-relaxed text-slate-600">
                Pilih unit kamar yang tersedia sesuai kebutuhan Anda. Semua kamar dirancang dengan standar kenyamanan tinggi, privasi terjaga, dan fasilitas lengkap siap huni.
            </p>
        </div>

        <!-- Grid Kamar (Tanpa Foto, Deskripsi Bersih & Responsif) -->
        <div class="mt-8 sm:mt-10 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
            @forelse ($rooms as $room)
                @php
                    $isAvailable = $room->is_available ?? ($room->status === 'available');
                    $roomType = $room->price_monthly >= 1400000 ? 'Family Room' : 'Student Room';
                @endphp
                <article class="flex flex-col justify-between rounded-3xl border border-[#e7e2d8] bg-white p-6 shadow-sm transition-all duration-200 hover:border-[#c9a227]/60 hover:shadow-md">
                    
                    <div>
                        <!-- Header Kartu: Judul & Badge Ketersediaan -->
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <span class="text-xs font-semibold uppercase tracking-wider text-[#c9a227]">{{ $roomType }}</span>
                                <h2 class="mt-1 text-xl font-bold text-slate-900">Kamar {{ $room->room_code }}</h2>
                            </div>
                            <span class="inline-flex shrink-0 items-center rounded-full px-3 py-1 text-xs font-semibold {{ $isAvailable ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-100 text-slate-500 border border-slate-200' }}">
                                {{ $isAvailable ? 'Tersedia' : 'Terisi' }}
                            </span>
                        </div>

                        <!-- Deskripsi Kamar -->
                        <p class="mt-3 text-xs sm:text-sm leading-relaxed text-slate-600">
                            {{ $room->description ?? 'Kamar kos nyaman dan tenang khusus mahasiswa dan pekerja. Dilengkapi perabotan lengkap siap huni.' }}
                        </p>

                        <!-- Fasilitas & Spesifikasi Chips -->
                        <div class="mt-4 flex flex-wrap gap-1.5 sm:gap-2">
                            <span class="rounded-lg border border-slate-200 bg-slate-50 px-2.5 py-1 text-[11px] font-medium text-slate-700">
                                Ukuran {{ $room->size ?: '3 x 4 m' }}
                            </span>
                            <span class="rounded-lg border border-slate-200 bg-slate-50 px-2.5 py-1 text-[11px] font-medium text-slate-700">
                                KM Dalam
                            </span>
                            <span class="rounded-lg border border-slate-200 bg-slate-50 px-2.5 py-1 text-[11px] font-medium text-slate-700">
                                Listrik & Air
                            </span>
                            <span class="rounded-lg border border-slate-200 bg-slate-50 px-2.5 py-1 text-[11px] font-medium text-slate-700">
                                WiFi Gratis
                            </span>
                        </div>
                    </div>

                    <!-- Footer Kartu: Harga & Tombol Aksi -->
                    <div class="mt-6 border-t border-slate-100 pt-4">
                        <div class="flex items-baseline justify-between mb-4">
                            <span class="text-xs text-slate-500">Tarif Sewa</span>
                            <p class="text-lg sm:text-xl font-extrabold text-slate-900">
                                Rp{{ number_format($room->price_monthly, 0, ',', '.') }}
                                <span class="text-xs font-normal text-slate-500">/ bulan</span>
                            </p>
                        </div>

                        <div class="flex items-center gap-2">
                            <a href="{{ route('room-detail', ['code' => $room->room_code]) }}" 
                               class="flex-1 rounded-xl border border-slate-200 bg-white py-2.5 text-center text-xs font-semibold text-slate-700 transition hover:bg-slate-50 hover:border-slate-300">
                                Detail
                            </a>
                            @if ($isAvailable)
                                <a href="{{ route('booking') }}" 
                                   class="flex-1 rounded-xl bg-[#c9a227] py-2.5 text-center text-xs font-bold text-white shadow-sm transition hover:bg-[#b68d1f]">
                                    Booking
                                </a>
                            @else
                                <button disabled class="flex-1 rounded-xl bg-slate-100 py-2.5 text-center text-xs font-semibold text-slate-400 cursor-not-allowed">
                                    Penuh
                                </button>
                            @endif
                        </div>
                    </div>

                </article>
            @empty
                <div class="col-span-full rounded-3xl border border-dashed border-slate-200 bg-white p-12 text-center">
                    <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-[#c9a227]/10 text-[#c9a227]">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                    <h3 class="mt-3 text-base font-bold text-slate-900">Belum Ada Daftar Kamar</h3>
                    <p class="mt-1 text-xs text-slate-500">Daftar kamar saat ini belum tersedia di sistem.</p>
                </div>
            @endforelse
        </div>

    </div>
</div>
@endsection

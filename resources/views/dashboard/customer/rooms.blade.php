@extends('layouts.dashboard-app')

@section('title', 'Kamar Tersedia · ARCHOFESA KOST')

@section('content')
<div class="px-6 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-[#1f2937]">Kamar Tersedia</h1>
        <p class="mt-2 text-[#6b7280]">Temukan dan booking kamar impian Anda</p>
    </div>

    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
        @forelse ($rooms as $room)
            @php
                $available = $room->is_available;
            @endphp
            <div class="flex flex-col rounded-[24px] border {{ $available ? 'border-[#e7e2d8] bg-white' : 'border-slate-100 bg-slate-50' }} p-6 shadow-sm transition hover:shadow-md">

                {{-- Status badge --}}
                <div class="flex items-center justify-between gap-2">
                    <span class="text-sm font-semibold text-[#1f2937]">Kamar {{ $room->room_code }}</span>
                    <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $available ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                        {{ $available ? 'Tersedia' : 'Terisi' }}
                    </span>
                </div>

                {{-- Info --}}
                <div class="mt-4 space-y-2 text-sm text-[#4b5563]">
                    <div class="flex items-center justify-between">
                        <span class="text-[#6b7280]">Ukuran</span>
                        <span class="font-medium text-[#1f2937]">{{ $room->size }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-[#6b7280]">Fasilitas</span>
                        <span class="font-medium text-[#1f2937]">AC · WiFi · Toilet</span>
                    </div>
                </div>

                {{-- Harga --}}
                <div class="mt-4 border-t border-[#e7e2d8] pt-4">
                    <p class="text-xl font-bold text-[#1f2937]">Rp{{ number_format($room->price_monthly, 0, ',', '.') }}<span class="text-sm font-normal text-[#6b7280]">/bln</span></p>
                </div>

                {{-- Tombol --}}
                <div class="mt-4 flex gap-2">
                    <a href="{{ route('room-detail', ['code' => $room->room_code]) }}"
                       class="flex-1 rounded-full border border-[#e7e2d8] px-3 py-2 text-center text-xs font-semibold text-[#374151] transition hover:border-[#c9a227] hover:text-[#c9a227]">
                        Detail
                    </a>
                    @if ($available)
                        <a href="{{ route('booking') }}"
                           class="flex-1 rounded-full bg-[#c9a227] px-3 py-2 text-center text-xs font-semibold text-white transition hover:bg-[#b68d1f]">
                            Booking
                        </a>
                    @endif
                </div>
            </div>
        @empty
            <div class="col-span-full rounded-2xl border border-dashed border-slate-200 bg-slate-50 p-12 text-center">
                <p class="text-lg font-semibold text-slate-900">Belum ada kamar</p>
                <p class="mt-2 text-slate-600">Admin belum menambahkan daftar kamar ke dalam sistem.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection

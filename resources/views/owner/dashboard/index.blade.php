@extends('layouts.owner')

@section('title', 'Dashboard Pemilik · ARCHOFESA KOST')

@section('content')
<div class="px-6 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-slate-900">Dashboard Pemilik</h1>
        <p class="mt-2 text-slate-600">Ringkasan hanya-baca dan konfirmasi kamar siap huni.</p>
    </div>

    <div class="grid gap-6 lg:grid-cols-3">
        <x-dashboard.stat-card title="Kamar Terisi" value="{{ $occupiedRooms }}/{{ $totalRooms }}" detail="Jumlah kamar yang sedang dihuni" />
        <x-dashboard.stat-card title="Pendapatan Bulan Ini" value="Rp{{ number_format($monthlyRevenue, 0, ',', '.') }}" detail="Total pembayaran masuk" />
        <x-dashboard.stat-card title="Menunggu Konfirmasi" value="{{ $pendingConfirmations->count() }}" detail="Booking siap huni yang perlu dikonfirmasi" />
    </div>

    <div class="mt-6">
        <a href="{{ route('owner.chat') }}" class="inline-flex items-center rounded-full bg-blue-600 px-5 py-3 text-sm font-semibold text-white hover:bg-blue-700">Buka Chat Realtime dengan Admin</a>
    </div>

    <div class="mt-8 grid gap-6 lg:grid-cols-[1.2fr_0.8fr]">
        <x-dashboard.section title="Peta Kamar" subtitle="Visual status 18 kamar">
            <div class="grid grid-cols-3 gap-4">
                @foreach ($roomStatuses as $room)
                    @php
                        $color = match ($room['status']) {
                            'available' => 'bg-green-100 text-green-700',
                            'occupied' => 'bg-blue-100 text-blue-700',
                            default => 'bg-yellow-100 text-yellow-700',
                        };
                    @endphp
                    <div class="rounded-3xl border border-[#e7e2d8] bg-white p-4 text-center shadow-sm">
                        <div class="text-sm font-semibold text-slate-900">{{ $room['code'] }}</div>
                        <div class="mt-2 rounded-2xl px-3 py-1 text-xs font-semibold {{ $color }}">{{ ucfirst($room['status']) }}</div>
                    </div>
                @endforeach
            </div>
        </x-dashboard.section>

        <x-dashboard.section title="Menunggu Konfirmasi Anda" subtitle="Booking yang statusnya menunggu konfirmasi pemilik">
            <div class="space-y-4">
                @forelse ($pendingConfirmations as $booking)
                    <div class="rounded-[28px] border border-[#e7e2d8] bg-[#faf8f5] p-5">
                        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                            <div>
                                <p class="text-sm font-semibold text-[#6b7280]">#{{ $booking->id }} · {{ $booking->user->name }}</p>
                                <p class="mt-2 text-xl font-semibold text-[#1f2937]">{{ $booking->room_code ?? ($booking->room->room_code ?? 'Kamar belum ditentukan') }}</p>
                                <p class="mt-2 text-sm text-[#4b5563]">Masuk: {{ optional($booking->move_in_date)->format('d M Y') }} · Keluar: {{ optional($booking->move_out_date)->format('d M Y') }}</p>
                            </div>
                            <a href="{{ route('owner.konfirmasi.index') }}" class="rounded-full bg-[#c9a227] px-5 py-3 text-sm font-semibold text-white transition hover:bg-[#b68d1f]">Lihat Semua</a>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-slate-600">Tidak ada booking yang menunggu konfirmasi.</p>
                @endforelse
            </div>
        </x-dashboard.section>
    </div>
</div>
@endsection

@extends('layouts.dashboard-app')

@section('title', 'Dashboard Owner · ARCHOFESA KOST')

@section('content')
<div class="px-6 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-[#1f2937]">Dashboard Owner</h1>
        <p class="mt-2 text-[#6b7280]">Ringkasan performa properti Anda</p>
    </div>

    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-5">
        <x-dashboard.stat-card title="Total Kamar"      value="{{ $totalRooms }}"                                   detail="Semua unit" />
        <x-dashboard.stat-card title="Kamar Terisi"     value="{{ $occupiedRooms }}"                                detail="{{ $totalRooms > 0 ? round(($occupiedRooms / $totalRooms) * 100) : 0 }}% hunian" />
        <x-dashboard.stat-card title="Kamar Tersedia"   value="{{ $availableRooms }}"                               detail="Siap disewa" />
        <x-dashboard.stat-card title="Pendapatan Bulan" value="Rp{{ number_format($monthlyRevenue, 0, ',', '.') }}" detail="Dari booking terbayar" />
        <x-dashboard.stat-card title="Menunggu Review"  value="{{ $pendingBookings }}"                              detail="Perlu tindakan" />
    </div>

    <div class="mt-8 grid gap-6 lg:grid-cols-2">
        <x-dashboard.section title="Booking Terbaru" subtitle="Reservasi terkini">
            <div class="space-y-4">
                @forelse ($bookings as $booking)
                    <div class="flex items-center justify-between border-b border-[#e7e2d8] pb-4 last:border-0">
                        <div>
                            <p class="font-medium text-[#1f2937]">{{ $booking->room_code ?? 'Belum ditentukan' }} — {{ $booking->user->name ?? 'Tamu' }}</p>
                            <p class="text-xs text-[#6b7280]">{{ optional($booking->move_in_date)->format('d M Y') ?? 'Tanggal masuk belum ditentukan' }}</p>
                            <p class="mt-1 text-xs text-[#9ca3af]">{{ $booking->notes ?: 'Tidak ada catatan.' }}</p>
                        </div>
                        <span class="inline-flex rounded-full {{ $booking->status === 'pending' ? 'bg-yellow-100 text-yellow-700' : 'bg-green-100 text-green-700' }} px-3 py-1 text-xs font-semibold">
                            {{ ucfirst(str_replace('_', ' ', $booking->status)) }}
                        </span>
                    </div>
                @empty
                    <p class="text-sm text-[#6b7280]">Belum ada booking.</p>
                @endforelse
            </div>
        </x-dashboard.section>

        <x-dashboard.section title="Check-out Mendatang" subtitle="Penghuni yang akan segera keluar">
            <div class="space-y-4">
                @php
                    $checkouts = $bookings->filter(fn ($b) => $b->move_out_date !== null);
                @endphp

                @forelse ($checkouts as $checkout)
                    <div class="rounded-[16px] border border-yellow-100 bg-yellow-50 p-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="font-medium text-[#1f2937]">{{ $checkout->room_code ?? 'Belum ditentukan' }} — {{ $checkout->user->name ?? 'Tamu' }}</p>
                                <p class="text-sm text-[#6b7280]">Check-out: {{ optional($checkout->move_out_date)->format('d M Y') }}</p>
                            </div>
                            <span class="rounded-full bg-yellow-100 px-3 py-1 text-xs font-semibold text-yellow-700">Segera</span>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-[#6b7280]">Tidak ada check-out yang akan datang.</p>
                @endforelse
            </div>
        </x-dashboard.section>
    </div>

    <div class="mt-8 grid gap-6 lg:grid-cols-2">
        <x-dashboard.section title="Aksi Cepat">
            <div class="grid gap-3">
                <a href="{{ route('owner.konfirmasi.index') }}" class="block w-full rounded-[16px] border border-[#e7e2d8] px-4 py-3 text-sm font-medium text-[#374151] transition hover:border-[#c9a227] hover:text-[#c9a227]">Konfirmasi Booking</a>
                <a href="{{ route('owner.laporan.index') }}"   class="block w-full rounded-[16px] border border-[#e7e2d8] px-4 py-3 text-sm font-medium text-[#374151] transition hover:border-[#c9a227] hover:text-[#c9a227]">Lihat Laporan</a>
                <a href="{{ route('owner.chat') }}"            class="block w-full rounded-[16px] border border-[#e7e2d8] px-4 py-3 text-sm font-medium text-[#374151] transition hover:border-[#c9a227] hover:text-[#c9a227]">Chat dengan Admin</a>
            </div>
        </x-dashboard.section>

        <x-dashboard.section title="Performa" subtitle="Bulan ini">
            <div class="space-y-6">
                @php
                    $occupancyPct = $totalRooms > 0 ? round(($occupiedRooms / $totalRooms) * 100) : 0;
                @endphp
                <div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-[#374151]">Tingkat Hunian</span>
                        <span class="font-bold text-[#1f2937]">{{ $occupancyPct }}%</span>
                    </div>
                    <div class="mt-2 h-2 overflow-hidden rounded-full bg-[#e7e2d8]">
                        <div class="h-full rounded-full bg-[#c9a227] transition-all" style="width: {{ $occupancyPct }}%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-[#374151]">Pendapatan Terkumpul</span>
                        <span class="font-bold text-[#1f2937]">Rp{{ number_format($monthlyRevenue, 0, ',', '.') }}</span>
                    </div>
                    <div class="mt-2 h-2 overflow-hidden rounded-full bg-[#e7e2d8]">
                        <div class="h-full w-full rounded-full bg-green-500"></div>
                    </div>
                </div>
            </div>
        </x-dashboard.section>
    </div>
</div>
@endsection

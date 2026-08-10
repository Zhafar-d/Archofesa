@extends('layouts.dashboard-app')

@section('title', 'Booking Saya · ARCHOFESA KOST')

@section('content')
<div class="px-6 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-slate-900">Booking Saya</h1>
        <p class="mt-2 text-slate-600">Lihat dan kelola reservasi Anda</p>
    </div>

    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <input type="text" placeholder="Cari booking..." class="w-full rounded-lg border border-slate-200 bg-slate-50 px-4 py-2">
        <select class="rounded-lg border border-slate-200 bg-white px-4 py-2">
            <option>Semua Booking</option>
            <option>Aktif</option>
            <option>Selesai</option>
            <option>Dibatalkan</option>
        </select>
    </div>

    <div class="space-y-6">
        @forelse ($bookings as $booking)
            @php
                $progress = 0;
                if ($booking->move_in_date && $booking->move_out_date) {
                    $totalDays = $booking->move_in_date->diffInDays($booking->move_out_date);
                    $daysPassed = $booking->move_in_date->diffInDays(now());
                    
                    if (now() < $booking->move_in_date) {
                        $progress = 0;
                    } elseif (now() > $booking->move_out_date) {
                        $progress = 100;
                    } else {
                        $progress = $totalDays > 0 ? round(($daysPassed / $totalDays) * 100) : 0;
                    }
                }
                
                $roomType = $booking->room && $booking->room->price_monthly >= 1400000 ? 'Family Room' : 'Student Room';
            @endphp
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="flex flex-col items-start justify-between gap-4 sm:flex-row sm:items-center">
                    <div>
                        <p class="text-sm font-medium text-slate-600">Booking #{{ str_pad($booking->id, 5, '0', STR_PAD_LEFT) }}</p>
                        <h3 class="mt-1 text-xl font-bold text-slate-900">Kamar {{ $booking->room_code }} - {{ $roomType }}</h3>
                        <div class="mt-2 flex flex-wrap gap-6 text-sm text-slate-600">
                            <span>Tanggal Masuk: <span class="font-semibold text-slate-900">{{ optional($booking->move_in_date)->format('d M Y') ?? 'Menunggu' }}</span></span>
                            <span>Tanggal Keluar: <span class="font-semibold text-slate-900">{{ optional($booking->move_out_date)->format('d M Y') ?? 'Menunggu' }}</span></span>
                        </div>
                    </div>
                    @php
                        $statusLabel = match($booking->status) {
                            'menunggu_pembayaran'       => 'Menunggu Pembayaran',
                            'dibayar'                  => 'Sudah Dibayar',
                            'menunggu_konfirmasi_owner'=> 'Menunggu Konfirmasi',
                            'siap_huni'                => 'Siap Dihuni',
                            'dihuni'                   => 'Sedang Dihuni',
                            'selesai'                  => 'Selesai',
                            'dibatalkan'               => 'Dibatalkan',
                            default                    => ucfirst($booking->status),
                        };
                        $statusColor = match($booking->status) {
                            'dihuni', 'siap_huni', 'dibayar' => 'bg-green-100 text-green-700',
                            'menunggu_pembayaran', 'menunggu_konfirmasi_owner' => 'bg-yellow-100 text-yellow-700',
                            'dibatalkan' => 'bg-red-100 text-red-700',
                            default => 'bg-slate-100 text-slate-700',
                        };
                    @endphp
                    <span class="inline-flex rounded-full {{ $statusColor }} px-4 py-2 text-sm font-semibold">
                        {{ $statusLabel }}
                    </span>
                </div>

                <div class="mt-4 border-t border-slate-100 pt-4">
                    <div class="mb-3 flex items-center justify-between">
                        <span class="text-sm font-medium text-slate-600">Progres Booking</span>
                        <span class="text-sm font-semibold text-slate-900">{{ $progress }}%</span>
                    </div>
                    <div class="h-2 overflow-hidden rounded-full bg-slate-100">
                        <div class="h-full bg-gradient-to-r from-blue-500 to-cyan-500 transition" style="width: {{ $progress }}%"></div>
                    </div>
                </div>

                <div class="mt-4 flex items-center justify-between">
                    <p class="text-2xl font-bold text-slate-900">Rp{{ number_format($booking->monthly_rate, 0, ',', '.') }}/bln</p>
                    <div class="flex gap-3">
                        <a href="{{ route('customer.payments') }}"
                           class="rounded-lg border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                            Lihat Detail
                        </a>
                        @if (in_array($booking->status, ['dibayar', 'siap_huni', 'dihuni', 'menunggu_konfirmasi_owner']))
                            <a href="{{ route('booking.bukti', $booking) }}"
                               class="rounded-lg border border-[#c9a227] px-4 py-2 text-sm font-semibold text-[#c9a227] hover:bg-[#faf8f5]">
                                🖨️ Bukti
                            </a>
                        @endif
                        @if ($booking->status === 'dihuni')
                            <a href="{{ route('customer.extend.form', $booking) }}"
                               class="rounded-lg bg-[#c9a227] px-4 py-2 text-sm font-semibold text-white hover:bg-[#b68d1f]">
                                Perpanjang Sewa
                            </a>
                        @endif
                        @if ($booking->status === 'menunggu_pembayaran')
                            <a href="{{ route('customer.payments') }}"
                               class="rounded-lg bg-[#c9a227] px-4 py-2 text-sm font-semibold text-white hover:bg-[#b68d1f]">
                                Bayar Sekarang
                            </a>
                        @endif
                        @if (!in_array($booking->status, ['dibatalkan', 'selesai']))
                            <form method="POST" action="{{ route('booking.cancel', $booking) }}" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan booking ini?');" class="inline">
                                @csrf
                                <button type="submit" class="rounded-lg border border-red-200 bg-red-50 px-4 py-2 text-sm font-semibold text-red-600 transition hover:bg-red-100 hover:text-red-700">
                                    Batalin Booking
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full rounded-2xl border border-dashed border-slate-200 bg-slate-50 p-12 text-center">
                <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-[#c9a227]/10 text-[#c9a227]">
                    <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                </div>
                <h3 class="mt-4 text-lg font-semibold text-slate-900">Kamu belum punya booking aktif</h3>
                <p class="mt-2 text-slate-600">Silakan temukan kamar impianmu dan mulai pengalaman ngekos yang baru.</p>
                <div class="mt-6">
                    <a href="{{ route('customer.rooms') }}" class="inline-flex items-center rounded-full bg-[#c9a227] px-6 py-3 text-sm font-semibold text-white transition hover:bg-[#b68d1f]">Booking Kamar Sekarang</a>
                </div>
            </div>
        @endforelse
    </div>
</div>
@endsection

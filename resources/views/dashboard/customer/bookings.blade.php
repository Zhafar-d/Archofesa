@extends('layouts.dashboard-app')

@section('title', 'Booking Saya · ARCHOFESA KOST')

@section('content')
<div class="px-4 py-6 sm:px-6 lg:px-8">
    <div class="mx-auto max-w-5xl">
        <!-- Header -->
        <div class="mb-6 sm:mb-8">
            <div class="inline-flex items-center gap-2 rounded-full bg-[#faf8f5] px-3.5 py-1 text-xs font-semibold uppercase tracking-wider text-[#c9a227] border border-[#e7e2d8]">
                <span>Riwayat Reservasi</span>
            </div>
            <h1 class="mt-3 text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">Booking Saya</h1>
            <p class="mt-1 text-xs sm:text-sm text-slate-500">Pantau perkembangan status pemesanan dan masa tinggal Anda</p>
        </div>

        <div class="space-y-5">
            @forelse ($bookings as $booking)
                @php
                    $progress = 0;
                    $remainingDays = null;
                    $isExpiringSoon = false;
                    $isExpiredLease = false;

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

                        $remainingDays = now()->startOfDay()->diffInDays($booking->move_out_date->startOfDay(), false);
                        $isExpiringSoon = $remainingDays !== null && $remainingDays <= 7 && $remainingDays >= 0 && $booking->status === 'dihuni';
                        $isExpiredLease = $remainingDays !== null && $remainingDays < 0 && $booking->status === 'dihuni';
                    }
                    
                    $roomType = $booking->room && $booking->room->price_monthly >= 1400000 ? 'Family Room' : 'Student Room';

                    $statusLabel = match($booking->status) {
                        'pending'                   => 'Menunggu Verifikasi Admin',
                        'menunggu_pembayaran'       => 'Menunggu Pembayaran',
                        'dibayar'                  => 'Sudah Dibayar',
                        'menunggu_konfirmasi_owner'=> 'Menunggu Konfirmasi',
                        'siap_huni'                => 'Siap Dihuni',
                        'dihuni'                   => 'Sedang Dihuni',
                        'selesai'                  => 'Selesai',
                        'dibatalkan'               => 'Dibatalkan',
                        default                    => ucfirst($booking->status),
                    };

                    $statusBadge = match($booking->status) {
                        'dihuni', 'siap_huni', 'dibayar' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                        'pending', 'menunggu_pembayaran', 'menunggu_konfirmasi_owner' => 'bg-amber-50 text-amber-700 border-amber-200',
                        'dibatalkan' => 'bg-rose-50 text-rose-700 border-rose-200',
                        default => 'bg-slate-100 text-slate-700 border-slate-200',
                    };
                @endphp

                <div class="rounded-3xl border border-[#e7e2d8] bg-white p-5 sm:p-7 shadow-sm transition hover:border-[#c9a227]/50">
                    
                    <!-- Header Card -->
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between border-b border-slate-100 pb-4">
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="text-xs font-semibold text-slate-400">#BK-{{ str_pad($booking->id, 5, '0', STR_PAD_LEFT) }}</span>
                                <span class="text-slate-300">&middot;</span>
                                <span class="text-xs font-semibold text-[#c9a227]">{{ $roomType }}</span>
                            </div>
                            <h2 class="mt-1 text-lg sm:text-xl font-bold text-slate-900">Kamar {{ $booking->room_code }}</h2>
                        </div>
                        <span class="inline-flex self-start sm:self-center rounded-full border px-3 py-1 text-xs font-semibold {{ $statusBadge }}">
                            {{ $statusLabel }}
                        </span>
                    </div>

                    <!-- Peringatan H-7 Perpanjangan Masa Sewa -->
                    @if ($isExpiringSoon)
                        <div class="mt-4 rounded-2xl border border-amber-200 bg-amber-50/80 p-4">
                            <div class="flex items-start gap-3">
                                <svg class="h-5 w-5 shrink-0 text-amber-600 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                                </svg>
                                <div class="flex-1">
                                    <p class="text-xs font-bold text-amber-900">Masa Sewa Berakhir Dalam {{ $remainingDays }} Hari</p>
                                    <p class="mt-0.5 text-xs text-amber-700">Masa sewa Anda akan berakhir pada {{ optional($booking->move_out_date)->translatedFormat('d F Y') }}. Silakan ajukan perpanjangan sekarang agar kamar tetap menjadi hak sewa Anda.</p>
                                </div>
                            </div>
                        </div>
                    @elseif ($isExpiredLease)
                        <div class="mt-4 rounded-2xl border border-rose-200 bg-rose-50/80 p-4">
                            <p class="text-xs font-bold text-rose-900">Masa Sewa Telah Berakhir</p>
                            <p class="mt-0.5 text-xs text-rose-700">Tanggal masa sewa telah habis pada {{ optional($booking->move_out_date)->translatedFormat('d F Y') }}.</p>
                        </div>
                    @endif

                    <!-- Detail Tanggal & Sisa Waktu -->
                    <div class="mt-4 grid grid-cols-2 gap-3 sm:grid-cols-3 text-xs">
                        <div class="rounded-2xl border border-slate-100 bg-slate-50 p-3">
                            <span class="text-slate-400">Tanggal Masuk</span>
                            <p class="mt-0.5 font-bold text-slate-800">{{ optional($booking->move_in_date)->translatedFormat('d M Y') ?? 'Menunggu' }}</p>
                        </div>
                        <div class="rounded-2xl border border-slate-100 bg-slate-50 p-3">
                            <span class="text-slate-400">Tanggal Keluar</span>
                            <p class="mt-0.5 font-bold text-slate-800">{{ optional($booking->move_out_date)->translatedFormat('d M Y') ?? 'Menunggu' }}</p>
                        </div>
                        <div class="col-span-2 sm:col-span-1 rounded-2xl border border-slate-100 bg-slate-50 p-3">
                            <span class="text-slate-400">Sisa Masa Tinggal</span>
                            <p class="mt-0.5 font-bold {{ $isExpiringSoon ? 'text-amber-700' : 'text-slate-800' }}">
                                @if ($booking->status === 'dihuni' && $remainingDays !== null)
                                    {{ $remainingDays > 0 ? $remainingDays . ' Hari Lagi' : 'Hari Terakhir' }}
                                @else
                                    {{ ucfirst($booking->status) }}
                                @endif
                            </p>
                        </div>
                    </div>

                    <!-- Progress Bar Masa Sewa -->
                    @if ($booking->status === 'dihuni')
                        <div class="mt-4 border-t border-slate-100 pt-3">
                            <div class="mb-2 flex items-center justify-between text-xs">
                                <span class="text-slate-500">Masa Tinggal Berjalan</span>
                                <span class="font-bold text-slate-800">{{ $progress }}%</span>
                            </div>
                            <div class="h-2 overflow-hidden rounded-full bg-slate-100">
                                <div class="h-full bg-gradient-to-r from-[#c9a227] to-amber-500 transition-all duration-300" style="width: {{ $progress }}%"></div>
                            </div>
                        </div>
                    @endif

                    <!-- Footer: Harga & Tombol Aksi (Responsif HP & Desktop) -->
                    <div class="mt-5 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between border-t border-slate-100 pt-4">
                        <div>
                            <span class="text-xs text-slate-400">Tarif Bulanan</span>
                            <p class="text-xl sm:text-2xl font-extrabold text-slate-900">
                                Rp{{ number_format($booking->monthly_rate, 0, ',', '.') }}
                                <span class="text-xs font-normal text-slate-500">/ bulan</span>
                            </p>
                        </div>

                        <!-- Action Buttons Grid / Wrap -->
                        <div class="flex flex-wrap items-center gap-2">
                            <a href="{{ route('booking.status.detail', $booking) }}" 
                               class="flex-1 sm:flex-initial text-center rounded-xl bg-slate-900 px-4 py-2.5 text-xs font-bold text-white shadow-sm transition hover:bg-slate-800">
                                Lacak Status
                            </a>
                            
                            <a href="{{ route('customer.payments') }}" 
                               class="flex-1 sm:flex-initial text-center rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-xs font-semibold text-slate-700 transition hover:bg-slate-50 hover:border-slate-300">
                                Detail Tagihan
                            </a>

                            @if (in_array($booking->status, ['dibayar', 'siap_huni', 'dihuni', 'menunggu_konfirmasi_owner']))
                                <a href="{{ route('booking.bukti', $booking) }}" 
                                   class="flex-1 sm:flex-initial text-center rounded-xl border border-[#c9a227] bg-[#faf8f5] px-4 py-2.5 text-xs font-semibold text-[#c9a227] transition hover:bg-[#c9a227] hover:text-white">
                                    Bukti Booking
                                </a>
                            @endif

                            @if ($booking->status === 'dihuni')
                                <a href="{{ route('customer.extend.form', $booking) }}" 
                                   class="w-full sm:w-auto text-center rounded-xl bg-[#c9a227] px-4 py-2.5 text-xs font-bold text-white shadow-sm transition hover:bg-[#b68d1f]">
                                    Perpanjang Sewa
                                </a>
                            @endif

                            @if (!in_array($booking->status, ['dibatalkan', 'selesai', 'dihuni']))
                                <form method="POST" action="{{ route('booking.cancel', $booking) }}" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan booking ini?');" class="flex-1 sm:flex-initial">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-full text-center rounded-xl border border-rose-200 bg-rose-50 px-4 py-2.5 text-xs font-semibold text-rose-700 transition hover:bg-rose-100">
                                        Batalkan
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>

                </div>
            @empty
                <div class="rounded-3xl border border-dashed border-slate-200 bg-white p-12 text-center">
                    <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-[#c9a227]/10 text-[#c9a227]">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                    <h3 class="mt-3 text-base font-bold text-slate-900">Belum Ada Riwayat Pemesanan</h3>
                    <p class="mt-1 text-xs text-slate-500 max-w-sm mx-auto">Anda belum memiliki pemesanan kamar. Silakan pilih kamar yang sesuai dengan kebutuhan Anda.</p>
                    <div class="mt-5">
                        <a href="{{ route('customer.rooms') }}" class="inline-flex items-center gap-2 rounded-xl bg-[#c9a227] px-5 py-2.5 text-xs font-bold text-white shadow-sm transition hover:bg-[#b68d1f]">
                            <span>Pilih Kamar Sekarang</span>
                        </a>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection

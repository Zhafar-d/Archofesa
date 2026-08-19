@extends('layouts.app')

@section('title', 'Status Pemesanan · ARCHOFESA')

@section('content')
<div class="min-h-[calc(100vh-4rem)] bg-slate-50 py-10">
    <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">

        {{-- Flash Messages --}}
        @if (session('success'))
            <div class="mb-6 flex items-center gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-medium text-emerald-800 shadow-sm">
                <svg class="h-5 w-5 shrink-0 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if (session('error'))
            <div class="mb-6 flex items-center gap-3 rounded-2xl border border-rose-200 bg-rose-50 px-5 py-4 text-sm font-medium text-rose-800 shadow-sm">
                <svg class="h-5 w-5 shrink-0 text-rose-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                </svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        @if (session('info'))
            <div class="mb-6 flex items-center gap-3 rounded-2xl border border-blue-200 bg-blue-50 px-5 py-4 text-sm font-medium text-blue-800 shadow-sm">
                <svg class="h-5 w-5 shrink-0 text-blue-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                </svg>
                <span>{{ session('info') }}</span>
            </div>
        @endif

        @php
            // Hitung status stepper
            $status = $booking->status;
            $isCancelled = $status === 'dibatalkan';
            $isDone = in_array($status, ['dihuni', 'selesai']);

            // Status Level: 1 = Submitted, 2 = Waiting Payment / Verification, 3 = Owner Confirmation, 4 = Ready / Occupied
            $currentStep = 1;
            if (in_array($status, ['menunggu_pembayaran', 'dibayar'])) {
                $currentStep = 2;
            } elseif ($status === 'menunggu_konfirmasi_owner') {
                $currentStep = 3;
            } elseif (in_array($status, ['siap_huni', 'dihuni', 'selesai'])) {
                $currentStep = 4;
            }

            // Status header badge info
            $badgeColor = match($status) {
                'pending'                   => 'bg-amber-50 text-amber-700 border-amber-200',
                'menunggu_pembayaran'       => 'bg-amber-50 text-amber-700 border-amber-200',
                'dibayar'                  => 'bg-blue-50 text-blue-700 border-blue-200',
                'menunggu_konfirmasi_owner'=> 'bg-indigo-50 text-indigo-700 border-indigo-200',
                'siap_huni', 'dihuni'       => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                'selesai'                  => 'bg-slate-100 text-slate-700 border-slate-200',
                'dibatalkan'               => 'bg-rose-50 text-rose-700 border-rose-200',
                default                    => 'bg-slate-100 text-slate-700 border-slate-200',
            };

            $statusText = match($status) {
                'pending'                   => 'Menunggu Verifikasi Admin',
                'menunggu_pembayaran'       => 'Menunggu Pembayaran',
                'dibayar'                  => 'Pembayaran Berhasil',
                'menunggu_konfirmasi_owner'=> 'Menunggu Konfirmasi Pemilik',
                'siap_huni'                => 'Kamar Siap Dihuni',
                'dihuni'                   => 'Sedang Dihuni',
                'selesai'                  => 'Sewa Selesai',
                'dibatalkan'               => 'Pemesanan Dibatalkan',
                default                    => ucfirst($status),
            };

            $pendingPayment = $booking->payments->firstWhere('status', 'pending') 
                ?? $booking->payments->firstWhere('status', 'menunggu_pembayaran')
                ?? $booking->payments->firstWhere('status', 'menunggu');

            $paidPayment = $booking->payments->firstWhere('status', 'dibayar');

            // Durasi countdown batas bayar/verifikasi: 24 jam dari waktu booking
            $expiresAt = $booking->created_at ? $booking->created_at->copy()->addHours(24) : now()->addHours(24);
            $isExpired = now()->greaterThan($expiresAt) && in_array($status, ['pending', 'menunggu_pembayaran']);
        @endphp

        <!-- Top Header Bar -->
        <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <div class="flex items-center gap-3">
                    <h1 class="text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">Status Pemesanan</h1>
                    <span class="inline-flex items-center rounded-full border px-3 py-1 text-xs font-semibold {{ $badgeColor }}">
                        {{ $statusText }}
                    </span>
                </div>
                <p class="mt-1 text-sm text-slate-500">
                    ID Transaksi: <span class="font-mono font-semibold text-slate-800">#BK-{{ str_pad($booking->id, 5, '0', STR_PAD_LEFT) }}</span>
                    &middot; Diajukan pada {{ $booking->created_at ? $booking->created_at->translatedFormat('d F Y, H:i') : '-' }} WIB
                </p>
            </div>
            <div class="flex items-center gap-2">
                <button onclick="window.location.reload()" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-xs font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50 hover:border-slate-300">
                    <svg class="h-4 w-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                    </svg>
                    <span>Perbarui Status</span>
                </button>
                <a href="{{ route('customer.bookings') }}" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-xs font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50 hover:border-slate-300">
                    <span>Semua Booking</span>
                </a>
            </div>
        </div>

        <div class="grid gap-8 lg:grid-cols-12">
            
            {{-- Main Column (Stepper & Info) --}}
            <div class="space-y-6 lg:col-span-8">

                {{-- Countdown Banner (Hanya muncul jika status pending / menunggu pembayaran) --}}
                @if (in_array($status, ['pending', 'menunggu_pembayaran']) && !$isExpired)
                    <div class="rounded-3xl border border-[#e7e2d8] bg-white p-6 shadow-sm">
                        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                            <div class="space-y-1">
                                <div class="flex items-center gap-2 text-xs font-semibold uppercase tracking-wider text-[#c9a227]">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span>Batas Waktu Selesai</span>
                                </div>
                                <p class="text-sm text-slate-600">Selesaikan proses pembayaran sebelum batas waktu berakhir.</p>
                            </div>

                            {{-- Timer Box Segment --}}
                            <div class="flex items-center gap-2" id="countdown-container" data-target="{{ $expiresAt->toIso8601String() }}">
                                <div class="flex flex-col items-center justify-center rounded-2xl bg-slate-900 px-3.5 py-2 text-white shadow-inner min-w-[54px]">
                                    <span class="font-mono text-lg font-bold tracking-tight" id="timer-hours">00</span>
                                    <span class="text-[9px] font-medium uppercase tracking-wider text-slate-400">Jam</span>
                                </div>
                                <span class="font-mono text-lg font-bold text-slate-400">:</span>
                                <div class="flex flex-col items-center justify-center rounded-2xl bg-slate-900 px-3.5 py-2 text-white shadow-inner min-w-[54px]">
                                    <span class="font-mono text-lg font-bold tracking-tight" id="timer-minutes">00</span>
                                    <span class="text-[9px] font-medium uppercase tracking-wider text-slate-400">Menit</span>
                                </div>
                                <span class="font-mono text-lg font-bold text-slate-400">:</span>
                                <div class="flex flex-col items-center justify-center rounded-2xl bg-slate-900 px-3.5 py-2 text-white shadow-inner min-w-[54px]">
                                    <span class="font-mono text-lg font-bold tracking-tight" id="timer-seconds">00</span>
                                    <span class="text-[9px] font-medium uppercase tracking-wider text-slate-400">Detik</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @elseif ($isExpired)
                    <div class="flex items-center gap-3 rounded-2xl border border-amber-200 bg-amber-50 p-5 text-sm text-amber-800">
                        <svg class="h-5 w-5 shrink-0 text-amber-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                        </svg>
                        <div>
                            <p class="font-semibold">Batas Waktu Pembayaran Telah Berakhir</p>
                            <p class="mt-0.5 text-xs text-amber-700">Silakan batalkan pesanan ini untuk mengajukan pemesanan kamar kembali.</p>
                        </div>
                    </div>
                @endif

                {{-- Live Tracking Stepper Card (Gojek-style Tracker) --}}
                <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
                    <div class="mb-6 flex items-center justify-between border-b border-slate-100 pb-4">
                        <div>
                            <h2 class="text-lg font-bold text-slate-900">Alur Proses Reservasi</h2>
                            <p class="text-xs text-slate-500">Pantau perkembangan status pemesanan kamar Anda secara real-time</p>
                        </div>
                        <span class="rounded-full bg-slate-100 px-3 py-1 font-mono text-xs font-semibold text-slate-600">
                            Langkah {{ $isCancelled ? '0' : $currentStep }} dari 4
                        </span>
                    </div>

                    @if ($isCancelled)
                        <div class="rounded-2xl border border-rose-100 bg-rose-50/70 p-6 text-center">
                            <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-rose-100 text-rose-600">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </div>
                            <h3 class="mt-3 text-base font-bold text-rose-900">Pemesanan Telah Dibatalkan</h3>
                            <p class="mt-1 text-xs text-rose-600 max-w-md mx-auto">Kamar telah dibuka kembali untuk calon penghuni lain. Anda dapat melakukan booking kamar baru sekarang.</p>
                            <div class="mt-4">
                                <a href="{{ route('booking') }}" class="inline-flex items-center gap-2 rounded-full bg-[#c9a227] px-6 py-2.5 text-xs font-semibold text-white shadow-sm transition hover:bg-[#b68d1f]">
                                    <span>Pilih Kamar Baru</span>
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    @else
                        {{-- Stepper Items --}}
                        <div class="relative space-y-8 pl-4 sm:pl-6 before:absolute before:left-8 sm:before:left-10 before:top-4 before:bottom-4 before:w-0.5 before:bg-slate-200">
                            
                            {{-- Step 1: Pemesanan Diajukan --}}
                            @php
                                $s1Complete = $currentStep >= 1;
                                $s1Active = $currentStep === 1 && $status === 'pending';
                            @endphp
                            <div class="relative flex items-start gap-4 sm:gap-6">
                                <div class="relative z-10 flex h-10 w-10 shrink-0 items-center justify-center rounded-full transition-all duration-300 {{ $s1Complete ? ($s1Active ? 'bg-[#c9a227] text-white ring-4 ring-[#c9a227]/20' : 'bg-slate-900 text-white') : 'bg-slate-100 text-slate-400' }}">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                    </svg>
                                </div>
                                <div class="pt-1 flex-1">
                                    <div class="flex items-center justify-between">
                                        <h3 class="text-sm font-bold {{ $s1Complete ? 'text-slate-900' : 'text-slate-400' }}">1. Pengajuan Booking Diterima</h3>
                                        <span class="text-[11px] text-slate-500">{{ $booking->created_at ? $booking->created_at->format('H:i') : '' }}</span>
                                    </div>
                                    <p class="mt-0.5 text-xs text-slate-500 leading-relaxed">Formulir booking kamar {{ $booking->room_code }} telah tersimpan dalam sistem dan menunggu verifikasi admin.</p>
                                </div>
                            </div>

                            {{-- Step 2: Pembayaran --}}
                            @php
                                $s2Complete = in_array($status, ['dibayar', 'menunggu_konfirmasi_owner', 'siap_huni', 'dihuni', 'selesai']);
                                $s2Active = in_array($status, ['pending', 'menunggu_pembayaran']);
                            @endphp
                            <div class="relative flex items-start gap-4 sm:gap-6">
                                <div class="relative z-10 flex h-10 w-10 shrink-0 items-center justify-center rounded-full transition-all duration-300 {{ $s2Complete ? 'bg-slate-900 text-white' : ($s2Active ? 'bg-[#c9a227] text-white ring-4 ring-[#c9a227]/20 animate-pulse' : 'bg-slate-100 text-slate-400') }}">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z" />
                                    </svg>
                                </div>
                                <div class="pt-1 flex-1">
                                    <div class="flex items-center justify-between">
                                        <h3 class="text-sm font-bold {{ $s2Complete || $s2Active ? 'text-slate-900' : 'text-slate-400' }}">2. Pembayaran & Verifikasi Tagihan</h3>
                                        @if ($s2Complete)
                                            <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-emerald-600">
                                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                                </svg>
                                                Lunas
                                            </span>
                                        @elseif ($s2Active)
                                            <span class="text-[11px] font-semibold text-amber-600">Proses Pembayaran</span>
                                        @endif
                                    </div>
                                    <p class="mt-0.5 text-xs text-slate-500 leading-relaxed">
                                        @if ($s2Complete)
                                            Pembayaran sebesar Rp{{ number_format($booking->monthly_rate, 0, ',', '.') }} telah berhasil diverifikasi oleh sistem.
                                        @elseif ($pendingPayment)
                                            Tagihan telah diterbitkan. Silakan lanjutkan pembayaran dengan metode yang tersedia.
                                        @else
                                            Admin sedang meninjau pesanan dan menyiapkan rincian invoice pembayaran Anda.
                                        @endif
                                    </p>

                                    @if ($pendingPayment && !$isExpired)
                                        <div class="mt-3">
                                            <a href="{{ route('payment.pay', $pendingPayment) }}" class="inline-flex items-center gap-2 rounded-xl bg-[#c9a227] px-4 py-2 text-xs font-semibold text-white shadow-sm transition hover:bg-[#b68d1f]">
                                                <span>Bayar Tagihan Sekarang</span>
                                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                                                </svg>
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            {{-- Step 3: Konfirmasi Pemilik --}}
                            @php
                                $s3Complete = in_array($status, ['siap_huni', 'dihuni', 'selesai']);
                                $s3Active = in_array($status, ['dibayar', 'menunggu_konfirmasi_owner']);
                            @endphp
                            <div class="relative flex items-start gap-4 sm:gap-6">
                                <div class="relative z-10 flex h-10 w-10 shrink-0 items-center justify-center rounded-full transition-all duration-300 {{ $s3Complete ? 'bg-slate-900 text-white' : ($s3Active ? 'bg-[#c9a227] text-white ring-4 ring-[#c9a227]/20 animate-pulse' : 'bg-slate-100 text-slate-400') }}">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-7.5 6.75h10.5a2.25 2.25 0 002.25-2.25V6.75a2.25 2.25 0 00-2.25-2.25H6.75A2.25 2.25 0 004.5 6.75v10.5a2.25 2.25 0 002.25 2.25z" />
                                    </svg>
                                </div>
                                <div class="pt-1 flex-1">
                                    <div class="flex items-center justify-between">
                                        <h3 class="text-sm font-bold {{ $s3Complete || $s3Active ? 'text-slate-900' : 'text-slate-400' }}">3. Konfirmasi Pemilik & Penyiapan Kamar</h3>
                                        @if ($s3Complete)
                                            <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-emerald-600">
                                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                                </svg>
                                                Selesai
                                            </span>
                                        @elseif ($s3Active)
                                            <span class="text-[11px] font-semibold text-indigo-600">Dalam Peninjauan</span>
                                        @endif
                                    </div>
                                    <p class="mt-0.5 text-xs text-slate-500 leading-relaxed">
                                        Pemilik kos memastikan kelengkapan inventaris kamar, kebersihan, dan mempersiapkan kunci akses.
                                    </p>
                                </div>
                            </div>

                            {{-- Step 4: Siap Dihuni / Check-in --}}
                            @php
                                $s4Complete = in_array($status, ['siap_huni', 'dihuni', 'selesai']);
                            @endphp
                            <div class="relative flex items-start gap-4 sm:gap-6">
                                <div class="relative z-10 flex h-10 w-10 shrink-0 items-center justify-center rounded-full transition-all duration-300 {{ $s4Complete ? 'bg-emerald-600 text-white ring-4 ring-emerald-100' : 'bg-slate-100 text-slate-400') }}">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                                    </svg>
                                </div>
                                <div class="pt-1 flex-1">
                                    <div class="flex items-center justify-between">
                                        <h3 class="text-sm font-bold {{ $s4Complete ? 'text-slate-900' : 'text-slate-400' }}">4. Siap Dihuni / Check-in</h3>
                                        @if ($s4Complete)
                                            <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-emerald-600">
                                                Aktif
                                            </span>
                                        @endif
                                    </div>
                                    <p class="mt-0.5 text-xs text-slate-500 leading-relaxed">
                                        Kamar siap ditempati pada tanggal masuk yang telah disetujui. Tunjukkan bukti booking saat serah terima kunci kepada pengelola.
                                    </p>

                                    @if ($s4Complete)
                                        <div class="mt-3 flex gap-2">
                                            <a href="{{ route('booking.bukti', $booking) }}" class="inline-flex items-center gap-2 rounded-xl border border-[#c9a227] bg-[#faf8f5] px-4 py-2 text-xs font-semibold text-[#c9a227] shadow-sm transition hover:bg-[#c9a227] hover:text-white">
                                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24-1.17-.346-2.378-.346-3.604 0-4.418 3.582-8 8-8s8 3.582 8 8c0 1.226-.106 2.434-.346 3.604M12 18.75v-6m0 0l-3 3m3-3l3 3" />
                                                </svg>
                                                <span>Cetak Bukti Reservasi</span>
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>

                        </div>
                    @endif
                </div>

            </div>

            {{-- Sidebar Column (Detail Kamar & Action Box) --}}
            <div class="space-y-6 lg:col-span-4">
                
                {{-- Room Detail Card --}}
                <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h2 class="text-sm font-bold uppercase tracking-wider text-slate-900">Rincian Kamar</h2>

                    <div class="mt-4 overflow-hidden rounded-2xl border border-slate-100 bg-slate-50">
                        @if ($booking->room && $booking->room->image_url)
                            <img src="{{ $booking->room->image_url }}" alt="Kamar {{ $booking->room_code }}" class="h-40 w-full object-cover">
                        @else
                            <div class="flex h-36 w-full items-center justify-center bg-slate-100 text-slate-400">
                                <svg class="h-10 w-10 text-slate-300" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                                </svg>
                            </div>
                        @endif
                        <div class="p-4">
                            <div class="flex items-center justify-between">
                                <h3 class="text-base font-bold text-slate-900">Kamar {{ $booking->room_code }}</h3>
                                <span class="text-xs font-semibold text-[#c9a227]">
                                    {{ $booking->room && $booking->room->price_monthly >= 1400000 ? 'Family Room' : 'Student Room' }}
                                </span>
                            </div>
                            <p class="mt-1 text-sm font-semibold text-slate-900">
                                Rp{{ number_format($booking->monthly_rate, 0, ',', '.') }}<span class="text-xs font-normal text-slate-500"> / bulan</span>
                            </p>
                        </div>
                    </div>

                    <div class="mt-5 space-y-3 divide-y divide-slate-100 text-xs">
                        <div class="flex items-center justify-between pt-3 text-slate-600">
                            <span>Tanggal Masuk</span>
                            <span class="font-semibold text-slate-900">{{ optional($booking->move_in_date)->translatedFormat('d M Y') ?? '-' }}</span>
                        </div>
                        <div class="flex items-center justify-between pt-3 text-slate-600">
                            <span>Tanggal Keluar</span>
                            <span class="font-semibold text-slate-900">{{ optional($booking->move_out_date)->translatedFormat('d M Y') ?? '-' }}</span>
                        </div>
                        <div class="flex items-center justify-between pt-3 text-slate-600">
                            <span>Nama Penyewa</span>
                            <span class="font-semibold text-slate-900">{{ Auth::user()->name }}</span>
                        </div>
                        <div class="flex items-center justify-between pt-3 text-slate-600">
                            <span>Status Pembayaran</span>
                            <span class="font-semibold capitalize text-slate-900">{{ $booking->payment_status ?? 'pending' }}</span>
                        </div>
                    </div>
                </div>

                {{-- Action Box (Cancel Button, Help) --}}
                <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h2 class="text-sm font-bold uppercase tracking-wider text-slate-900">Bantuan & Tindakan</h2>
                    
                    <div class="mt-4 space-y-3">
                        @if (!in_array($status, ['dibatalkan', 'selesai', 'dihuni']))
                            <button type="button" onclick="openCancelModal()" class="w-full inline-flex items-center justify-center gap-2 rounded-2xl border border-rose-200 bg-rose-50 px-4 py-2.5 text-xs font-semibold text-rose-700 transition hover:bg-rose-100">
                                <svg class="h-4 w-4 text-rose-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                <span>Batalkan Pemesanan</span>
                            </button>
                        @endif

                        <a href="{{ route('contact') }}" class="w-full inline-flex items-center justify-center gap-2 rounded-2xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-xs font-semibold text-slate-700 transition hover:bg-slate-100">
                            <svg class="h-4 w-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H8.25m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H12m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 01-2.555-.337A5.972 5.972 0 015.41 20.97a.75.75 0 01-.818-.948l.643-2.316C3.96 16.31 3 14.28 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25z" />
                            </svg>
                            <span>Hubungi Pengelola Kos</span>
                        </a>
                    </div>
                </div>

            </div>

        </div>

    </div>
</div>

{{-- Modal Dialog Konfirmasi Batalkan Booking --}}
<div id="cancelModal" class="fixed inset-0 z-50 hidden overflow-y-auto bg-slate-900/60 backdrop-blur-sm transition-opacity">
    <div class="flex min-h-screen items-center justify-center p-4">
        <div class="relative w-full max-w-md rounded-3xl bg-white p-6 shadow-2xl transition-all">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h3 class="text-base font-bold text-slate-900">Konfirmasi Pembatalan</h3>
                <button type="button" onclick="closeCancelModal()" class="rounded-lg p-1 text-slate-400 hover:bg-slate-100 hover:text-slate-600">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="mt-4 text-xs text-slate-600 leading-relaxed">
                <p>Apakah Anda yakin ingin membatalkan pemesanan <strong class="text-slate-800">Kamar {{ $booking->room_code }}</strong>?</p>
                <p class="mt-2 text-slate-500">Setelah dibatalkan, kamar ini akan kembali tersedia untuk calon penghuni lain dan Anda dapat memilih kamar baru.</p>
            </div>

            <form action="{{ route('booking.cancel', $booking) }}" method="POST" class="mt-6 flex items-center justify-end gap-3">
                @csrf
                @method('DELETE')
                <button type="button" onclick="closeCancelModal()" class="rounded-xl border border-slate-200 px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50">
                    Tutup
                </button>
                <button type="submit" class="rounded-xl bg-rose-600 px-5 py-2 text-xs font-semibold text-white shadow-sm hover:bg-rose-700 transition">
                    Ya, Batalkan Pesanan
                </button>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function openCancelModal() {
        document.getElementById('cancelModal').classList.remove('hidden');
    }

    function closeCancelModal() {
        document.getElementById('cancelModal').classList.add('hidden');
    }

    // Hitung Mundur (Countdown Timer)
    document.addEventListener('DOMContentLoaded', function () {
        const container = document.getElementById('countdown-container');
        if (!container) return;

        const targetDate = new Date(container.dataset.target).getTime();
        const elHours = document.getElementById('timer-hours');
        const elMinutes = document.getElementById('timer-minutes');
        const elSeconds = document.getElementById('timer-seconds');

        function updateTimer() {
            const now = new Date().getTime();
            const distance = targetDate - now;

            if (distance <= 0) {
                if (elHours) elHours.textContent = '00';
                if (elMinutes) elMinutes.textContent = '00';
                if (elSeconds) elSeconds.textContent = '00';
                return;
            }

            const hours = Math.floor(distance / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);

            if (elHours) elHours.textContent = String(hours).padStart(2, '0');
            if (elMinutes) elMinutes.textContent = String(minutes).padStart(2, '0');
            if (elSeconds) elSeconds.textContent = String(seconds).padStart(2, '0');
        }

        updateTimer();
        setInterval(updateTimer, 1000);
    });
</script>
@endpush
@endsection

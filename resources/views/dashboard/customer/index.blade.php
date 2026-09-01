@extends('layouts.dashboard-app')

@section('title', 'Beranda · ARCHOFESA KOST')

@section('content')
<div class="px-4 py-6 sm:px-6 lg:px-8 lg:py-8">
    <div class="mx-auto max-w-7xl space-y-6">
        
        {{-- Banner Notifikasi Jika Belum Ada Nomor WhatsApp (Misal Daftar via Google) --}}
        @if(empty(auth()->user()->phone))
            <div class="rounded-2xl border border-amber-200 bg-amber-50 p-4 shadow-sm flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-2xl bg-amber-100 text-amber-700 font-bold text-base">
                        📱
                    </div>
                    <div>
                        <p class="text-sm font-bold text-amber-900">Nomor WhatsApp Belum Terhubung</p>
                        <p class="text-xs text-amber-700">Lengkapi nomor WhatsApp aktif Anda agar bot kami dapat mengirimkan notifikasi tagihan sewa dan konfirmasi pemilik secara otomatis.</p>
                    </div>
                </div>
                <a href="{{ route('profile.edit') }}" class="shrink-0 rounded-full bg-[#c9a227] px-4 py-2 text-xs font-bold text-white shadow-sm transition hover:bg-[#b68d1f]">
                    Lengkapi Sekarang &rarr;
                </a>
            </div>
        @endif

        <!-- Header Hero Banner (Deskripsi Bersih & Modern tanpa Foto) -->
        <header class="rounded-3xl border border-[#e7e2d8] bg-white p-6 shadow-sm sm:p-8 lg:p-10">
            <div class="max-w-3xl">
                <div class="inline-flex items-center gap-2 rounded-full bg-[#faf8f5] px-3.5 py-1 text-xs font-semibold uppercase tracking-wider text-[#c9a227] border border-[#e7e2d8]">
                    <span>Selamat Datang Kembali</span>
                </div>
                <h1 class="mt-4 text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl lg:text-4xl">
                    Tinggal Nyaman & Aman di ARCHOFESA KOST
                </h1>
                <p class="mt-3 text-sm sm:text-base leading-relaxed text-slate-600">
                    Hunian kos eksklusif dengan lingkungan yang tenang, fasilitas lengkap, dan lokasi strategis. Dirancang khusus untuk kenyamanan belajar mahasiswa dan produktivitas profesional.
                </p>

                <!-- Quick Action Buttons -->
                <div class="mt-6 flex flex-wrap items-center gap-3">
                    @if ($booking && !in_array($booking->status, ['dibatalkan', 'selesai']))
                        <a href="{{ route('booking.status') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 rounded-xl bg-slate-900 px-5 py-2.5 text-xs font-bold text-white shadow-sm transition hover:bg-slate-800">
                            <svg class="h-4 w-4 text-[#c9a227]" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z" />
                            </svg>
                            <span>Lacak Status Booking</span>
                        </a>
                    @else
                        <a href="{{ route('customer.rooms') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 rounded-xl bg-[#c9a227] px-5 py-2.5 text-xs font-bold text-white shadow-sm transition hover:bg-[#b68d1f]">
                            <span>Pilih & Booking Kamar</span>
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                            </svg>
                        </a>
                    @endif
                    <a href="#facilities" class="w-full sm:w-auto inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-5 py-2.5 text-xs font-semibold text-slate-700 transition hover:bg-slate-50 hover:border-slate-300">
                        Lihat Fasilitas
                    </a>
                </div>
            </div>

            <!-- Ringkasan Spesifikasi Properti -->
            <div class="mt-8 rounded-2xl border border-[#e7e2d8] bg-[#faf8f5] p-4 sm:p-6">
                <div class="grid grid-cols-2 gap-4 sm:grid-cols-4">
                    <div class="space-y-1">
                        <p class="text-xs text-slate-500">Properti</p>
                        <p class="text-sm sm:text-base font-bold text-slate-900 truncate">{{ $propertyName ?? 'ARCHOFESA KOST' }}</p>
                    </div>
                    <div class="space-y-1">
                        <p class="text-xs text-slate-500">Lokasi</p>
                        <p class="text-sm sm:text-base font-bold text-slate-900 truncate">{{ $location ?? 'Pedurungan, Semarang' }}</p>
                    </div>
                    <div class="space-y-1">
                        <p class="text-xs text-slate-500">Ukuran Kamar</p>
                        <p class="text-sm sm:text-base font-bold text-slate-900">{{ $roomSize ?? '3 x 4 Meter' }}</p>
                    </div>
                    <div class="space-y-1">
                        <p class="text-xs text-slate-500">Kamar Tersedia</p>
                        <p class="text-sm sm:text-base font-bold text-emerald-700">{{ $availableRooms ?? 0 }} Kamar Kosong</p>
                    </div>
                </div>
            </div>
        </header>

        <!-- Booking Aktif Section -->
        <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between border-b border-slate-100 pb-4">
                <div>
                    <h2 class="text-lg sm:text-xl font-bold text-slate-900">Reservasi & Hunian Saya</h2>
                    <p class="mt-0.5 text-xs sm:text-sm text-slate-500">Kelola status pesanan, rincian kamar, dan pembayaran Anda</p>
                </div>
                @if ($booking)
                    <div class="flex flex-wrap items-center gap-2">
                        <a href="{{ route('booking.status') }}" class="inline-flex items-center gap-1.5 rounded-xl bg-slate-900 px-4 py-2 text-xs font-semibold text-white shadow-sm transition hover:bg-slate-800">
                            <span>Lacak Status</span>
                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                            </svg>
                        </a>
                        @if ($booking->status === 'dihuni')
                            <a href="{{ route('customer.extend.form', $booking) }}" class="rounded-xl bg-[#c9a227] px-4 py-2 text-xs font-semibold text-white transition hover:bg-[#b68d1f]">
                                Perpanjang Sewa
                            </a>
                        @endif
                    </div>
                @endif
            </div>

            @if ($booking)
                @php
                    $statusLabel = match($booking->status) {
                        'pending'                   => 'Menunggu Verifikasi Admin',
                        'menunggu_pembayaran'       => 'Menunggu Pembayaran',
                        'dibayar'                  => 'Sudah Dibayar',
                        'menunggu_konfirmasi_owner'=> 'Menunggu Konfirmasi Pemilik',
                        'siap_huni'                => 'Siap Dihuni',
                        'dihuni'                   => 'Sedang Dihuni',
                        'selesai'                  => 'Selesai',
                        'dibatalkan'               => 'Dibatalkan',
                        default                    => ucfirst($booking->status)
                    };

                    $statusBadge = match($booking->status) {
                        'dihuni', 'siap_huni', 'dibayar' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                        'pending', 'menunggu_pembayaran', 'menunggu_konfirmasi_owner' => 'bg-amber-50 text-amber-700 border-amber-200',
                        'dibatalkan' => 'bg-rose-50 text-rose-700 border-rose-200',
                        default => 'bg-slate-100 text-slate-700 border-slate-200',
                    };

                    $isExpiringSoon = $booking->status === 'dihuni' && isset($remainingDays) && $remainingDays <= 7 && $remainingDays >= 0;
                @endphp

                @if ($isExpiringSoon)
                    <div class="mt-6 rounded-2xl border border-amber-200 bg-amber-50/90 p-4 sm:p-5">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                            <div class="flex items-start gap-3">
                                <svg class="h-5 w-5 shrink-0 text-amber-600 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                                </svg>
                                <div>
                                    <h3 class="text-xs sm:text-sm font-bold text-amber-900">Masa Sewa Berakhir Dalam {{ $remainingDays }} Hari</h3>
                                    <p class="mt-0.5 text-xs text-amber-700">Masa sewa Anda akan berakhir pada {{ optional($booking->move_out_date)->translatedFormat('d F Y') }}. Perpanjang sewa sekarang untuk mengamankan kamar Anda.</p>
                                </div>
                            </div>
                            <a href="{{ route('customer.extend.form', $booking) }}" class="inline-flex shrink-0 items-center justify-center rounded-xl bg-[#c9a227] px-4 py-2 text-xs font-bold text-white shadow-sm transition hover:bg-[#b68d1f]">
                                Perpanjang Sekarang
                            </a>
                        </div>
                    </div>
                @endif

                <div class="mt-6 grid gap-4 lg:grid-cols-12">
                    <!-- Info Kamar -->
                    <div class="rounded-2xl border border-slate-200 bg-slate-50/70 p-5 sm:p-6 lg:col-span-7">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                            <div>
                                <p class="text-xs text-slate-500">Kamar Dipesan</p>
                                <p class="text-lg font-bold text-slate-900">Kamar {{ $booking->room_code ?: 'Menunggu Penugasan' }}</p>
                            </div>
                            <span class="inline-flex self-start sm:self-center rounded-full border px-3 py-1 text-xs font-semibold {{ $statusBadge }}">
                                {{ $statusLabel }}
                            </span>
                        </div>

                        <div class="mt-5 grid grid-cols-2 gap-4 border-t border-slate-200/80 pt-4 sm:grid-cols-3 text-xs">
                            <div>
                                <p class="text-slate-500">Tanggal Masuk</p>
                                <p class="mt-1 font-bold text-slate-800">{{ optional($booking->move_in_date)->translatedFormat('d M Y') ?? 'Menunggu' }}</p>
                            </div>
                            <div>
                                <p class="text-slate-500">Tanggal Keluar</p>
                                <p class="mt-1 font-bold text-slate-800">{{ optional($booking->move_out_date)->translatedFormat('d M Y') ?? 'Menunggu' }}</p>
                            </div>
                            <div class="col-span-2 sm:col-span-1" id="dashboard-countdown" data-target="{{ $booking->move_out_date ? \Carbon\Carbon::parse($booking->move_out_date)->endOfDay()->toIso8601String() : '' }}">
                                <p class="text-slate-500">Sisa Waktu</p>
                                @if (in_array($booking->status, ['siap_huni', 'dihuni']) && $booking->move_out_date)
                                    <div class="mt-1.5 flex items-center gap-1">
                                        <span class="inline-flex items-center justify-center rounded-lg bg-slate-900 px-2 py-1 font-mono text-xs font-bold text-white min-w-[28px]" id="dash-days">{{ str_pad($remainingDays, 2, '0', STR_PAD_LEFT) }}</span>
                                        <span class="text-[10px] text-slate-400">h</span>
                                        <span class="text-slate-400 font-bold">:</span>
                                        <span class="inline-flex items-center justify-center rounded-lg bg-slate-900 px-2 py-1 font-mono text-xs font-bold text-white min-w-[28px]" id="dash-hours">00</span>
                                        <span class="text-[10px] text-slate-400">j</span>
                                        <span class="text-slate-400 font-bold">:</span>
                                        <span class="inline-flex items-center justify-center rounded-lg bg-slate-900 px-2 py-1 font-mono text-xs font-bold text-white min-w-[28px]" id="dash-mins">00</span>
                                        <span class="text-[10px] text-slate-400">m</span>
                                        <span class="text-slate-400 font-bold">:</span>
                                        <span class="inline-flex items-center justify-center rounded-lg bg-slate-900 px-2 py-1 font-mono text-xs font-bold text-white min-w-[28px]" id="dash-secs">00</span>
                                        <span class="text-[10px] text-slate-400">d</span>
                                    </div>
                                @else
                                    <p class="mt-1 font-bold text-slate-800">{{ $remainingDays ?? 0 }} Hari</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Tagihan -->
                    <div class="rounded-2xl border border-slate-200 bg-white p-5 sm:p-6 lg:col-span-5 flex flex-col justify-between">
                        <div>
                            <p class="text-xs text-slate-500">Tagihan & Pembayaran</p>
                            @if ($payments->isNotEmpty())
                                @php $latestPayment = $payments->first(); @endphp
                                <p class="mt-1 text-xl font-bold text-slate-900">Rp{{ number_format((float) $latestPayment->amount, 0, ',', '.') }}</p>
                                <p class="mt-1 text-xs text-slate-500">Status: <span class="font-semibold text-slate-700 capitalize">{{ $latestPayment->status }}</span></p>
                            @else
                                <p class="mt-1 text-xl font-bold text-slate-900">Rp{{ number_format($booking->monthly_rate, 0, ',', '.') }}</p>
                                <p class="mt-1 text-xs text-slate-500">Tarif sewa bulanan</p>
                            @endif
                        </div>

                        <div class="mt-5 flex flex-wrap items-center gap-2 pt-3 border-t border-slate-100">
                            <a href="{{ route('customer.payments') }}" class="rounded-xl border border-slate-200 px-3.5 py-2 text-xs font-semibold text-slate-700 transition hover:bg-slate-50">
                                Rincian Tagihan
                            </a>
                            <a href="{{ route('booking.status') }}" class="rounded-xl bg-[#c9a227] px-3.5 py-2 text-xs font-semibold text-white transition hover:bg-[#b68d1f]">
                                Status Pemesanan
                            </a>
                        </div>
                    </div>
                </div>
            @else
                <div class="mt-6 rounded-2xl border border-dashed border-slate-200 bg-slate-50/50 p-8 text-center">
                    <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-[#c9a227]/10 text-[#c9a227]">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                    <h3 class="mt-3 text-base font-bold text-slate-900">Belum Ada Reservasi Aktif</h3>
                    <p class="mt-1 text-xs text-slate-500 max-w-sm mx-auto">Pilih kamar yang sesuai dengan kebutuhan Anda dan ajukan booking secara langsung.</p>
                    <div class="mt-5">
                        <a href="{{ route('customer.rooms') }}" class="inline-flex items-center gap-2 rounded-xl bg-[#c9a227] px-5 py-2.5 text-xs font-bold text-white shadow-sm transition hover:bg-[#b68d1f]">
                            <span>Cari & Pesan Kamar</span>
                        </a>
                    </div>
                </div>
            @endif
        </section>

        <!-- Informasi Tipe Kamar (Spesifikasi Bersih tanpa Foto) -->
        <section id="rooms" class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-100 pb-4">
                <div>
                    <h2 class="text-lg sm:text-xl font-bold text-slate-900">Informasi Tipe Kamar</h2>
                    <p class="mt-0.5 text-xs sm:text-sm text-slate-500">Spesifikasi kamar yang siap dihuni</p>
                </div>
                <a href="{{ route('customer.rooms') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-[#c9a227] hover:text-[#b68d1f]">
                    <span>Lihat Semua Kamar ({{ $totalRooms ?? 18 }})</span>
                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </a>
            </div>

            <div class="mt-6 rounded-2xl border border-[#e7e2d8] bg-[#faf8f5] p-6 sm:p-7">
                <div class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
                    <div class="space-y-3">
                        <div class="flex flex-wrap items-center gap-3">
                            <h3 class="text-xl font-bold text-slate-900">Kamar Standard Mahasiswa & Pekerja</h3>
                            <span class="rounded-full bg-emerald-100 text-emerald-800 border border-emerald-200 px-3 py-0.5 text-xs font-semibold">
                                {{ $availableRooms ?? 0 }} Kamar Tersedia
                            </span>
                        </div>
                        <p class="text-xs sm:text-sm text-slate-600 max-w-2xl leading-relaxed">
                            Kamar privat berdesain minimalis dan tenang. Sudah dilengkapi dengan perabotan lengkap (tempat tidur, lemari pakaian, meja belajar), kamar mandi dalam, serta akses listrik dan internet berkecepatan tinggi.
                        </p>

                        <!-- Fasilitas Kamar Chips -->
                        <div class="flex flex-wrap gap-2 pt-1">
                            <span class="rounded-lg border border-slate-200 bg-white px-3 py-1 text-xs font-medium text-slate-700 shadow-2xs">Ukuran 3 x 4 m</span>
                            <span class="rounded-lg border border-slate-200 bg-white px-3 py-1 text-xs font-medium text-slate-700 shadow-2xs">Kamar Mandi Dalam</span>
                            <span class="rounded-lg border border-slate-200 bg-white px-3 py-1 text-xs font-medium text-slate-700 shadow-2xs">Kasur & Lemari</span>
                            <span class="rounded-lg border border-slate-200 bg-white px-3 py-1 text-xs font-medium text-slate-700 shadow-2xs">Listrik & Air Bersih</span>
                            <span class="rounded-lg border border-slate-200 bg-white px-3 py-1 text-xs font-medium text-slate-700 shadow-2xs">WiFi Gratis</span>
                        </div>
                    </div>

                    <div class="shrink-0 border-t border-slate-200/80 pt-4 lg:border-t-0 lg:pt-0 lg:border-l lg:pl-8">
                        <p class="text-xs text-slate-500">Tarif Sewa</p>
                        <p class="mt-1 text-2xl sm:text-3xl font-extrabold text-slate-900">
                            Rp{{ number_format($roomPrice ?? 1400000, 0, ',', '.') }}
                            <span class="text-xs font-normal text-slate-500">/ bulan</span>
                        </p>
                        <div class="mt-4">
                            <a href="{{ route('customer.rooms') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 rounded-xl bg-[#c9a227] px-6 py-2.5 text-xs font-bold text-white shadow-sm transition hover:bg-[#b68d1f]">
                                <span>Pilih Kamar</span>
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Fasilitas Bersama -->
        <section id="facilities" class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
            <div class="border-b border-slate-100 pb-4">
                <h2 class="text-lg sm:text-xl font-bold text-slate-900">Fasilitas Properti</h2>
                <p class="mt-0.5 text-xs sm:text-sm text-slate-500">Fasilitas bersama yang dapat digunakan oleh seluruh penghuni</p>
            </div>
            <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5">
                    <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-white text-slate-700 border border-slate-200 shadow-2xs mb-3">
                        <svg class="h-5 w-5 text-[#c9a227]" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 21v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21m0 0h4.5V3.75a.75.75 0 00-.75-.75H4.5a.75.75 0 00-.75.75V21h4.5z" />
                        </svg>
                    </div>
                    <h3 class="text-sm font-bold text-slate-900">Kamar Mandi Dalam</h3>
                    <p class="mt-1 text-xs text-slate-600 leading-relaxed">Privasi terjamin di setiap kamar dengan sanitasi bersih dan terawat.</p>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5">
                    <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-white text-slate-700 border border-slate-200 shadow-2xs mb-3">
                        <svg class="h-5 w-5 text-[#c9a227]" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-sm font-bold text-slate-900">Dapur Bersama</h3>
                    <p class="mt-1 text-xs text-slate-600 leading-relaxed">Dilengkapi kompor, wastafel, dan area makan yang bersih untuk memasak.</p>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5">
                    <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-white text-slate-700 border border-slate-200 shadow-2xs mb-3">
                        <svg class="h-5 w-5 text-[#c9a227]" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.288 15.038a5.25 5.25 0 017.424 0M5.106 11.856c3.807-3.808 9.98-3.808 13.788 0M1.924 8.674c5.565-5.565 14.587-5.565 20.152 0M12.53 18.22l-.53.53-.53-.53a.75.75 0 011.06 0z" />
                        </svg>
                    </div>
                    <h3 class="text-sm font-bold text-slate-900">Internet WiFi Cepat</h3>
                    <p class="mt-1 text-xs text-slate-600 leading-relaxed">Koneksi internet tanpa batas untuk kebutuhan kuliah dan pekerjaan remote.</p>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5">
                    <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-white text-slate-700 border border-slate-200 shadow-2xs mb-3">
                        <svg class="h-5 w-5 text-[#c9a227]" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />
                        </svg>
                    </div>
                    <h3 class="text-sm font-bold text-slate-900">Keamanan 24 Jam</h3>
                    <p class="mt-1 text-xs text-slate-600 leading-relaxed">Akses gerbang terkontrol dan lingkungan yang aman untuk ketenangan tinggal.</p>
                </div>
            </div>
        </section>

        <!-- Lokasi & Ulasan -->
        <section class="grid gap-6 lg:grid-cols-12">
            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8 lg:col-span-7">
                <div class="border-b border-slate-100 pb-3 mb-4">
                    <h2 class="text-base sm:text-lg font-bold text-slate-900">Lokasi Properti</h2>
                    <p class="text-xs text-slate-500">Pedurungan, Semarang, Jawa Tengah</p>
                </div>
                <div class="overflow-hidden rounded-2xl border border-slate-200">
                    <x-leaflet-map height="260px" />
                </div>
            </div>

            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8 lg:col-span-5 flex flex-col justify-between">
                <div>
                    <div class="border-b border-slate-100 pb-3 mb-4">
                        <h2 class="text-base sm:text-lg font-bold text-slate-900">Ulasan Penghuni</h2>
                        <div class="mt-2 flex items-center gap-2">
                            <span class="rounded-lg bg-[#faf8f5] border border-[#e7e2d8] px-2.5 py-1 text-xs font-bold text-slate-900">
                                {{ number_format($avgRating ?? 5.0, 1) }} / 5.0
                            </span>
                            <span class="text-xs text-slate-500">Rata-rata kepuasan penghuni</span>
                        </div>
                    </div>

                    @if ($reviews->isNotEmpty())
                        <div class="space-y-3">
                            @foreach ($reviews->take(2) as $review)
                                <div class="rounded-2xl border border-slate-100 bg-slate-50 p-4 text-xs text-slate-600">
                                    <div class="flex items-center justify-between mb-1">
                                        <span class="font-bold text-slate-900">{{ $review->user->name ?? 'Penghuni' }}</span>
                                        <span class="text-[#c9a227] font-semibold">{{ $review->rating }}/5</span>
                                    </div>
                                    <p class="text-slate-600 leading-relaxed">{{ $review->comment ?: 'Pelayanan sangat ramah dan kamar bersih.' }}</p>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="rounded-2xl border border-dashed border-slate-200 bg-slate-50 p-6 text-center text-xs text-slate-500">
                            Belum ada ulasan publik.
                        </div>
                    @endif
                </div>

                <div class="mt-6 pt-4 border-t border-slate-100">
                    <a href="{{ route('contact') }}" class="w-full inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition">
                        <span>Punya Pertanyaan? Hubungi Pengelola</span>
                    </a>
                </div>
            </div>
        </section>

    </div>
</div>
@endsection

@push('scripts')
<script>
    // Live Countdown Sisa Waktu Sewa (Dashboard)
    document.addEventListener('DOMContentLoaded', function () {
        const container = document.getElementById('dashboard-countdown');
        if (!container || !container.dataset.target) return;

        const targetDate = new Date(container.dataset.target).getTime();
        const elDays = document.getElementById('dash-days');
        const elHours = document.getElementById('dash-hours');
        const elMins = document.getElementById('dash-mins');
        const elSecs = document.getElementById('dash-secs');

        if (!elDays) return; // countdown elements not rendered (status not siap_huni/dihuni)

        function updateDashTimer() {
            const now = new Date().getTime();
            const distance = targetDate - now;

            if (distance <= 0) {
                if (elDays) elDays.textContent = '00';
                if (elHours) elHours.textContent = '00';
                if (elMins) elMins.textContent = '00';
                if (elSecs) elSecs.textContent = '00';
                return;
            }

            const days = Math.floor(distance / (1000 * 60 * 60 * 24));
            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);

            if (elDays) elDays.textContent = String(days).padStart(2, '0');
            if (elHours) elHours.textContent = String(hours).padStart(2, '0');
            if (elMins) elMins.textContent = String(minutes).padStart(2, '0');
            if (elSecs) elSecs.textContent = String(seconds).padStart(2, '0');
        }

        updateDashTimer();
        setInterval(updateDashTimer, 1000);
    });
</script>
@endpush

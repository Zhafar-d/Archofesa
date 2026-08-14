@extends('layouts.dashboard-app')

@section('title', 'Beranda · ARCHOFESA KOST')

@section('content')
<div class="px-4 py-6 sm:px-6 lg:px-8 lg:py-8">
    <div class="mx-auto max-w-7xl space-y-6">
        <header class="overflow-hidden rounded-[36px] border border-[#e7e2d8] bg-white shadow-[0_24px_70px_-32px_rgba(15,23,42,0.16)]">
            <div class="grid gap-8 lg:grid-cols-[1.05fr_0.95fr]">
                <div class="p-8 sm:p-10 lg:p-12">
                    <p class="text-sm font-semibold uppercase tracking-[0.24em] text-[#c9a227]">Selamat Datang Kembali</p>
                    <h1 class="mt-4 text-4xl font-semibold tracking-tight text-[#1f2937] sm:text-5xl">Tinggal di jantung kenyamanan ARCHOFESA KOST.</h1>
                    <p class="mt-5 max-w-2xl text-lg leading-8 text-[#4b5563]">Pengalaman ngekos yang istimewa untuk mahasiswa dan profesional, dengan kamar elegan, fasilitas lengkap, dan suasana premium yang tenang.</p>

                    <div class="mt-8 flex flex-wrap gap-3">
                        <a href="#rooms" class="rounded-full bg-[#c9a227] px-5 py-3 text-sm font-semibold text-white transition hover:bg-[#b68d1f]">Jelajahi Kamar</a>
                        <a href="#facilities" class="rounded-full border border-[#e7e2d8] px-5 py-3 text-sm font-semibold text-[#374151] transition hover:border-[#c9a227] hover:text-[#c9a227]">Lihat Fasilitas</a>
                    </div>

                    <div class="mt-8 rounded-[28px] border border-[#e7e2d8] bg-[#faf8f5] p-5">
                        <div class="grid gap-4 sm:grid-cols-3">
                            <div>12
                                <p class="text-sm text-[#6b7280]">Properti</p>
                                <p class="mt-1 font-semibold text-[#1f2937]">{{ $propertyName }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-[#6b7280]">Lokasi</p>
                                <p class="mt-1 font-semibold text-[#1f2937]">{{ $location }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-[#6b7280]">Ukuran Kamar</p>
                                <p class="mt-1 font-semibold text-[#1f2937]">{{ $roomSize }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="relative min-h-[360px] overflow-hidden lg:min-h-full">
                    <img src="{{ $heroImage ?? '' }}" alt="Interior ARCHOFESA KOST" class="h-full w-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-[#111827]/40 via-transparent to-transparent"></div>
                </div>
            </div>
        </header>

        <section class="rounded-[32px] border border-[#e7e2d8] bg-white p-6 shadow-[0_16px_50px_-32px_rgba(15,23,42,0.14)] sm:p-8">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-[0.24em] text-[#c9a227]">Booking Saya</p>
                    <h2 class="mt-2 text-2xl font-semibold text-[#1f2937]">Masa tinggal Anda, terorganisir dengan baik.</h2>
                </div>
                <div class="flex flex-wrap gap-3">
                    @if ($booking && $booking->status === 'dihuni')
                        <a href="{{ route('customer.extend.form', $booking) }}" class="rounded-full bg-[#c9a227] px-4 py-2 text-sm font-semibold text-white transition hover:bg-[#b68d1f]">Perpanjang Sewa</a>
                    @endif
                    @if ($booking && !in_array($booking->status, ['dibatalkan', 'selesai']))
                        <form method="POST" action="{{ route('booking.cancel', $booking) }}" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan booking ini?');" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="rounded-full border border-red-200 bg-red-50 px-4 py-2 text-sm font-semibold text-red-600 transition hover:bg-red-100 hover:text-red-700">
                                Batalin Booking
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            @if ($booking)
                <div class="mt-8 grid gap-4 lg:grid-cols-[1.2fr_0.8fr]">
                    <div class="rounded-[28px] border border-[#e7e2d8] bg-[#faf8f5] p-6">
                        <div class="flex items-center justify-between gap-4">
                            <div>
                                <p class="text-sm text-[#6b7280]">Kode Kamar</p>
                                <p class="mt-1 text-xl font-semibold text-[#1f2937]">{{ $booking->room_code ?: 'Menunggu Penugasan' }}</p>
                            </div>
                            @php
                                $statusLabel = match($booking->status) {
                                    'menunggu_pembayaran' => 'Menunggu Pembayaran',
                                    'dibayar' => 'Sudah Dibayar',
                                    'menunggu_konfirmasi_owner' => 'Menunggu Konfirmasi',
                                    'siap_huni' => 'Siap Dihuni',
                                    'dihuni' => 'Sedang Dihuni',
                                    default => ucfirst($booking->status)
                                };
                            @endphp
                            <span class="rounded-full border border-[#e7e2d8] bg-white px-3 py-1 text-sm font-semibold text-[#374151]">{{ $statusLabel }}</span>
                        </div>
                        <div class="mt-6 grid gap-4 md:grid-cols-3">
                            <div>
                                <p class="text-sm text-[#6b7280]">Tanggal Masuk</p>
                                <p class="mt-1 font-semibold text-[#1f2937]">{{ optional($booking->move_in_date)->format('d M Y') ?? 'Menunggu' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-[#6b7280]">Tanggal Keluar</p>
                                <p class="mt-1 font-semibold text-[#1f2937]">{{ optional($booking->move_out_date)->format('d M Y') ?? 'Menunggu' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-[#6b7280]">Sisa Waktu</p>
                                <p class="mt-1 font-semibold text-[#1f2937]">{{ $remainingDays ?? 0 }} hari</p>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-[28px] border border-[#e7e2d8] bg-[#fffdf9] p-6">
                        <p class="text-sm text-[#6b7280]">Tagihan Saat Ini</p>
                        @if ($payments->isNotEmpty())
                            @php $latestPayment = $payments->first(); @endphp
                            <p class="mt-2 text-2xl font-semibold text-[#1f2937]">Rp{{ number_format((float) $latestPayment->amount, 0, ',', '.') }}</p>
                            <p class="mt-2 text-sm text-[#4b5563]">{{ ucfirst($latestPayment->status) }}</p>
                        @else
                            <p class="mt-2 text-2xl font-semibold text-[#1f2937]">Belum ada tagihan</p>
                            <p class="mt-2 text-sm text-[#4b5563]">Tagihan akan muncul setelah data pembayaran tersedia.</p>
                        @endif
                        <div class="mt-6 flex gap-3">
                            <a href="{{ route('customer.payments') }}" class="rounded-full border border-[#e7e2d8] px-4 py-2 text-sm font-semibold text-[#374151] transition hover:border-[#c9a227] hover:text-[#c9a227]">Lihat Detail</a>
                            @if ($booking && $booking->status === 'dihuni')
                                <a href="{{ route('customer.extend.form', $booking) }}" class="rounded-full bg-[#c9a227] px-4 py-2 text-sm font-semibold text-white transition hover:bg-[#b68d1f]">Perpanjang Sewa</a>
                            @endif
                        </div>
                    </div>
                </div>
            @else
                <div class="mt-8 rounded-[28px] border border-dashed border-[#e7e2d8] bg-[#faf8f5] p-8 text-center">
                    <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-[#c9a227]/10 text-[#c9a227]">
                        <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                    </div>
                    <p class="mt-4 text-xl font-semibold text-[#1f2937]">Kamu belum memiliki booking aktif</p>
                    <p class="mt-2 text-sm leading-7 text-[#4b5563]">Silakan temukan kamar impianmu dan mulai pengalaman ngekos yang baru.</p>
                    <div class="mt-6">
                        <a href="{{ route('customer.rooms') }}" class="inline-flex items-center rounded-full bg-[#c9a227] px-6 py-3 text-sm font-semibold text-white transition hover:bg-[#b68d1f]">Booking Kamar Sekarang</a>
                    </div>
                </div>
            @endif
        </section>

        <section id="gallery" class="rounded-[32px] border border-[#e7e2d8] bg-white p-6 shadow-[0_16px_50px_-32px_rgba(15,23,42,0.14)] sm:p-8">
            <div class="flex items-end justify-between gap-4">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-[0.24em] text-[#c9a227]">Galeri Foto</p>
                    <h2 class="mt-2 text-2xl font-semibold text-[#1f2937]">Tampilan modern dari properti kami.</h2>
                </div>
            </div>
            <div class="mt-8 grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                @foreach ($galleryImages as $idx => $img)
                    <div class="group overflow-hidden rounded-[28px] border border-[#e7e2d8] bg-[#faf8f5]">
                        <img src="{{ $img }}" alt="Foto Galeri {{ $idx + 1 }}" class="h-56 w-full object-cover transition duration-300 group-hover:scale-105">
                        <div class="p-4">
                            <p class="font-semibold text-[#1f2937]">Foto {{ $idx + 1 }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>

        <section id="facilities" class="rounded-[32px] border border-[#e7e2d8] bg-white p-6 shadow-[0_16px_50px_-32px_rgba(15,23,42,0.14)] sm:p-8">
            <div class="flex items-end justify-between gap-4">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-[0.24em] text-[#c9a227]">Fasilitas</p>
                    <h2 class="mt-2 text-2xl font-semibold text-[#1f2937]">Fasilitas premium untuk kemudahan sehari-hari.</h2>
                </div>
            </div>
            <div class="mt-8 grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                @foreach (['Kamar Mandi Dalam', 'Dapur Bersama', 'Rooftop Lounge', 'Keamanan 24/7'] as $facility)
                    <div class="rounded-[24px] border border-[#e7e2d8] bg-[#faf8f5] p-5">
                        <p class="font-semibold text-[#1f2937]">{{ $facility }}</p>
                        <p class="mt-2 text-sm leading-7 text-[#4b5563]">Dipersiapkan untuk hunian yang tenang dan modern.</p>
                    </div>
                @endforeach
            </div>
        </section>

        <section id="rooms" class="rounded-3xl border border-[#e7e2d8] bg-white p-6 shadow-sm sm:p-8">
            <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 border-b border-slate-100 pb-5">
                <div>
                    <p class="text-xs font-bold uppercase tracking-[0.24em] text-[#c9a227]">Pilihan Kamar</p>
                    <h2 class="mt-1 text-2xl font-bold text-slate-900">Tipe Kamar Utama</h2>
                </div>
                <a href="{{ route('customer.rooms') }}" class="inline-flex items-center gap-2 text-sm font-bold text-[#c9a227] hover:text-[#b68d1f]">
                    Lihat Semua Kamar ({{ $totalRooms ?? 18 }})
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </a>
            </div>

            <div class="mt-6 overflow-hidden rounded-2xl border border-slate-200 bg-[#faf8f5] shadow-sm transition hover:shadow-md">
                <div class="grid md:grid-cols-12">
                    {{-- Room Image with Badge --}}
                    <div class="relative md:col-span-5 min-h-[220px] md:min-h-full">
                        <img src="{{ $firstRoomImage ?? '' }}" alt="Kamar Mahasiswa" class="h-full w-full object-cover">
                        <div class="absolute top-4 left-4 flex items-center gap-2">
                            <span class="rounded-full bg-emerald-600/90 backdrop-blur px-3 py-1 text-xs font-bold text-white shadow-sm">
                                ✓ {{ $availableRooms }} Kamar Tersedia
                            </span>
                        </div>
                    </div>

                    {{-- Room Details --}}
                    <div class="flex flex-col justify-between p-6 md:col-span-7 sm:p-7">
                        <div>
                            <div class="flex flex-wrap items-center justify-between gap-2">
                                <h3 class="text-2xl font-bold text-slate-900">Kamar Standard Mahasiswa</h3>
                                <span class="rounded-lg bg-[#c9a227]/10 px-3 py-1 text-xs font-bold text-[#c9a227] border border-[#c9a227]/20">
                                    Ukuran {{ $roomSize ?? '3 x 4 m' }}
                                </span>
                            </div>

                            <p class="mt-3 text-sm leading-relaxed text-slate-600">
                                Kamar nyaman siap huni khusus mahasiswa & pekerja dengan fasilitas lengkap. Suasana tenang untuk belajar dan beristirahat.
                            </p>

                            {{-- Spec Badges --}}
                            <div class="mt-5 flex flex-wrap gap-2">
                                <span class="inline-flex items-center gap-1.5 rounded-lg bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 border border-slate-200 shadow-2xs">
                                    Kasur & Lemari
                                </span>
                                <span class="inline-flex items-center gap-1.5 rounded-lg bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 border border-slate-200 shadow-2xs">
                                     KM Dalam
                                </span>
                                <span class="inline-flex items-center gap-1.5 rounded-lg bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 border border-slate-200 shadow-2xs">
                                     Listrik & Air
                                </span>
                                <span class="inline-flex items-center gap-1.5 rounded-lg bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 border border-slate-200 shadow-2xs">
                                         WiFi Gratis
                                </span>
                            </div>
                        </div>

                        {{-- Price & CTA --}}
                        <div class="mt-6 flex flex-wrap items-center justify-between gap-4 border-t border-slate-200/80 pt-5">
                            <div>
                                <p class="text-xs text-slate-400 font-medium">Harga Sewa Bulanan</p>
                                <p class="text-2xl font-extrabold text-slate-900">
                                    Rp{{ number_format($roomPrice, 0, ',', '.') }}
                                    <span class="text-xs font-normal text-slate-500">/ bulan</span>
                                </p>
                            </div>

                            <div class="flex items-center gap-3">
                                <a href="{{ route('customer.rooms') }}" 
                                   class="inline-flex items-center gap-2 rounded-xl bg-[#c9a227] px-6 py-3 text-sm font-bold text-white shadow-md shadow-[#c9a227]/20 transition hover:bg-[#b68d1f] active:scale-[0.98]">
                                    Pilih Kamar
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="grid gap-6 xl:grid-cols-[1.15fr_0.85fr]">
            <div class="rounded-[32px] border border-[#e7e2d8] bg-white p-6 shadow-[0_16px_50px_-32px_rgba(15,23,42,0.14)] sm:p-8">
                <p class="text-sm font-semibold uppercase tracking-[0.24em] text-[#c9a227]">Lokasi</p>
                <p class="mt-1 text-sm text-[#6b7280]">Pedurungan, Semarang</p>
                <div class="mt-4">
                    <x-leaflet-map height="256px" />
                </div>
            </div>

            <div class="rounded-[32px] border border-[#e7e2d8] bg-white p-6 shadow-[0_16px_50px_-32px_rgba(15,23,42,0.14)] sm:p-8">
                <p class="text-sm font-semibold uppercase tracking-[0.24em] text-[#c9a227]">Ulasan</p>
                <div class="mt-6 flex items-center gap-3">
                    <div class="rounded-full bg-[#faf8f5] px-4 py-2 text-sm font-semibold text-[#1f2937]">{{ number_format($avgRating, 1) }}/5 rata-rata</div>
                    <p class="text-sm text-[#4b5563]">Berdasarkan umpan balik penghuni terverifikasi.</p>
                </div>
                @if ($reviews->isNotEmpty())
                    <div class="mt-6 space-y-3">
                        @foreach ($reviews as $review)
                            <div class="rounded-[24px] border border-[#e7e2d8] bg-[#faf8f5] p-4 text-sm text-[#4b5563]">
                                <p class="font-semibold text-[#1f2937]">{{ $review->rating }}/5</p>
                                <p class="mt-2">{{ $review->comment ?: 'Tidak ada komentar.' }}</p>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="mt-6 rounded-[24px] border border-dashed border-[#e7e2d8] bg-[#faf8f5] p-6 text-sm text-[#4b5563]">Belum ada ulasan</div>
                @endif
            </div>
        </section>

        <section class="rounded-[32px] border border-[#e7e2d8] bg-white p-6 shadow-[0_16px_50px_-32px_rgba(15,23,42,0.14)] sm:p-8">
            <div class="flex items-end justify-between gap-4">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-[0.24em] text-[#c9a227]">Pengumuman</p>
                    <h2 class="mt-2 text-2xl font-semibold text-[#1f2937]">Informasi terbaru dari properti.</h2>
                </div>
            </div>
            <div class="mt-8 rounded-[28px] border border-dashed border-[#e7e2d8] bg-[#faf8f5] p-8 text-sm leading-7 text-[#4b5563]">
                @if (!empty($announcements))
                    @foreach ($announcements as $announcement)
                        <div class="rounded-[24px] border border-[#e7e2d8] bg-white p-4">{{ $announcement }}</div>
                    @endforeach
                @else
                    <p>Belum ada pengumuman. Informasi terbaru akan muncul di sini setelah diterbitkan.</p>
                @endif
            </div>
        </section>

        <x-layout.footer />
    </div>
</div>
@endsection

@extends('layouts.dashboard-app')

@section('title', 'Pembayaran Berhasil · ARCHOFESA')

@section('content')
<div class="mx-auto max-w-2xl px-4 py-12 sm:px-6">

    {{-- Spanduk berhasil --}}
    <div class="mb-8 flex flex-col items-center text-center">
        <div class="flex h-16 w-16 items-center justify-center rounded-full bg-green-100 text-green-600">
            <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
        </div>
        <h1 class="mt-4 text-2xl font-semibold text-[#1f2937]">Pembayaran Berhasil!</h1>
        <p class="mt-2 text-sm text-[#6b7280]">Tunjukkan bukti ini kepada penjaga kost saat tanggal masuk.</p>
    </div>

    {{-- ===== BUKTI BOOKING (area yang akan diprint) ===== --}}
    <div id="bukti-print" class="rounded-[32px] border border-[#e7e2d8] bg-white p-8 shadow-sm">

        {{-- Header --}}
        <div class="flex items-center justify-between border-b border-[#e7e2d8] pb-6">
            <div class="flex items-center gap-2">
                <div>
                    <p class="font-bold uppercase tracking-widest text-[#c9a227]">ARCHOFESA KOST</p>
                    <p class="text-xs text-[#6b7280]">Pedurungan, Semarang, Jawa Tengah</p>
                </div>
            </div>
            <div class="text-right">
                <p class="text-xs text-[#6b7280]">Bukti Booking</p>
                <p class="mt-1 text-sm font-bold text-[#1f2937]">{{ $payment->reference ?? 'INV-' . $payment->id }}</p>
            </div>
        </div>

        {{-- Status --}}
        <div class="mt-6 flex items-center gap-3 rounded-[16px] bg-green-50 px-5 py-4">
            <svg class="h-5 w-5 shrink-0 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div>
                <p class="text-sm font-semibold text-green-800">Pembayaran Dikonfirmasi</p>
                <p class="text-xs text-green-600">{{ $payment->paid_at ? $payment->paid_at->format('d F Y, H:i') . ' WIB' : now()->format('d F Y, H:i') . ' WIB' }}</p>
            </div>
        </div>

        {{-- Info Penyewa --}}
        <div class="mt-6">
            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-[#c9a227]">Data Penyewa</p>
            <div class="mt-3 grid grid-cols-2 gap-3">
                <div class="rounded-[14px] bg-[#faf8f5] p-4">
                    <p class="text-xs text-[#6b7280]">Nama</p>
                    <p class="mt-1 font-semibold text-[#1f2937]">{{ $payment->booking->user->name ?? auth()->user()->name }}</p>
                </div>
                <div class="rounded-[14px] bg-[#faf8f5] p-4">
                    <p class="text-xs text-[#6b7280]">Email</p>
                    <p class="mt-1 font-semibold text-[#1f2937]">{{ $payment->booking->user->email ?? auth()->user()->email }}</p>
                </div>
            </div>
        </div>

        {{-- Info Kamar --}}
        <div class="mt-5">
            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-[#c9a227]">Detail Kamar</p>
            <div class="mt-3 grid grid-cols-2 gap-3">
                <div class="rounded-[14px] bg-[#faf8f5] p-4">
                    <p class="text-xs text-[#6b7280]">Nomor Kamar</p>
                    <p class="mt-1 text-xl font-bold text-[#1f2937]">{{ $payment->booking->room_code ?? '-' }}</p>
                </div>
                <div class="rounded-[14px] bg-[#faf8f5] p-4">
                    <p class="text-xs text-[#6b7280]">Tipe</p>
                    <p class="mt-1 font-semibold text-[#1f2937]">
                        {{ optional($payment->booking->room)->price_monthly >= 1400000 ? 'Family Room' : 'Student Room' }}
                    </p>
                </div>
                <div class="rounded-[14px] bg-[#faf8f5] p-4">
                    <p class="text-xs text-[#6b7280]">Tanggal Masuk</p>
                    <p class="mt-1 font-semibold text-[#1f2937]">
                        {{ optional($payment->booking->move_in_date)->format('d F Y') ?? '-' }}
                    </p>
                </div>
                <div class="rounded-[14px] bg-[#faf8f5] p-4">
                    <p class="text-xs text-[#6b7280]">Tanggal Keluar</p>
                    <p class="mt-1 font-semibold text-[#1f2937]">
                        {{ optional($payment->booking->move_out_date)->format('d F Y') ?? '-' }}
                    </p>
                </div>
            </div>
        </div>

        {{-- Pembayaran --}}
        <div class="mt-5">
            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-[#c9a227]">Pembayaran</p>
            <div class="mt-3 rounded-[14px] border border-[#e7e2d8] p-4">
                <div class="flex items-center justify-between">
                    <p class="text-sm text-[#6b7280]">Sewa Bulanan</p>
                    <p class="font-semibold text-[#1f2937]">Rp{{ number_format($payment->amount, 0, ',', '.') }}</p>
                </div>
                <div class="mt-2 flex items-center justify-between border-t border-[#e7e2d8] pt-2">
                    <p class="text-sm font-semibold text-[#1f2937]">Total Dibayar</p>
                    <p class="text-xl font-bold text-[#c9a227]">Rp{{ number_format($payment->amount, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        {{-- Footer --}}
        <div class="mt-6 border-t border-[#e7e2d8] pt-5 text-center">
            <p class="text-xs text-[#9ca3af]">Dokumen ini adalah bukti sah pembayaran kost ARCHOFESA.</p>
            <p class="mt-1 text-xs text-[#9ca3af]">Tunjukkan kepada penjaga kost saat pertama kali tanggal masuk.</p>
            <p class="mt-3 text-xs font-semibold text-[#6b7280]">archofesa.test · Pedurungan, Semarang</p>
        </div>
    </div>

    {{-- Tombol aksi (tidak ikut diprint) --}}
    <div class="no-print mt-6 flex flex-col gap-3 sm:flex-row">
        <button onclick="window.print()"
                class="flex-1 rounded-full bg-[#c9a227] py-3 text-center text-sm font-semibold text-white shadow-lg shadow-[#c9a227]/20 transition hover:bg-[#b68d1f]">
            🖨️ Cetak / Simpan PDF
        </button>
        <a href="{{ route('customer.bookings') }}"
           class="flex-1 rounded-full border border-[#e7e2d8] py-3 text-center text-sm font-semibold text-[#374151] transition hover:border-[#c9a227] hover:text-[#c9a227]">
            Lihat Booking Saya
        </a>
        <a href="{{ route('customer.dashboard') }}"
           class="flex-1 rounded-full border border-[#e7e2d8] py-3 text-center text-sm font-semibold text-[#374151] transition hover:border-[#c9a227] hover:text-[#c9a227]">
            Kembali ke Beranda
        </a>
    </div>
</div>

{{-- Print styles --}}
<style>
@media print {
    body * { visibility: hidden; }
    #bukti-print, #bukti-print * { visibility: visible; }
    #bukti-print { position: absolute; inset: 0; margin: 24px; border: none !important; box-shadow: none !important; }
    .no-print { display: none !important; }
}
</style>
@endsection

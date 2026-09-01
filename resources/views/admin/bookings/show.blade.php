@extends('layouts.admin')

@section('title', 'Detail Booking · Admin ARCHOFESA')

@section('content')
<div class="space-y-6 p-6 lg:p-8">
    {{-- Flash Notifications --}}
    @if(session('success'))
        <div class="flex items-center gap-3 rounded-2xl border border-green-200 bg-green-50 p-4 text-sm font-medium text-green-800 shadow-sm">
            <svg class="h-5 w-5 shrink-0 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif
    @if(session('error'))
        <div class="flex items-center gap-3 rounded-2xl border border-red-200 bg-red-50 p-4 text-sm font-medium text-red-800 shadow-sm">
            <svg class="h-5 w-5 shrink-0 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <div class="rounded-[32px] bg-white p-8 shadow-sm">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
                <a href="{{ route('admin.bookings.index') }}" class="inline-flex items-center gap-2 text-xs font-semibold text-[#c9a227] hover:underline mb-2">
                    &larr; Kembali ke Daftar Booking
                </a>
                <p class="text-sm font-semibold uppercase tracking-[0.24em] text-[#c9a227]">Detail Booking</p>
                <h1 class="mt-2 text-3xl font-semibold text-[#1f2937]">#{{ $booking->id }}</h1>
                <p class="mt-1 text-sm text-[#6b7280]">{{ $booking->user->name ?? 'N/A' }} · {{ $booking->user->email ?? 'N/A' }} · Telp: {{ $booking->user->phone ?? 'Belum terdaftar' }}</p>
            </div>
            <div class="flex flex-wrap items-center gap-3">
                <span class="rounded-full px-3 py-2 text-xs font-semibold uppercase tracking-[0.24em] 
                    {{ in_array($booking->status, ['pending', 'menunggu_pembayaran']) ? 'bg-yellow-100 text-yellow-700' : 
                       ($booking->status === 'dibayar' ? 'bg-blue-100 text-blue-700' : 
                       ($booking->status === 'siap_huni' ? 'bg-indigo-100 text-indigo-700' : 
                       ($booking->status === 'dihuni' ? 'bg-green-100 text-green-700' : 
                       ($booking->status === 'dibatalkan' ? 'bg-red-100 text-red-700' : 'bg-slate-100 text-slate-700')))) }}">
                    {{ str()->headline($booking->status) }}
                </span>
                <span class="rounded-full px-3 py-2 text-xs font-semibold uppercase tracking-[0.24em] {{ $booking->payment_status === 'paid' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                    {{ ucfirst($booking->payment_status) }}
                </span>
            </div>
        </div>

        <div class="mt-8 grid gap-6 lg:grid-cols-2">
            <div class="rounded-[28px] border border-[#e7e2d8] bg-[#faf8f5] p-6">
                <h2 class="text-lg font-semibold text-[#1f2937]">Informasi Sewa & Kamar</h2>
                <div class="mt-5 space-y-4 text-sm text-[#4b5563]">
                    <p><span class="font-semibold text-[#1f2937]">Kode Kamar:</span> {{ $booking->room_code ?? ($booking->room->room_code ?? 'N/A') }}</p>
                    <p><span class="font-semibold text-[#1f2937]">Ukuran Kamar:</span> {{ $booking->room->size ?? '3x4m' }}</p>
                    <p><span class="font-semibold text-[#1f2937]">Biaya Bulanan:</span> Rp{{ number_format($booking->monthly_rate, 0, ',', '.') }}</p>
                    <p><span class="font-semibold text-[#1f2937]">Tanggal Masuk:</span> {{ optional($booking->move_in_date)->format('d M Y') ?? '-' }}</p>
                    <p><span class="font-semibold text-[#1f2937]">Tanggal Keluar:</span> {{ optional($booking->move_out_date)->format('d M Y') ?? '-' }}</p>
                    <p><span class="font-semibold text-[#1f2937]">Catatan Pemesan:</span> {{ $booking->notes ?: 'Tidak ada catatan.' }}</p>
                    @if($booking->owner_notes)
                        <p class="text-amber-800 bg-amber-50 p-3 rounded-xl border border-amber-200"><span class="font-semibold">Catatan Owner:</span> {{ $booking->owner_notes }}</p>
                    @endif
                </div>
            </div>

            <div class="rounded-[28px] border border-[#e7e2d8] bg-[#faf8f5] p-6 flex flex-col justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-[#1f2937]">Tindakan & Operasional</h2>
                    <div class="mt-5 space-y-4 text-sm text-[#4b5563]">
                        @if ($booking->status === 'pending')
                            <form method="POST" action="{{ route('admin.bookings.process-payment', $booking) }}" class="space-y-3">
                                @csrf
                                <input type="hidden" name="payment_method" value="midtrans">
                                <div class="rounded-[16px] border border-[#e7e2d8] bg-white px-4 py-3 text-sm text-[#374151]">
                                    <span class="font-semibold text-[#1f2937]">Metode Tagihan:</span> Gateway Midtrans Snap
                                </div>
                                <button type="submit" class="w-full rounded-full bg-[#c9a227] px-5 py-3 text-sm font-semibold text-white transition hover:bg-[#b68d1f]">Terbitkan Tagihan Pembayaran</button>
                            </form>
                        @elseif ($booking->status === 'dibayar')
                            <form method="POST" action="{{ route('admin.bookings.confirm-to-owner', $booking) }}">
                                @csrf
                                <button type="submit" class="w-full rounded-full bg-[#2563eb] px-5 py-3 text-sm font-semibold text-white transition hover:bg-[#1d4ed8]">Teruskan ke Pemilik untuk Konfirmasi</button>
                            </form>
                        @elseif ($booking->status === 'siap_huni')
                            <form method="POST" action="{{ route('admin.bookings.confirm-ready-to-occupy', $booking) }}">
                                @csrf
                                <button type="submit" class="w-full rounded-full bg-[#16a34a] px-5 py-3 text-sm font-semibold text-white transition hover:bg-[#15803d]">Aktifkan Status Huni (Dihuni)</button>
                            </form>
                        @else
                            <p class="text-sm text-slate-500">Status saat ini: <strong class="text-slate-800">{{ str()->headline($booking->status) }}</strong>.</p>
                        @endif
                    </div>
                </div>

                {{-- Danger Zone: Delete Booking --}}
                <div class="mt-8 border-t border-[#e7e2d8] pt-5">
                    <p class="text-xs font-semibold text-red-600 uppercase tracking-wider mb-2">Zona Berbahaya</p>
                    <form method="POST" action="{{ route('admin.bookings.destroy', $booking) }}" onsubmit="return confirm('PERINGATAN: Apakah Anda yakin ingin menghapus data booking #{{ $booking->id }} ini secara permanen?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full rounded-full border border-red-300 bg-red-50 px-5 py-2.5 text-sm font-semibold text-red-700 transition hover:bg-red-600 hover:text-white">
                            Hapus Booking Ini Secara Permanen
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

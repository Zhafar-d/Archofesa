@extends('layouts.admin')

@section('title', 'Detail Booking · Admin ARCHOFESA')

@section('content')
<div class="space-y-6 p-6 lg:p-8">
        <div class="rounded-[32px] bg-white p-8 shadow-sm">
            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-[0.24em] text-[#c9a227]">Detail Booking</p>
                    <h1 class="mt-2 text-3xl font-semibold text-[#1f2937]">#{{ $booking->id }}</h1>
                    <p class="mt-1 text-sm text-[#6b7280]">{{ $booking->user->name }} · {{ $booking->user->email }}</p>
                </div>
                <div class="flex flex-wrap gap-3">
                    <span class="rounded-full px-3 py-2 text-xs font-semibold uppercase tracking-[0.24em] {{ $booking->status === 'pending' ? 'bg-yellow-100 text-yellow-700' : ($booking->status === 'dibayar' ? 'bg-blue-100 text-blue-700' : ($booking->status === 'dihuni' ? 'bg-green-100 text-green-700' : 'bg-slate-100 text-slate-700')) }}">{{ str()->headline($booking->status) }}</span>
                    <span class="rounded-full px-3 py-2 text-xs font-semibold uppercase tracking-[0.24em] {{ $booking->payment_status === 'paid' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">{{ ucfirst($booking->payment_status) }}</span>
                </div>
            </div>

            <div class="mt-8 grid gap-6 lg:grid-cols-2">
                <div class="rounded-[28px] border border-[#e7e2d8] bg-[#faf8f5] p-6">
                    <h2 class="text-lg font-semibold text-[#1f2937]">Informasi Booking</h2>
                    <div class="mt-5 space-y-4 text-sm text-[#4b5563]">
                        <p><span class="font-semibold text-[#1f2937]">Kamar:</span> {{ $booking->room_code ?? ($booking->room->room_code ?? 'N/A') }}</p>
                        <p><span class="font-semibold text-[#1f2937]">Jenis:</span> {{ $booking->room->size ?? 'Standar' }}</p>
                        <p><span class="font-semibold text-[#1f2937]">Biaya Bulanan:</span> Rp{{ number_format($booking->monthly_rate, 0, ',', '.') }}</p>
                        <p><span class="font-semibold text-[#1f2937]">Tanggal Masuk:</span> {{ optional($booking->move_in_date)->format('d M Y') }}</p>
                        <p><span class="font-semibold text-[#1f2937]">Tanggal Keluar:</span> {{ optional($booking->move_out_date)->format('d M Y') }}</p>
                    </div>
                </div>

                <div class="rounded-[28px] border border-[#e7e2d8] bg-[#faf8f5] p-6">
                    <h2 class="text-lg font-semibold text-[#1f2937]">Catatan & Tindakan</h2>
                    <div class="mt-5 space-y-4 text-sm text-[#4b5563]">
                        <p><span class="font-semibold text-[#1f2937]">Catatan:</span> {{ $booking->notes ?? 'Tidak ada catatan.' }}</p>
                        <p><span class="font-semibold text-[#1f2937]">Daftar Tindakan:</span></p>
                        @if ($booking->status === 'pending')
                            <form method="POST" action="{{ route('admin.bookings.process-payment', $booking) }}" class="space-y-4">
                                @csrf
                                <input type="hidden" name="payment_method" value="midtrans">
                                <div class="rounded-[16px] border border-[#e7e2d8] bg-white px-4 py-3 text-sm text-[#374151]">
                                    <span class="font-semibold text-[#1f2937]">Metode Pembayaran:</span> Midtrans
                                </div>
                                <button type="submit" class="rounded-full bg-[#c9a227] px-5 py-3 text-sm font-semibold text-white transition hover:bg-[#b68d1f]">Buat Tagihan Midtrans</button>
                            </form>
                        @elseif ($booking->status === 'dibayar')
                            <form method="POST" action="{{ route('admin.bookings.confirm-to-owner', $booking) }}">
                                @csrf
                                <button type="submit" class="rounded-full bg-[#2563eb] px-5 py-3 text-sm font-semibold text-white transition hover:bg-[#1d4ed8]">Teruskan ke Pemilik untuk Konfirmasi</button>
                            </form>
                        @elseif ($booking->status === 'siap_huni')
                            <form method="POST" action="{{ route('admin.bookings.confirm-ready-to-occupy', $booking) }}">
                                @csrf
                                <button type="submit" class="rounded-full bg-[#16a34a] px-5 py-3 text-sm font-semibold text-white transition hover:bg-[#15803d]">Konfirmasi ke User bahwa Kos Siap Huni</button>
                            </form>
                        @else
                            <p class="text-sm text-[#4b5563]">Tidak ada tindakan tambahan untuk status ini.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

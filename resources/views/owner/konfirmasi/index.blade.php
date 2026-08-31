@extends('layouts.owner')

@section('title', 'Konfirmasi Booking · Pemilik ARCHOFESA')

@section('content')
<div class="px-6 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-slate-900">Konfirmasi Booking</h1>
        <p class="mt-2 text-slate-600">Konfirmasi kamar siap huni untuk booking yang menunggu verifikasi.</p>
    </div>

    <div class="space-y-6">
        @if(session('success'))
            <div class="rounded-2xl border border-green-200 bg-green-50 p-4 text-sm text-green-700">{{ session('success') }}</div>
        @endif

        @forelse ($bookings as $booking)
            <div class="rounded-[32px] border border-[#e7e2d8] bg-white p-6 shadow-sm">
                <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                    <div>
                        <p class="text-sm font-semibold uppercase tracking-[0.24em] text-[#c9a227]">Booking #{{ $booking->id }}</p>
                        <h2 class="mt-2 text-2xl font-semibold text-[#1f2937]">{{ $booking->room_code ?? ($booking->room->room_code ?? 'Kamar belum ditentukan') }}</h2>
                        <p class="mt-2 text-sm text-[#4b5563]">{{ $booking->user->name }} · Rp{{ number_format($booking->monthly_rate, 0, ',', '.') }} / bulan</p>
                        <p class="mt-2 text-sm text-[#4b5563]">Masuk: {{ optional($booking->move_in_date)->format('d M Y') }} · Keluar: {{ optional($booking->move_out_date)->format('d M Y') }}</p>
                    </div>
                    <form method="POST" action="{{ route('owner.konfirmasi.confirm', $booking) }}" class="flex items-center gap-3">
                        @csrf
                        <button type="submit" class="rounded-full bg-[#16a34a] px-5 py-3 text-sm font-semibold text-white transition hover:bg-[#15803d]">Konfirmasi Kamar Siap Huni</button>
                    </form>
                </div>
            </div>
        @empty
            <div class="rounded-[32px] border border-[#e7e2d8] bg-[#faf8f5] p-10 text-center text-sm text-slate-600">
                Tidak ada booking yang perlu dikonfirmasi.
            </div>
        @endforelse
    </div>
</div>
@endsection

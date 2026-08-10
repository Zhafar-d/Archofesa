@extends('layouts.dashboard-app')

@section('title', 'Extend Rental · ARCHOFESA KOST')

@section('content')
<div class="px-6 py-8">
    <div class="mb-8 max-w-2xl">
        <h1 class="text-3xl font-bold text-slate-900">Perpanjang Sewa Kamar</h1>
        <p class="mt-2 text-slate-600">Ajukan perpanjangan durasi sewa untuk kamar Anda saat ini.</p>
    </div>

    <div class="max-w-2xl rounded-[32px] border border-[#e7e2d8] bg-white p-6 shadow-[0_16px_50px_-32px_rgba(15,23,42,0.14)] sm:p-8">
        <div class="mb-6 rounded-[24px] border border-[#e7e2d8] bg-[#faf8f5] p-5">
            <p class="text-sm text-[#6b7280]">Kamar Saat Ini</p>
            <p class="mt-1 font-semibold text-[#1f2937]">Kamar {{ $booking->room_code }} ({{ $booking->room && $booking->room->price_monthly >= 1400000 ? 'Family Room' : 'Student Room' }})</p>
            <p class="mt-2 text-sm text-[#6b7280]">Berakhir pada: <span class="font-semibold text-[#1f2937]">{{ optional($booking->move_out_date)->format('d F Y') }}</span></p>
        </div>

        <form action="{{ route('customer.extend', $booking) }}" method="POST">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700">Durasi Perpanjangan (Bulan)</label>
                    <select name="months" class="mt-1 w-full rounded-lg border border-slate-200 bg-slate-50 px-4 py-2">
                        @for ($i = 1; $i <= 12; $i++)
                            <option value="{{ $i }}">{{ $i }} Bulan</option>
                        @endfor
                    </select>
                    @error('months') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                </div>
                
                <div class="pt-4">
                    <button type="submit" class="w-full rounded-lg bg-[#c9a227] px-6 py-3 font-semibold text-white transition hover:bg-[#b68d1f]">
                        Ajukan Perpanjangan
                    </button>
                    <div class="mt-3 text-center">
                        <a href="{{ route('customer.dashboard') }}" class="text-sm text-slate-500 hover:text-slate-700">Batal</a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

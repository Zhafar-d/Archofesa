@extends('layouts.owner')

@section('title', 'Konfirmasi Booking · Pemilik ARCHOFESA')

@section('content')
<div class="px-6 py-8" x-data="{ rejectModal: null }">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-slate-900">Konfirmasi Kesiapan Kamar</h1>
        <p class="mt-2 text-slate-600">Konfirmasi kamar siap huni untuk booking lunas, atau tolak dengan alasan jika kamar belum siap secara fisik.</p>
    </div>

    <div class="space-y-6">
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

        @forelse ($bookings as $booking)
            <div class="rounded-[32px] border border-[#e7e2d8] bg-white p-6 shadow-sm">
                <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="text-xs font-bold uppercase tracking-[0.24em] text-[#c9a227]">Booking #{{ $booking->id }}</span>
                            <span class="rounded-full bg-blue-50 px-2.5 py-0.5 text-xs font-semibold text-blue-700">Lunas / Menunggu Pemilik</span>
                        </div>
                        <h2 class="mt-2 text-2xl font-bold text-[#1f2937]">{{ $booking->room_code ?? ($booking->room->room_code ?? 'Kamar belum ditentukan') }}</h2>
                        <p class="mt-1 text-sm text-[#4b5563]">Pemesan: <strong class="text-slate-900">{{ $booking->user->name ?? 'Penyewa' }}</strong> (Telp: {{ $booking->user->phone ?? 'Belum ada no WA' }})</p>
                        <p class="mt-1 text-sm text-[#4b5563]">Biaya Sewa: <strong class="text-[#c9a227]">Rp{{ number_format($booking->monthly_rate, 0, ',', '.') }}</strong> / bulan</p>
                        <p class="mt-1 text-xs text-slate-500">Rencana Masuk: {{ optional($booking->move_in_date)->format('d M Y') ?? '-' }} · Estimasi Keluar: {{ optional($booking->move_out_date)->format('d M Y') ?? '-' }}</p>
                        @if($booking->notes)
                            <p class="mt-2 text-xs bg-slate-50 p-2.5 rounded-xl text-slate-600 border border-slate-200"><span class="font-semibold">Catatan Penyewa:</span> {{ $booking->notes }}</p>
                        @endif
                    </div>
                    
                    <div class="flex items-center gap-3">
                        {{-- Tombol Tolak Booking --}}
                        <button type="button" @click="rejectModal = 'reject{{ $booking->id }}'" class="rounded-full border border-red-200 bg-white px-5 py-3 text-sm font-semibold text-red-600 transition hover:bg-red-50">
                            Tolak Booking
                        </button>

                        {{-- Tombol Setujui / Konfirmasi Siap Huni --}}
                        <form method="POST" action="{{ route('owner.konfirmasi.confirm', $booking) }}">
                            @csrf
                            <button type="submit" class="rounded-full bg-[#16a34a] px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-[#15803d]">
                                Konfirmasi Kamar Siap Huni
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Modal Tolak Booking --}}
            <div x-show="rejectModal === 'reject{{ $booking->id }}'" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 transform scale-95"
                 x-transition:enter-end="opacity-100 transform scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 transform scale-100"
                 x-transition:leave-end="opacity-0 transform scale-95"
                 class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-sm"
                 style="display: none;">
                <div class="w-full max-w-md rounded-[32px] bg-white p-6 shadow-xl border border-[#e7e2d8]" @click.away="rejectModal = null">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                        <h3 class="text-lg font-bold text-red-600">Tolak Konfirmasi Booking #{{ $booking->id }}</h3>
                        <button @click="rejectModal = null" class="rounded-full p-1 text-slate-400 hover:bg-slate-100">&times;</button>
                    </div>
                    <form method="POST" action="{{ route('owner.konfirmasi.reject', $booking) }}" class="mt-4 space-y-4">
                        @csrf
                        <div>
                            <p class="text-xs text-slate-500">Kamar: <strong class="text-slate-800">{{ $booking->room_code }}</strong> · Pemesan: <strong class="text-slate-800">{{ $booking->user->name }}</strong></p>
                            <p class="mt-1 text-xs text-slate-500">Status kamar akan otomatis dikembalikan ke <em>'Tersedia'</em>.</p>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700">Alasan Penolakan</label>
                            <textarea name="reason" rows="3" placeholder="Contoh: Kamar sedang ada renovasi darurat / kebocoran pipa..." class="mt-2 w-full rounded-2xl border border-[#e7e2d8] p-3 text-sm focus:border-red-400 focus:outline-none" required></textarea>
                        </div>
                        <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                            <button type="button" @click="rejectModal = null" class="rounded-full border border-slate-200 px-4 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-50">Batal</button>
                            <button type="submit" class="rounded-full bg-red-600 px-5 py-2 text-xs font-bold text-white hover:bg-red-700">Kirim Penolakan</button>
                        </div>
                    </form>
                </div>
            </div>
        @empty
            <div class="rounded-[32px] border border-[#e7e2d8] bg-[#faf8f5] p-12 text-center">
                <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-green-100 text-green-600 mb-3">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <p class="text-base font-semibold text-slate-800">Semua Booking Sudah Dikonfirmasi</p>
                <p class="mt-1 text-sm text-slate-500">Tidak ada pengajuan booking baru yang sedang menunggu persetujuan Anda saat ini.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection

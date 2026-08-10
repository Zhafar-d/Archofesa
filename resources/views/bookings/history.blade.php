@extends('layouts.app')

@section('title', 'Riwayat Booking · ARCHOFESA')

@section('content')
<div class="mx-auto max-w-6xl px-4 py-16 sm:px-6 lg:px-8">
    <div class="mb-8 rounded-[36px] border border-[#e7e2d8] bg-white p-8 shadow-[0_24px_70px_-32px_rgba(15,23,42,0.16)]">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.24em] text-[#c9a227]">Riwayat Booking</p>
                <h1 class="mt-3 text-3xl font-semibold text-[#1f2937]">Semua histori reservasi kamu</h1>
                <p class="mt-2 text-sm leading-7 text-[#4b5563]">Lihat status booking, durasi, dan nilai sewa untuk setiap reservasi.</p>
            </div>
            <a href="{{ route('booking') }}" class="inline-flex items-center rounded-full bg-[#c9a227] px-5 py-3 text-sm font-semibold text-white transition hover:bg-[#b68d1f]">Buat booking baru</a>
        </div>
    </div>

    <div class="space-y-4">
        @forelse ($bookings as $booking)
            <div class="rounded-[32px] border border-[#e7e2d8] bg-white p-6 shadow-sm">
                <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                    <div>
                        <p class="text-sm font-semibold uppercase tracking-[0.20em] text-[#c9a227]">Booking #{{ $booking->id }}</p>
                        <h2 class="mt-2 text-2xl font-semibold text-[#1f2937]">{{ $booking->room_code ?? ($booking->room->room_code ?? 'Kamar belum ditentukan') }}</h2>
                        <p class="mt-2 text-sm text-[#4b5563]">{{ optional($booking->move_in_date)->format('d M Y') }} sampai {{ optional($booking->move_out_date)->format('d M Y') }}</p>
                    </div>
                    <div class="space-y-2 text-right">
                        <p class="text-sm text-[#6b7280]">Status pembayaran</p>
                        <span class="inline-flex rounded-full border border-[#e7e2d8] bg-[#f8fafc] px-3 py-1 text-sm font-semibold text-[#1f2937]">{{ ucfirst($booking->payment_status) }}</span>
                    </div>
                </div>

                @php
                    $progressSteps = [
                        'pending' => 'Pending',
                        'menunggu_pembayaran' => 'Menunggu Bayar',
                        'dibayar' => 'Dibayar',
                        'menunggu_konfirmasi_owner' => 'Menunggu Konfirmasi',
                        'siap_huni' => 'Siap Huni',
                        'dihuni' => 'Dihuni',
                    ];
                @endphp

                <div class="mt-6 overflow-x-auto">
                    <div class="flex min-w-full items-center gap-4">
                        @foreach ($progressSteps as $key => $label)
                            @php
                                $isActive = $booking->step_aktif !== false && $booking->step_aktif >= $loop->index;
                                $textColor = $isActive ? 'text-slate-900' : 'text-slate-400';
                                $bgColor = $isActive ? 'bg-[#c9a227] text-white border-[#c9a227]' : 'bg-white text-slate-400 border-slate-200';
                            @endphp

                            <div class="flex flex-col items-center gap-2 text-center">
                                <div class="flex h-9 w-9 items-center justify-center rounded-full border {{ $bgColor }}">
                                    <span class="text-sm font-semibold">{{ $loop->iteration }}</span>
                                </div>
                                <span class="text-xs font-semibold uppercase tracking-[0.2em] {{ $textColor }}">{{ $label }}</span>
                            </div>

                            @if (! $loop->last)
                                <div class="flex-1 border-t border-slate-200"></div>
                            @endif
                        @endforeach
                    </div>
                </div>

                <div class="mt-6 grid gap-4 sm:grid-cols-3">
                    <div class="rounded-[24px] border border-[#e7e2d8] bg-[#faf8f5] p-4">
                        <p class="text-sm text-[#6b7280]">Biaya bulanan</p>
                        <p class="mt-2 text-lg font-semibold text-[#1f2937]">Rp{{ number_format($booking->monthly_rate, 0, ',', '.') }}</p>
                    </div>
                    <div class="rounded-[24px] border border-[#e7e2d8] bg-[#faf8f5] p-4">
                        <p class="text-sm text-[#6b7280]">Status booking</p>
                        <p class="mt-2 text-lg font-semibold text-[#1f2937]">{{ ucfirst($booking->status) }}</p>
                    </div>
                    <div class="rounded-[24px] border border-[#e7e2d8] bg-[#faf8f5] p-4">
                        <p class="text-sm text-[#6b7280]">Catatan</p>
                        <p class="mt-2 text-sm text-[#4b5563]">{{ $booking->notes ?? 'Tidak ada catatan khusus.' }}</p>
                    </div>
                </div>

                @if ($booking->status === 'dihuni')
                    <div class="mt-6 rounded-[24px] border border-[#e7e2d8] bg-[#faf8f5] p-6">
                        <h3 class="text-lg font-semibold text-[#1f2937]">Tagihan Bulanan</h3>
                        <p class="mt-1 text-sm text-[#6b7280]">Daftar invoice dari pembayaran yang terkait dengan booking ini.</p>

                        @if ($booking->payments->isEmpty())
                            <div class="mt-4 rounded-2xl border border-dashed border-[#c9a227] bg-white p-4 text-sm text-[#4b5563]">
                                Tidak ada tagihan bulanan untuk booking ini.
                            </div>
                        @else
                            <div class="mt-4 space-y-3">
                                @foreach ($booking->payments as $payment)
                                    <div class="rounded-3xl border border-[#e7e2d8] bg-white p-4">
                                        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                                            <div>
                                                <p class="text-sm text-[#6b7280]">Invoice #{{ $payment->id }}</p>
                                                <p class="mt-1 text-base font-semibold text-[#1f2937]">Rp{{ number_format($payment->amount, 0, ',', '.') }}</p>
                                            </div>
                                            <div class="space-y-1 text-right">
                                                <p class="text-sm text-[#6b7280]">{{ optional($payment->paid_at)->format('d M Y') ?? 'Belum dibayar' }}</p>
                                                <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold uppercase tracking-[0.2em] {{ $payment->status === 'paid' ? 'bg-green-100 text-green-700' : ($payment->status === 'pending' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') }}">
                                                    {{ $payment->status === 'paid' ? 'Lunas' : ucfirst($payment->status) }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        @empty
            <div class="rounded-[32px] border border-[#e7e2d8] bg-[#faf8f5] p-10 text-center">
                <p class="text-sm font-semibold text-[#1f2937]">Belum ada riwayat booking.</p>
                <p class="mt-2 text-sm text-[#4b5563]">Silakan buat booking baru untuk mulai merekam reservasi Anda.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection

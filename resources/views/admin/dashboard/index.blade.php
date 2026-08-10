@extends('layouts.admin')

@section('title', 'Dashboard Admin · ARCHOFESA KOST')

@section('content')
<div class="space-y-6 p-6 lg:p-8">

        <div>
            <h1 class="text-2xl font-bold text-[#1f2937]">Dashboard Admin</h1>
            <p class="mt-1 text-sm text-[#6b7280]">{{ now()->format('l, d F Y') }}</p>
        </div>

        {{-- ── Kartu Statistik ─────────────────────────────────────────────── --}}
        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <div class="rounded-[24px] border border-[#e7e2d8] bg-white p-6 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-[#c9a227]">Pemasukan Bulan Ini</p>
                <p class="mt-3 text-3xl font-bold text-[#1f2937]">Rp{{ number_format($revenueThisMonth / 1000000, 1, ',', '.') }}jt</p>
                <p class="mt-1 text-xs {{ $revenueTrend >= 0 ? 'text-green-600' : 'text-red-500' }}">
                    {{ $revenueTrend >= 0 ? '▲' : '▼' }} {{ abs($revenueTrend) }}% vs bulan lalu
                </p>
            </div>
            <div class="rounded-[24px] border border-[#e7e2d8] bg-white p-6 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-[#6b7280]">Total Pemasukan</p>
                <p class="mt-3 text-3xl font-bold text-[#1f2937]">Rp{{ number_format($revenueTotal / 1000000, 1, ',', '.') }}jt</p>
                <p class="mt-1 text-xs text-[#6b7280]">Semua transaksi lunas</p>
            </div>
            <div class="rounded-[24px] border border-[#e7e2d8] bg-white p-6 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-yellow-600">Tagihan Menunggu</p>
                <p class="mt-3 text-3xl font-bold text-[#1f2937]">{{ $pendingPayments }}</p>
                <p class="mt-1 text-xs text-[#6b7280]">Rp{{ number_format($pendingAmount, 0, ',', '.') }} belum dibayar</p>
            </div>
            <div class="rounded-[24px] border border-[#e7e2d8] bg-white p-6 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-[#6b7280]">Kamar Terisi</p>
                <p class="mt-3 text-3xl font-bold text-[#1f2937]">{{ $occupiedRooms }}<span class="text-lg font-normal text-[#9ca3af]">/{{ $totalRooms }}</span></p>
                <p class="mt-1 text-xs text-[#6b7280]">{{ $availableRooms }} kamar tersedia</p>
            </div>
        </div>

        {{-- ── Grafik Pemasukan + Statistik Booking ─────────────────────────── --}}
        <div class="grid gap-6 lg:grid-cols-[1.4fr_0.6fr]">
            <div class="rounded-[28px] border border-[#e7e2d8] bg-white p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold uppercase tracking-[0.2em] text-[#c9a227]">Pemasukan</p>
                        <h2 class="mt-1 text-lg font-bold text-[#1f2937]">6 Bulan Terakhir</h2>
                    </div>
                </div>
                @php $maxRevenue = $monthlyRevenue->max('amount') ?: 1; @endphp
                <div class="mt-6 flex items-end gap-2" style="height:160px">
                    @foreach($monthlyRevenue as $item)
                        @php
                            $pct = ($item['amount'] / $maxRevenue) * 100;
                            $isThisMonth = $item['month'] === now()->format('M Y');
                        @endphp
                        <div class="flex flex-1 flex-col items-center gap-1">
                            <p class="text-[10px] font-semibold text-[#1f2937]">
                                {{ $item['amount'] > 0 ? 'Rp' . number_format($item['amount']/1000000, 1) . 'jt' : '-' }}
                            </p>
                            <div class="w-full rounded-t-lg transition-all {{ $isThisMonth ? 'bg-[#c9a227]' : 'bg-[#e7e2d8]' }}"
                                 style="height:{{ max($pct, 4) }}%"></div>
                            <p class="text-[10px] text-[#9ca3af]">{{ \Carbon\Carbon::parse('01 ' . $item['month'])->format('M') }}</p>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="rounded-[28px] border border-[#e7e2d8] bg-white p-6 shadow-sm">
                <p class="text-sm font-semibold uppercase tracking-[0.2em] text-[#c9a227]">Booking</p>
                <h2 class="mt-1 text-lg font-bold text-[#1f2937]">Statistik</h2>
                <div class="mt-6 space-y-3">
                    <div class="flex items-center justify-between rounded-[16px] bg-[#faf8f5] px-4 py-3">
                        <p class="text-sm text-[#374151]">Total Booking</p>
                        <p class="text-lg font-bold text-[#1f2937]">{{ $totalBookings }}</p>
                    </div>
                    <div class="flex items-center justify-between rounded-[16px] bg-yellow-50 px-4 py-3">
                        <p class="text-sm text-yellow-700">Menunggu</p>
                        <p class="text-lg font-bold text-yellow-700">{{ $pendingBookings }}</p>
                    </div>
                    <div class="flex items-center justify-between rounded-[16px] bg-green-50 px-4 py-3">
                        <p class="text-sm text-green-700">Total Pengguna</p>
                        <p class="text-lg font-bold text-green-700">{{ $totalUsers }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── Transaksi Terbaru + Tagihan Menunggu ─────────────────────────────── --}}
        <div class="grid gap-6 lg:grid-cols-2">
            <div class="rounded-[28px] border border-[#e7e2d8] bg-white p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold uppercase tracking-[0.2em] text-[#c9a227]">Transaksi Lunas</p>
                        <h2 class="mt-1 text-base font-bold text-[#1f2937]">Pembayaran Terbaru</h2>
                    </div>
                    <a href="{{ route('admin.pembayaran.index') }}" class="text-xs font-semibold text-[#c9a227] hover:underline">Lihat Semua</a>
                </div>
                <div class="mt-5 space-y-3">
                    @forelse($recentPayments as $pay)
                        <div class="flex items-center justify-between rounded-[16px] bg-[#faf8f5] px-4 py-3">
                            <div>
                                <p class="text-sm font-semibold text-[#1f2937]">{{ optional($pay->booking->user)->name ?? 'Tidak Diketahui' }}</p>
                                <p class="text-xs text-[#6b7280]">{{ optional($pay->booking)->room_code ?? '-' }} · {{ optional($pay->paid_at)->format('d M Y') ?? '-' }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-bold text-[#1f2937]">Rp{{ number_format($pay->amount, 0, ',', '.') }}</p>
                                <span class="inline-flex rounded-full bg-green-100 px-2 py-0.5 text-xs font-semibold text-green-700">Lunas</span>
                            </div>
                        </div>
                    @empty
                        <div class="rounded-[16px] border border-dashed border-[#e7e2d8] p-6 text-center text-sm text-[#6b7280]">Belum ada transaksi lunas.</div>
                    @endforelse
                </div>
            </div>

            <div class="rounded-[28px] border border-[#e7e2d8] bg-white p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold uppercase tracking-[0.2em] text-yellow-600">Belum Dibayar</p>
                        <h2 class="mt-1 text-base font-bold text-[#1f2937]">Tagihan Menunggu</h2>
                    </div>
                    <a href="{{ route('admin.pembayaran.index') }}" class="text-xs font-semibold text-[#c9a227] hover:underline">Lihat Semua</a>
                </div>
                <div class="mt-5 space-y-3">
                    @forelse($pendingPaymentList as $pay)
                        <div class="flex items-center justify-between rounded-[16px] bg-yellow-50 px-4 py-3">
                            <div>
                                <p class="text-sm font-semibold text-[#1f2937]">{{ optional($pay->booking->user)->name ?? 'Tidak Diketahui' }}</p>
                                <p class="text-xs text-[#6b7280]">{{ optional($pay->booking)->room_code ?? '-' }} · {{ $pay->created_at->format('d M Y') }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-bold text-[#1f2937]">Rp{{ number_format($pay->amount, 0, ',', '.') }}</p>
                                <span class="inline-flex rounded-full bg-yellow-100 px-2 py-0.5 text-xs font-semibold text-yellow-700">Menunggu</span>
                            </div>
                        </div>
                    @empty
                        <div class="rounded-[16px] border border-dashed border-[#e7e2d8] p-6 text-center text-sm text-[#6b7280]">Tidak ada tagihan menunggu.</div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- ── Booking Terbaru + User Terbaru ──────────────────────────── --}}
        <div class="grid gap-6 lg:grid-cols-2">
            <div class="rounded-[28px] border border-[#e7e2d8] bg-white p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold uppercase tracking-[0.2em] text-[#c9a227]">Booking</p>
                        <h2 class="mt-1 text-base font-bold text-[#1f2937]">Booking Terbaru</h2>
                    </div>
                    <a href="{{ route('admin.bookings.index') }}" class="text-xs font-semibold text-[#c9a227] hover:underline">Lihat Semua</a>
                </div>
                <div class="mt-5 space-y-3">
                    @forelse($latestBookings as $booking)
                        @php
                            $sc = match($booking->status) {
                                'dihuni','siap_huni','dibayar'                   => 'bg-green-100 text-green-700',
                                'menunggu_pembayaran','menunggu_konfirmasi_owner'=> 'bg-yellow-100 text-yellow-700',
                                'pending'                                        => 'bg-blue-100 text-blue-700',
                                default                                          => 'bg-slate-100 text-slate-600',
                            };
                            $sl = match($booking->status) {
                                'pending'                    => 'Menunggu',
                                'menunggu_pembayaran'        => 'Menunggu Bayar',
                                'dibayar'                    => 'Dibayar',
                                'menunggu_konfirmasi_owner'  => 'Konfirmasi',
                                'siap_huni'                  => 'Siap Huni',
                                'dihuni'                     => 'Dihuni',
                                default                      => ucfirst($booking->status),
                            };
                        @endphp
                        <div class="flex items-center justify-between rounded-[16px] bg-[#faf8f5] px-4 py-3">
                            <div>
                                <p class="text-sm font-semibold text-[#1f2937]">{{ optional($booking->user)->name ?? 'Tidak Diketahui' }}</p>
                                <p class="text-xs text-[#6b7280]">Kamar {{ $booking->room_code ?? '-' }} · {{ $booking->created_at->format('d M Y') }}</p>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="rounded-full px-2 py-0.5 text-xs font-semibold {{ $sc }}">{{ $sl }}</span>
                                <a href="{{ route('admin.bookings.show', $booking) }}" class="text-xs font-semibold text-[#c9a227] hover:underline">Detail</a>
                            </div>
                        </div>
                    @empty
                        <div class="rounded-[16px] border border-dashed border-[#e7e2d8] p-6 text-center text-sm text-[#6b7280]">Belum ada booking.</div>
                    @endforelse
                </div>
            </div>

            <div class="rounded-[28px] border border-[#e7e2d8] bg-white p-6 shadow-sm">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-[0.2em] text-[#c9a227]">Pengguna</p>
                    <h2 class="mt-1 text-base font-bold text-[#1f2937]">Pengguna Terbaru</h2>
                </div>
                <div class="mt-5 space-y-3">
                    @forelse($recentUsers as $user)
                        <div class="flex items-center gap-3 rounded-[16px] bg-[#faf8f5] px-4 py-3">
                            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-[#c9a227] text-sm font-bold text-white">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="truncate text-sm font-semibold text-[#1f2937]">{{ $user->name }}</p>
                                <p class="truncate text-xs text-[#6b7280]">{{ $user->email }}</p>
                            </div>
                            <p class="shrink-0 text-xs text-[#9ca3af]">{{ $user->created_at->diffForHumans() }}</p>
                        </div>
                    @empty
                        <div class="rounded-[16px] border border-dashed border-[#e7e2d8] p-6 text-center text-sm text-[#6b7280]">Belum ada pengguna.</div>
                    @endforelse
                </div>
            </div>
        </div>

</div>
@endsection

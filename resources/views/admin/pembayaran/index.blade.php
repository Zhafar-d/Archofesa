@extends('layouts.admin')

@section('title', 'Manajemen Keuangan · Admin ARCHOFESA')

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
                <h1 class="text-2xl font-semibold text-[#1f2937]">Manajemen Keuangan & Transaksi</h1>
                <p class="mt-1 text-sm text-[#6b7280]">Pantau transaksi pembayaran online Midtrans dan kelola data kas.</p>
            </div>
            <form method="GET" class="flex flex-col gap-3 sm:flex-row sm:items-center">
                <select name="status" class="rounded-2xl border border-[#e7e2d8] bg-white px-4 py-2.5 text-sm text-[#374151]">
                    <option value="">Semua Status</option>
                    <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Lunas / Paid</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="batal" {{ request('status') === 'batal' ? 'selected' : '' }}>Batal</option>
                </select>
                <button type="submit" class="rounded-full bg-[#c9a227] px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-[#b68d1f]">Filter</button>
            </form>
        </div>

        <div class="mt-8 grid gap-6 lg:grid-cols-2">
            <x-dashboard.stat-card title="Transaksi Berhasil" value="{{ $summary['success_count'] }}" detail="Jumlah transaksi berstatus lunas" />
            <x-dashboard.stat-card title="Total Nominal Masuk" value="Rp{{ number_format($summary['success_amount'], 0, ',', '.') }}" detail="Total akumulasi pendapatan lunas" />
        </div>

        <div class="mt-8 overflow-hidden rounded-[28px] border border-[#e7e2d8] bg-white">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-sm text-left">
                    <thead class="bg-[#faf8f5]">
                        <tr>
                            <th class="px-6 py-4 font-semibold text-slate-600">ID</th>
                            <th class="px-6 py-4 font-semibold text-slate-600">Nama Pembayar</th>
                            <th class="px-6 py-4 font-semibold text-slate-600">Booking #</th>
                            <th class="px-6 py-4 font-semibold text-slate-600">Metode</th>
                            <th class="px-6 py-4 font-semibold text-slate-600">Jumlah</th>
                            <th class="px-6 py-4 font-semibold text-slate-600">Status</th>
                            <th class="px-6 py-4 font-semibold text-slate-600 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 bg-white">
                        @forelse ($payments as $payment)
                            <tr class="transition hover:bg-slate-50/70">
                                <td class="px-6 py-4 font-semibold text-slate-700">#{{ $payment->id }}</td>
                                <td class="px-6 py-4">
                                    <p class="font-medium text-slate-900">{{ $payment->booking?->user->name ?? ($payment->user?->name ?? 'N/A') }}</p>
                                    <p class="text-xs text-slate-500">{{ $payment->booking?->user->phone ?? ($payment->booking?->user->email ?? '-') }}</p>
                                </td>
                                <td class="px-6 py-4 text-slate-700">
                                    @if($payment->booking_id)
                                        <a href="{{ route('admin.bookings.show', $payment->booking_id) }}" class="font-semibold text-[#c9a227] hover:underline">
                                            #{{ $payment->booking_id }}
                                        </a>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="px-6 py-4 font-medium text-slate-700">{{ strtoupper($payment->payment_method ?? 'MIDTRANS') }}</td>
                                <td class="px-6 py-4 font-semibold text-slate-900">Rp{{ number_format($payment->amount, 0, ',', '.') }}</td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold uppercase tracking-wider {{ $payment->status === 'paid' ? 'bg-green-100 text-green-700' : ($payment->status === 'pending' ? 'bg-yellow-100 text-yellow-700' : 'bg-slate-100 text-slate-700') }}">
                                        {{ ucfirst($payment->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        @if ($payment->payment_method === 'cod' && $payment->status !== 'paid')
                                            <form method="POST" action="{{ route('admin.pembayaran.mark-paid', $payment) }}" class="inline">
                                                @csrf
                                                <button type="submit" class="rounded-full bg-[#16a34a] px-3 py-1 text-xs font-semibold text-white transition hover:bg-[#15803d]">Tandai Lunas</button>
                                            </form>
                                        @endif
                                        <form method="POST" action="{{ route('admin.pembayaran.destroy', $payment) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data pembayaran #{{ $payment->id }} ini?');" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="rounded-full border border-red-200 bg-white px-3 py-1 text-xs font-semibold text-red-600 transition hover:bg-red-50" title="Hapus Transaksi">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-8 text-center text-sm text-slate-500">Belum ada data transaksi pembayaran.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-6">{{ $payments->withQueryString()->links() }}</div>
    </div>
</div>
@endsection

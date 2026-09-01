@extends('layouts.admin')

@section('title', 'Booking Masuk · Admin ARCHOFESA')

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
                <h1 class="text-2xl font-semibold text-[#1f2937]">Booking Masuk</h1>
                <p class="mt-1 text-sm text-[#6b7280]">Kelola semua data pemesanan kamar dan aksi hapus/batal.</p>
            </div>
            <form method="GET" class="flex flex-col gap-3 sm:flex-row sm:items-center">
                <select name="status" class="rounded-2xl border border-[#e7e2d8] bg-white px-4 py-3 text-sm text-[#374151]">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Menunggu</option>
                    <option value="menunggu_pembayaran" {{ request('status') === 'menunggu_pembayaran' ? 'selected' : '' }}>Menunggu Pembayaran</option>
                    <option value="dibayar" {{ request('status') === 'dibayar' ? 'selected' : '' }}>Sudah Dibayar</option>
                    <option value="menunggu_konfirmasi_owner" {{ request('status') === 'menunggu_konfirmasi_owner' ? 'selected' : '' }}>Menunggu Konfirmasi Pemilik</option>
                    <option value="siap_huni" {{ request('status') === 'siap_huni' ? 'selected' : '' }}>Siap Huni</option>
                    <option value="dihuni" {{ request('status') === 'dihuni' ? 'selected' : '' }}>Dihuni</option>
                    <option value="selesai" {{ request('status') === 'selesai' ? 'selected' : '' }}>Selesai</option>
                    <option value="dibatalkan" {{ request('status') === 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                </select>
                <input type="search" name="search" placeholder="Cari nama pemesan..." value="{{ request('search') }}" class="rounded-2xl border border-[#e7e2d8] bg-white px-4 py-3 text-sm text-[#374151]" />
                <button type="submit" class="rounded-full bg-[#c9a227] px-5 py-3 text-sm font-semibold text-white transition hover:bg-[#b68d1f]">Cari</button>
            </form>
        </div>

        <div class="mt-8 overflow-hidden rounded-[28px] border border-[#e7e2d8] bg-white">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-sm text-left">
                    <thead class="bg-[#faf8f5]">
                        <tr>
                            <th class="px-6 py-4 font-semibold text-slate-600">ID</th>
                            <th class="px-6 py-4 font-semibold text-slate-600">Nama Pemesan</th>
                            <th class="px-6 py-4 font-semibold text-slate-600">Kamar</th>
                            <th class="px-6 py-4 font-semibold text-slate-600">Status</th>
                            <th class="px-6 py-4 font-semibold text-slate-600">Pembayaran</th>
                            <th class="px-6 py-4 font-semibold text-slate-600">Tanggal Masuk</th>
                            <th class="px-6 py-4 font-semibold text-slate-600 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 bg-white">
                        @forelse ($bookings as $booking)
                            <tr class="transition hover:bg-slate-50/70">
                                <td class="px-6 py-4 font-semibold text-slate-700">#{{ $booking->id }}</td>
                                <td class="px-6 py-4">
                                    <p class="font-medium text-slate-900">{{ $booking->user->name ?? 'User #' . $booking->user_id }}</p>
                                    <p class="text-xs text-slate-500">{{ $booking->user->phone ?? $booking->user->email }}</p>
                                </td>
                                <td class="px-6 py-4 font-medium text-slate-700">{{ $booking->room_code ?? ($booking->room->room_code ?? 'N/A') }}</td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold uppercase tracking-wider 
                                        {{ in_array($booking->status, ['pending', 'menunggu_pembayaran']) ? 'bg-yellow-100 text-yellow-700' : 
                                           ($booking->status === 'dibayar' ? 'bg-blue-100 text-blue-700' : 
                                           ($booking->status === 'siap_huni' ? 'bg-indigo-100 text-indigo-700' : 
                                           ($booking->status === 'dihuni' ? 'bg-green-100 text-green-700' : 
                                           ($booking->status === 'dibatalkan' ? 'bg-red-100 text-red-700' : 'bg-slate-100 text-slate-700')))) }}">
                                        {{ str()->headline($booking->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center gap-1.5 text-xs font-medium {{ $booking->payment_status === 'paid' ? 'text-green-600' : 'text-amber-600' }}">
                                        <span class="h-1.5 w-1.5 rounded-full {{ $booking->payment_status === 'paid' ? 'bg-green-600' : 'bg-amber-600' }}"></span>
                                        {{ ucfirst($booking->payment_status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-slate-600">{{ optional($booking->move_in_date)->format('d M Y') ?? '-' }}</td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('admin.bookings.show', $booking) }}" class="rounded-full border border-[#e7e2d8] bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 transition hover:bg-[#faf8f5] hover:text-[#c9a227]">
                                            Detail
                                        </a>
                                        <form method="POST" action="{{ route('admin.bookings.destroy', $booking) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data booking #{{ $booking->id }} ini? Transaksi dan riwayat terkait akan dibersihkan.');" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="rounded-full border border-red-200 bg-white px-3 py-1.5 text-xs font-semibold text-red-600 transition hover:bg-red-50" title="Hapus Booking">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-8 text-center text-sm text-slate-500">Tidak ada data booking yang ditemukan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-6">{{ $bookings->withQueryString()->links() }}</div>
    </div>
</div>
@endsection

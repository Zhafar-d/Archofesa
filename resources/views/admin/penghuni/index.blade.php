@extends('layouts.admin')

@section('title', 'Manajemen Penghuni · Admin ARCHOFESA')

@section('content')
<div class="space-y-6 p-6 lg:p-8" x-data="{ activeModal: null }">
    {{-- Flash Notifications --}}
    @if(session('success'))
        <div class="flex flex-col gap-3 rounded-2xl border border-green-200 bg-green-50 p-4 text-sm font-medium text-green-800 shadow-sm sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-center gap-3">
                <svg class="h-5 w-5 shrink-0 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                <span>{{ session('success') }}</span>
            </div>
            @if(session('wa_url'))
                <a href="{{ session('wa_url') }}" target="_blank" class="inline-flex items-center gap-1.5 rounded-full bg-[#25D366] px-4 py-1.5 text-xs font-bold text-white shadow-sm transition hover:bg-[#1ebd59]">
                    <span>📱 Buka Chat WhatsApp Langsung</span> &rarr;
                </a>
            @endif
        </div>
    @endif
    @if(session('error'))
        <div class="flex flex-col gap-3 rounded-2xl border border-red-200 bg-red-50 p-4 text-sm font-medium text-red-800 shadow-sm sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-center gap-3">
                <svg class="h-5 w-5 shrink-0 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>{{ session('error') }}</span>
            </div>
            @if(session('wa_url'))
                <a href="{{ session('wa_url') }}" target="_blank" class="inline-flex items-center gap-1.5 rounded-full bg-[#25D366] px-4 py-1.5 text-xs font-bold text-white shadow-sm transition hover:bg-[#1ebd59]">
                    <span>📱 Buka Chat Manual di WhatsApp</span> &rarr;
                </a>
            @endif
        </div>
    @endif

    <div class="rounded-[32px] bg-white p-8 shadow-sm">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-[#1f2937]">Manajemen Penghuni Aktif</h1>
                <p class="mt-1 text-sm text-[#6b7280]">Kelola penghuni kamar kos, kirim pengingat WhatsApp bot, perbarui masa sewa, dan proses check-out.</p>
            </div>
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                <form method="GET" class="flex items-center gap-2">
                    <input type="search" name="search" placeholder="Cari nama / nomor HP..." value="{{ request('search') }}" class="rounded-2xl border border-[#e7e2d8] bg-white px-4 py-2.5 text-sm text-[#374151]" />
                    <button type="submit" class="rounded-full bg-[#c9a227] px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-[#b68d1f]">Cari</button>
                </form>
            </div>
        </div>

        <div class="mt-8 overflow-hidden rounded-[28px] border border-[#e7e2d8] bg-white">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-sm text-left">
                    <thead class="bg-[#faf8f5]">
                        <tr>
                            <th class="px-6 py-4 font-semibold text-slate-600">Penghuni</th>
                            <th class="px-6 py-4 font-semibold text-slate-600">Kamar</th>
                            <th class="px-6 py-4 font-semibold text-slate-600">Mulai Huni</th>
                            <th class="px-6 py-4 font-semibold text-slate-600">Jatuh Tempo</th>
                            <th class="px-6 py-4 font-semibold text-slate-600">Sisa Waktu</th>
                            <th class="px-6 py-4 font-semibold text-slate-600 text-center">Aksi & WhatsApp Bot</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 bg-white">
                        @forelse ($tenants as $booking)
                            @php
                                $daysRemaining = $booking->move_out_date ? (int) now()->startOfDay()->diffInDays($booking->move_out_date->startOfDay(), false) : null;
                            @endphp
                            <tr class="transition hover:bg-slate-50/70">
                                <td class="px-6 py-4">
                                    <p class="font-semibold text-slate-900">{{ $booking->user->name ?? 'User #' . $booking->user_id }}</p>
                                    <p class="text-xs text-slate-500">📱 {{ $booking->user->phone ?? 'Belum ada nomor WA' }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="font-bold text-[#c9a227]">{{ $booking->room_code ?? ($booking->room->room_code ?? 'N/A') }}</span>
                                    <p class="text-xs text-slate-500">{{ $booking->room->size ?? '3x4m' }}</p>
                                </td>
                                <td class="px-6 py-4 text-slate-700">{{ optional($booking->move_in_date)->format('d M Y') ?? '-' }}</td>
                                <td class="px-6 py-4 text-slate-700 font-medium">{{ optional($booking->move_out_date)->format('d M Y') ?? '-' }}</td>
                                <td class="px-6 py-4">
                                    @if($daysRemaining !== null)
                                        @if($daysRemaining > 5)
                                            <span class="inline-flex rounded-full bg-green-50 px-2.5 py-1 text-xs font-semibold text-green-700">
                                                {{ $daysRemaining }} hari lagi
                                            </span>
                                        @elseif($daysRemaining >= 0)
                                            <span class="inline-flex rounded-full bg-amber-50 px-2.5 py-1 text-xs font-semibold text-amber-700 animate-pulse">
                                                ⚠️ {{ $daysRemaining }} hari lagi
                                            </span>
                                        @else
                                            <span class="inline-flex rounded-full bg-red-50 px-2.5 py-1 text-xs font-semibold text-red-700">
                                                Lewat {{ abs($daysRemaining) }} hari
                                            </span>
                                        @endif
                                    @else
                                        <span class="text-xs text-slate-400">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center flex-wrap gap-2">
                                        {{-- Tombol Kirim Pengingat WhatsApp Bot --}}
                                        <form method="POST" action="{{ route('admin.penghuni.send-reminder', $booking) }}" class="inline">
                                            @csrf
                                            <button type="submit" class="inline-flex items-center gap-1.5 rounded-full bg-[#25D366] px-3 py-1.5 text-xs font-bold text-white transition hover:bg-[#1ebd59]" title="Kirim Pesan WhatsApp Pengingat">
                                                <span>WA Bot</span>
                                            </button>
                                        </form>

                                        {{-- Tombol Perpanjang / Ubah Tanggal --}}
                                        <button type="button" @click="activeModal = 'extend{{ $booking->id }}'" class="rounded-full border border-[#e7e2d8] bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 transition hover:bg-[#faf8f5] hover:text-[#c9a227]">
                                            Ubah Tempo
                                        </button>

                                        {{-- Tombol Check-Out --}}
                                        <form method="POST" action="{{ route('admin.penghuni.checkout', $booking) }}" onsubmit="return confirm('Konfirmasi Check-Out: Apakah penghuni kamar {{ $booking->room_code }} telah selesai menghuni dan siap mengembalikan kunci? Status kamar akan otomatis kembali menjadi Tersedia.');" class="inline">
                                            @csrf
                                            <button type="submit" class="rounded-full border border-blue-200 bg-blue-50 px-3 py-1.5 text-xs font-semibold text-blue-700 transition hover:bg-blue-600 hover:text-white" title="Check-Out Penghuni">
                                                Check-Out
                                            </button>
                                        </form>

                                        {{-- Tombol Hapus --}}
                                        <form method="POST" action="{{ route('admin.penghuni.destroy', $booking) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data penghuni ini secara permanen?');" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="rounded-full border border-red-200 bg-white px-3 py-1.5 text-xs font-semibold text-red-600 transition hover:bg-red-50" title="Hapus Data Penghuni">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>

                            {{-- Modal Ubah Tanggal Jatuh Tempo --}}
                            <div x-show="activeModal === 'extend{{ $booking->id }}'" 
                                 x-transition:enter="transition ease-out duration-300"
                                 x-transition:enter-start="opacity-0 transform scale-95"
                                 x-transition:enter-end="opacity-100 transform scale-100"
                                 x-transition:leave="transition ease-in duration-200"
                                 x-transition:leave-start="opacity-100 transform scale-100"
                                 x-transition:leave-end="opacity-0 transform scale-95"
                                 class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-sm"
                                 style="display: none;">
                                <div class="w-full max-w-md rounded-[32px] bg-white p-6 shadow-xl border border-[#e7e2d8]" @click.away="activeModal = null">
                                    <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                                        <h3 class="text-lg font-bold text-slate-900">Ubah Masa Sewa / Jatuh Tempo</h3>
                                        <button @click="activeModal = null" class="rounded-full p-1 text-slate-400 hover:bg-slate-100">&times;</button>
                                    </div>
                                    <form method="POST" action="{{ route('admin.penghuni.update-due-date', $booking) }}" class="mt-4 space-y-4">
                                        @csrf
                                        @method('PUT')
                                        <div>
                                            <p class="text-xs font-medium text-slate-500">Penghuni: <strong class="text-slate-800">{{ $booking->user->name }}</strong></p>
                                            <p class="text-xs font-medium text-slate-500">Kamar: <strong class="text-[#c9a227]">{{ $booking->room_code }}</strong></p>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-semibold text-slate-700">Tanggal Jatuh Tempo / Berakhir Baru</label>
                                            <input type="date" name="move_out_date" value="{{ old('move_out_date', $booking->move_out_date?->format('Y-m-d')) }}" class="mt-2 w-full rounded-2xl border border-[#e7e2d8] px-4 py-3 text-sm focus:border-[#c9a227] focus:outline-none" required>
                                        </div>
                                        <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                                            <button type="button" @click="activeModal = null" class="rounded-full border border-slate-200 px-4 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-50">Batal</button>
                                            <button type="submit" class="rounded-full bg-[#c9a227] px-5 py-2 text-xs font-bold text-white hover:bg-[#b68d1f]">Simpan Perubahan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-sm text-slate-500">Belum ada penghuni aktif yang terdaftar.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-6">{{ $tenants->withQueryString()->links() }}</div>
    </div>
</div>
@endsection

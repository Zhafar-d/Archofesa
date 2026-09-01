@extends('layouts.owner')

@section('title', 'Manajemen Kamar · Pemilik ARCHOFESA')

@section('content')
<div class="px-6 py-8">
    {{-- Flash Alerts --}}
    @if(session('success'))
        <div class="mb-6 flex items-center gap-3 rounded-2xl border border-green-200 bg-green-50 p-4 text-sm font-medium text-green-800 shadow-sm">
            <svg class="h-5 w-5 shrink-0 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif
    @if(session('error'))
        <div class="mb-6 flex items-center gap-3 rounded-2xl border border-red-200 bg-red-50 p-4 text-sm font-medium text-red-800 shadow-sm">
            <svg class="h-5 w-5 shrink-0 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <div class="rounded-[32px] border border-[#e7e2d8] bg-white p-8 shadow-sm">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Manajemen Kamar Kos</h1>
                <p class="mt-1 text-sm text-slate-500">Kelola inventaris kamar kos, ubah status ketersediaan, perbarui tarif bulanan, dan kelola foto.</p>
            </div>
            <a href="{{ route('owner.kamar.create') }}" class="inline-flex items-center gap-2 rounded-full bg-[#c9a227] px-6 py-3 text-sm font-bold text-white shadow-md shadow-[#c9a227]/20 transition hover:bg-[#b68d1f]">
                <span>+ Tambah Kamar Baru</span>
            </a>
        </div>

        <div class="mt-8 overflow-hidden rounded-[28px] border border-[#e7e2d8] bg-white">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-sm text-left">
                    <thead class="bg-[#faf8f5]">
                        <tr>
                            <th class="px-6 py-4 font-semibold text-slate-600">Kode Kamar</th>
                            <th class="px-6 py-4 font-semibold text-slate-600">Ukuran</th>
                            <th class="px-6 py-4 font-semibold text-slate-600">Tarif Bulanan</th>
                            <th class="px-6 py-4 font-semibold text-slate-600">Status Fisik</th>
                            <th class="px-6 py-4 font-semibold text-slate-600 text-center">Aksi Pengelola</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 bg-white">
                        @forelse ($rooms as $room)
                            <tr class="transition hover:bg-slate-50/70">
                                <td class="px-6 py-4">
                                    <span class="font-bold text-slate-900">{{ $room->room_code }}</span>
                                </td>
                                <td class="px-6 py-4 text-slate-700">{{ $room->size }}</td>
                                <td class="px-6 py-4 font-semibold text-[#c9a227]">Rp{{ number_format($room->price_monthly, 0, ',', '.') }}</td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold uppercase tracking-wider 
                                        {{ $room->status === 'available' ? 'bg-green-100 text-green-700' : 
                                           ($room->status === 'occupied' ? 'bg-blue-100 text-blue-700' : 'bg-amber-100 text-amber-700') }}">
                                        {{ ucfirst($room->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('owner.kamar.edit', $room) }}" class="rounded-full border border-[#e7e2d8] bg-white px-4 py-1.5 text-xs font-semibold text-slate-700 transition hover:bg-[#faf8f5] hover:text-[#c9a227]">
                                            Edit
                                        </a>
                                        <form method="POST" action="{{ route('owner.kamar.destroy', $room) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kamar {{ $room->room_code }}?');" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="rounded-full border border-red-200 bg-white px-4 py-1.5 text-xs font-semibold text-red-600 transition hover:bg-red-50" title="Hapus Kamar">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-sm text-slate-500">Belum ada kamar kos yang didaftarkan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-6">{{ $rooms->links() }}</div>
    </div>
</div>
@endsection

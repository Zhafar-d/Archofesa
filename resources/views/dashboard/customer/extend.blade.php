@extends('layouts.dashboard-app')

@section('title', 'Perpanjang Sewa · ARCHOFESA KOST')

@section('content')
<div class="px-4 py-6 sm:px-6 lg:px-8">
    <div class="mx-auto max-w-2xl">
        
        <!-- Header -->
        <div class="mb-6 sm:mb-8">
            <div class="inline-flex items-center gap-2 rounded-full bg-[#faf8f5] px-3.5 py-1 text-xs font-semibold uppercase tracking-wider text-[#c9a227] border border-[#e7e2d8]">
                <span>Perpanjangan Sewa</span>
            </div>
            <h1 class="mt-3 text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">Perpanjang Masa Tinggal</h1>
            <p class="mt-1 text-xs sm:text-sm text-slate-500">Lanjutkan kenyamanan tinggal Anda di ARCHOFESA KOST</p>
        </div>

        @if (session('error'))
            <div class="mb-6 rounded-2xl border border-rose-200 bg-rose-50 px-5 py-4 text-xs sm:text-sm font-medium text-rose-800">
                {{ session('error') }}
            </div>
        @endif

        <div class="rounded-3xl border border-[#e7e2d8] bg-white p-6 sm:p-8 shadow-sm">
            
            <!-- Ringkasan Kamar Saat Ini -->
            <div class="rounded-2xl border border-slate-100 bg-[#faf8f5] p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="text-xs font-semibold uppercase text-[#c9a227]">{{ $booking->room && $booking->room->price_monthly >= 1400000 ? 'Family Room' : 'Student Room' }}</span>
                        <h2 class="mt-0.5 text-lg font-bold text-slate-900">Kamar {{ $booking->room_code }}</h2>
                    </div>
                    <span class="rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 px-3 py-1 text-xs font-semibold">
                        Aktif Dihuni
                    </span>
                </div>

                <div class="mt-4 grid grid-cols-2 gap-3 border-t border-slate-200/80 pt-3 text-xs">
                    <div>
                        <span class="text-slate-400">Tarif Bulanan</span>
                        <p class="mt-0.5 font-bold text-slate-800">Rp{{ number_format($booking->monthly_rate, 0, ',', '.') }}/bln</p>
                    </div>
                    <div>
                        <span class="text-slate-400">Masa Sewa Berakhir</span>
                        <p class="mt-0.5 font-bold text-slate-800" id="current-expiry">{{ optional($booking->move_out_date)->translatedFormat('d F Y') ?? '-' }}</p>
                    </div>
                </div>
            </div>

            <!-- Form Perpanjangan -->
            <form action="{{ route('customer.extend', $booking) }}" method="POST" class="mt-6 space-y-6">
                @csrf

                <div>
                    <label for="months" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                        Pilih Durasi Tambahan
                    </label>
                    <select id="months" name="months" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-900 focus:border-[#c9a227] focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#c9a227] transition">
                        @for ($i = 1; $i <= 12; $i++)
                            <option value="{{ $i }}" {{ old('months', 1) == $i ? 'selected' : '' }}>
                                + {{ $i }} Bulan
                            </option>
                        @endfor
                    </select>
                    @error('months') 
                        <span class="mt-1 block text-xs text-rose-600">{{ $message }}</span> 
                    @enderror
                </div>

                <!-- Kalkulasi Baru -->
                <div class="rounded-2xl border border-dashed border-slate-200 bg-slate-50 p-4 space-y-2 text-xs">
                    <div class="flex items-center justify-between text-slate-600">
                        <span>Tanggal Berakhir Baru (Estimasi)</span>
                        <span class="font-bold text-slate-900" id="new-expiry-date">-</span>
                    </div>
                    <div class="flex items-center justify-between text-slate-600 pt-2 border-t border-slate-200/60">
                        <span>Total Biaya Perpanjangan</span>
                        <span class="text-sm font-extrabold text-slate-900" id="total-price">-</span>
                    </div>
                </div>

                <div class="space-y-3 pt-2">
                    <button type="submit" class="w-full rounded-xl bg-[#c9a227] py-3 text-xs sm:text-sm font-bold text-white shadow-sm transition hover:bg-[#b68d1f] active:scale-[0.99]">
                        Kirim Pengajuan Perpanjangan
                    </button>
                    <a href="{{ route('customer.dashboard') }}" class="block w-full text-center rounded-xl border border-slate-200 py-2.5 text-xs font-semibold text-slate-600 transition hover:bg-slate-50">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const selectMonths = document.getElementById('months');
        const elNewExpiry = document.getElementById('new-expiry-date');
        const elTotalPrice = document.getElementById('total-price');

        const monthlyRate = {{ (float) $booking->monthly_rate }};
        const currentExpiryRaw = "{{ $booking->move_out_date ? $booking->move_out_date->format('Y-m-d') : date('Y-m-d') }}";

        function updateCalculation() {
            const months = parseInt(selectMonths.value) || 1;
            const currentExpiry = new Date(currentExpiryRaw);
            currentExpiry.setMonth(currentExpiry.getMonth() + months);

            const options = { day: 'numeric', month: 'long', year: 'numeric' };
            elNewExpiry.textContent = currentExpiry.toLocaleDateString('id-ID', options);

            const total = monthlyRate * months;
            elTotalPrice.textContent = 'Rp' + total.toLocaleString('id-ID');
        }

        selectMonths.addEventListener('change', updateCalculation);
        updateCalculation();
    });
</script>
@endpush
@endsection

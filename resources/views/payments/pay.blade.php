@extends('layouts.dashboard-app')

@section('title', 'Bayar Tagihan · ARCHOFESA')

@section('content')
<div class="mx-auto max-w-2xl px-4 py-12 sm:px-6">

    <a href="{{ route('customer.payments') }}" class="inline-flex items-center gap-2 text-sm font-medium text-[#6b7280] transition hover:text-[#c9a227]">
        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Kembali ke Pembayaran
    </a>

    <div class="mt-8 rounded-[32px] border border-[#e7e2d8] bg-white p-8 shadow-sm">
        <p class="text-sm font-semibold uppercase tracking-[0.24em] text-[#c9a227]">Tagihan</p>
        <h1 class="mt-3 text-2xl font-semibold text-[#1f2937]">Konfirmasi Pembayaran</h1>
        <p class="mt-2 text-sm text-[#6b7280]">Invoice <strong>{{ $payment->reference ?? '#' . $payment->id }}</strong></p>

        <div class="mt-6 grid gap-4 sm:grid-cols-2">
            <div class="rounded-[20px] bg-[#faf8f5] p-5">
                <p class="text-sm text-[#6b7280]">Total Pembayaran</p>
                <p class="mt-2 text-3xl font-bold text-[#1f2937]">Rp{{ number_format($payment->amount, 0, ',', '.') }}</p>
            </div>
            <div class="rounded-[20px] bg-[#faf8f5] p-5">
                <p class="text-sm text-[#6b7280]">Untuk Booking</p>
                <p class="mt-2 text-lg font-semibold text-[#1f2937]">
                    {{ $payment->booking ? 'Kamar ' . $payment->booking->room_code : 'Booking #' . $payment->booking_id }}
                </p>
                <p class="text-sm text-[#6b7280]">Sewa Bulanan</p>
            </div>
        </div>

        <div class="mt-6 rounded-[20px] border border-[#e7e2d8] p-4 text-sm text-[#4b5563]">
            <p class="font-semibold text-[#1f2937]">Metode Pembayaran</p>
            <p class="mt-1">Midtrans — Transfer Bank, QRIS, e-Wallet, Kartu Kredit, dan lainnya</p>
        </div>

        <div class="mt-8">
            <button id="pay-button"
                    class="inline-flex w-full items-center justify-center gap-2 rounded-full bg-[#c9a227] px-6 py-4 text-sm font-semibold text-white shadow-lg shadow-[#c9a227]/20 transition hover:bg-[#b68d1f] disabled:opacity-60">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                Bayar Sekarang
            </button>
        </div>

        <p class="mt-4 text-center text-xs text-[#9ca3af]">
            Pembayaran diproses secara aman oleh Midtrans.
        </p>
    </div>
</div>

@if(config('midtrans.is_production'))
    <script src="https://app.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
@else
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
@endif

<script>
const btn        = document.getElementById('pay-button');
const confirmUrl = '{{ route('payment.confirm', $payment) }}';
const csrfToken  = '{{ csrf_token() }}';

async function sendConfirm(result) {
    btn.disabled    = true;
    btn.textContent = 'Memverifikasi...';
    try {
        const res  = await fetch(confirmUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
            },
            body: JSON.stringify({ result }),
        });
        const data = await res.json();
        if (data.redirect) {
            window.location.href = data.redirect;
        }
    } catch (e) {
        window.location.href = '{{ route('customer.payments') }}?status=pending';
    }
}

btn.addEventListener('click', function () {
    btn.disabled    = true;
    btn.textContent = 'Memproses...';

    snap.pay('{{ $snapToken }}', {
        onSuccess : (result) => sendConfirm(result),
        onPending : (result) => sendConfirm(result),
        onError   : (_)      => {
            btn.disabled    = false;
            btn.textContent = 'Bayar Sekarang';
            alert('Gagal memproses pembayaran. Silakan coba lagi.');
        },
        onClose   : ()       => {
            btn.disabled    = false;
            btn.textContent = 'Bayar Sekarang';
        },
    });
});
</script>
@endsection

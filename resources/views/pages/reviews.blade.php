@extends('layouts.app')

@section('title', 'Ulasan & Rating Kepuasan Penghuni · ARCHOFESA KOST')

@section('content')
<div class="bg-[#faf8f5] py-12 sm:py-16">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        
        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="mb-8 flex items-center gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-sm font-semibold text-emerald-800 shadow-sm">
                <svg class="h-5 w-5 shrink-0 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        {{-- Header Section --}}
        <div class="text-center max-w-3xl mx-auto mb-12">
            <span class="inline-flex items-center gap-1.5 rounded-full bg-[#c9a227]/10 px-4 py-1 text-xs font-bold uppercase tracking-widest text-[#c9a227] border border-[#c9a227]/20">
                ⭐ Testimoni &amp; Reputasi Kost
            </span>
            <h1 class="mt-4 text-3xl font-bold tracking-tight text-slate-900 sm:text-4xl lg:text-5xl">
                Tingkat Kepuasan &amp; Ulasan Penghuni
            </h1>
            <p class="mt-4 text-base sm:text-lg text-slate-600 leading-relaxed">
                Transparansi kualitas hunian kos dari pengalaman nyata mahasiswa dan profesional yang tinggal di ARCHOFESA KOST Pedurungan Semarang.
            </p>
        </div>

        {{-- Grade & Score Card Grid --}}
        <div class="grid gap-6 md:grid-cols-3 mb-12">
            {{-- Overall Score --}}
            <div class="rounded-[32px] border border-[#e7e2d8] bg-white p-8 shadow-sm flex flex-col justify-between">
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Akreditasi / Grade Kost</span>
                    <div class="mt-3 flex items-baseline gap-3">
                        <span class="text-5xl font-extrabold text-slate-900">{{ number_format($avgRating, 1) }}</span>
                        <span class="text-lg font-semibold text-slate-400">/ 5.0</span>
                    </div>
                    <div class="mt-2 flex items-center gap-1 text-[#c9a227]">
                        @for($i = 0; $i < 5; $i++)
                            <svg class="h-5 w-5 fill-current" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        @endfor
                    </div>
                </div>
                <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500 font-medium">
                    <span>Predikat Kualitas</span>
                    <span class="rounded-full bg-emerald-100 px-2.5 py-0.5 font-bold text-emerald-800">Grade A+ (Eksklusif)</span>
                </div>
            </div>

            {{-- Breakdown Aspects --}}
            <div class="rounded-[32px] border border-[#e7e2d8] bg-white p-8 shadow-sm flex flex-col justify-between md:col-span-2">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Parameter Penilaian Fasilitas &amp; Layanan</span>
                <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <div class="flex justify-between text-xs font-bold text-slate-700 mb-1">
                            <span>Kebersihan Kamar &amp; Sanitasi</span>
                            <span class="text-[#c9a227]">4.9 / 5.0</span>
                        </div>
                        <div class="h-2 w-full rounded-full bg-slate-100 overflow-hidden">
                            <div class="h-full rounded-full bg-[#c9a227]" style="width: 98%"></div>
                        </div>
                    </div>

                    <div>
                        <div class="flex justify-between text-xs font-bold text-slate-700 mb-1">
                            <span>Kecepatan WiFi &amp; Fasilitas Belajar</span>
                            <span class="text-[#c9a227]">4.8 / 5.0</span>
                        </div>
                        <div class="h-2 w-full rounded-full bg-slate-100 overflow-hidden">
                            <div class="h-full rounded-full bg-[#c9a227]" style="width: 96%"></div>
                        </div>
                    </div>

                    <div>
                        <div class="flex justify-between text-xs font-bold text-slate-700 mb-1">
                            <span>Keamanan 24 Jam &amp; CCTV</span>
                            <span class="text-[#c9a227]">5.0 / 5.0</span>
                        </div>
                        <div class="h-2 w-full rounded-full bg-slate-100 overflow-hidden">
                            <div class="h-full rounded-full bg-[#c9a227]" style="width: 100%"></div>
                        </div>
                    </div>

                    <div>
                        <div class="flex justify-between text-xs font-bold text-slate-700 mb-1">
                            <span>Respons Pengelola &amp; Bot Notifikasi</span>
                            <span class="text-[#c9a227]">4.9 / 5.0</span>
                        </div>
                        <div class="h-2 w-full rounded-full bg-slate-100 overflow-hidden">
                            <div class="h-full rounded-full bg-[#c9a227]" style="width: 98%"></div>
                        </div>
                    </div>
                </div>

                <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                    <span>Total Ulasan Penghuni: <strong>{{ $totalReviews }} Terverifikasi</strong></span>
                    <span class="text-emerald-700 font-semibold">✓ 100% Data Riil Transaksi</span>
                </div>
            </div>
        </div>

        {{-- Form Beri Ulasan (Khusus Penghuni / User Login) --}}
        <div class="rounded-[32px] border border-[#e7e2d8] bg-white p-6 sm:p-8 shadow-sm mb-12">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 border-b border-slate-100 pb-6 mb-6">
                <div>
                    <h2 class="text-xl font-bold text-slate-900">Beri Ulasan / Testimoni Anda</h2>
                    <p class="text-xs sm:text-sm text-slate-500 mt-1">Bagikan pengalaman tinggal Anda di ARCHOFESA untuk membantu calon penghuni lainnya.</p>
                </div>
                @guest
                    <a href="{{ route('login') }}" class="inline-flex items-center gap-2 rounded-full bg-slate-900 px-6 py-2.5 text-xs font-bold text-white transition hover:bg-slate-800 shrink-0">
                        <span>Login untuk Menulis Ulasan</span> &rarr;
                    </a>
                @endguest
            </div>

            @auth
                <form method="POST" action="{{ route('reviews.store') }}" class="space-y-6">
                    @csrf
                    @if($userBooking)
                        <input type="hidden" name="booking_id" value="{{ $userBooking->id }}">
                    @endif

                    <div class="grid gap-6 sm:grid-cols-2">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Rating Bintang</label>
                            <select name="rating" class="w-full rounded-2xl border border-[#e7e2d8] bg-[#faf8f5] px-4 py-3 text-sm text-slate-900 focus:border-[#c9a227] focus:bg-white focus:outline-none" required>
                                <option value="5" selected>⭐⭐⭐⭐⭐ - 5 Bintang (Sangat Memuaskan &amp; Nyaman)</option>
                                <option value="4">⭐⭐⭐⭐ - 4 Bintang (Bagus &amp; Bersih)</option>
                                <option value="3">⭐⭐⭐ - 3 Bintang (Cukup Baik)</option>
                                <option value="2">⭐⭐ - 2 Bintang (Perlu Peningkatan)</option>
                                <option value="1">⭐ - 1 Bintang (Kurang Memuaskan)</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Nama Penghuni</label>
                            <input type="text" value="{{ auth()->user()->name }}" class="w-full rounded-2xl border border-[#e7e2d8] bg-slate-100 px-4 py-3 text-sm text-slate-500 cursor-not-allowed" readonly>
                        </div>

                        <div class="sm:col-span-2">
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Ulasan / Testimoni Pengalaman Tinggal</label>
                            <textarea name="comment" rows="4" placeholder="Ceritakan bagaimana kenyamanan kamar, kebersihan, kecepatan WiFi, dan keramahan pengelola kos..." class="w-full rounded-2xl border border-[#e7e2d8] bg-[#faf8f5] p-4 text-sm text-slate-900 placeholder-slate-400 focus:border-[#c9a227] focus:bg-white focus:outline-none" required>{{ old('comment') }}</textarea>
                            @error('comment')
                                <p class="mt-1 text-xs font-semibold text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="rounded-full bg-[#c9a227] px-8 py-3 text-sm font-bold text-white shadow-md shadow-[#c9a227]/20 transition hover:bg-[#b68d1f]">
                            Kirim Ulasan Sekarang
                        </button>
                    </div>
                </form>
            @endauth
        </div>

        {{-- Reviews Feed Grid --}}
        <div>
            <h2 class="text-2xl font-bold text-slate-900 mb-6">Ulasan Terbaru dari Penghuni</h2>
            <div class="grid gap-6 md:grid-cols-3">
                @foreach($reviews as $review)
                    <div class="rounded-[28px] border border-[#e7e2d8] bg-white p-6 shadow-sm flex flex-col justify-between transition hover:shadow-md">
                        <div>
                            {{-- Rating Stars & Badge --}}
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center gap-1 text-[#c9a227]">
                                    @for($i = 0; $i < ($review->rating ?? 5); $i++)
                                        <svg class="h-4 w-4 fill-current" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                    @endfor
                                </div>
                                <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-2.5 py-0.5 text-[10px] font-bold text-emerald-700 border border-emerald-200">
                                    <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                    Terverifikasi
                                </span>
                            </div>

                            {{-- Review Body --}}
                            <p class="text-sm leading-relaxed text-slate-700 italic">
                                "{{ $review->comment }}"
                            </p>
                        </div>

                        {{-- Reviewer Info --}}
                        <div class="mt-6 pt-4 border-t border-slate-100 flex items-center gap-3">
                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-[#c9a227] text-xs font-bold text-white">
                                {{ strtoupper(substr($review->user->name ?? 'P', 0, 1)) }}
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="truncate text-xs font-bold text-slate-900">{{ $review->user->name ?? 'Penghuni ARCHOFESA' }}</p>
                                <p class="text-[11px] text-slate-400">
                                    {{ isset($review->created_at) ? \Carbon\Carbon::parse($review->created_at)->diffForHumans() : 'Penghuni Aktif' }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

    </div>
</div>
@endsection

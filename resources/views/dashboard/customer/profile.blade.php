@extends('layouts.dashboard-app')

@section('title', 'Profil Saya · ARCHOFESA KOST')

@section('content')
<div class="mx-auto max-w-6xl px-4 py-8 sm:px-6 lg:px-8">
    
    {{-- Header Banner --}}
    <div class="mb-8 overflow-hidden rounded-3xl border border-[#e7e2d8] bg-white p-6 shadow-sm sm:p-8">
        <div class="flex flex-col items-center justify-between gap-6 sm:flex-row">
            <div class="flex flex-col items-center gap-5 sm:flex-row">
                <div class="relative">
                    <img src="{{ $user->avatar ?? 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&background=c9a227&color=fff' }}" 
                         alt="{{ $user->name }}" 
                         class="h-20 w-20 rounded-2xl object-cover ring-4 ring-[#faf8f5]">
                    <span class="absolute -bottom-1 -right-1 h-5 w-5 rounded-full border-2 border-white bg-emerald-500" title="Akun Aktif"></span>
                </div>
                <div class="text-center sm:text-left">
                    <div class="flex flex-wrap items-center justify-center gap-2 sm:justify-start">
                        <h1 class="text-2xl font-bold text-slate-900">{{ $user->name }}</h1>
                        <span class="rounded-full bg-[#faf8f5] px-3 py-1 text-xs font-semibold uppercase tracking-wider text-[#c9a227] border border-[#e7e2d8]">
                            {{ $user->role === 'admin' ? 'Administrator' : ($user->role === 'owner' ? 'Pemilik Kos' : 'Penyewa') }}
                        </span>
                    </div>
                    <p class="mt-1 text-sm text-slate-500">{{ $user->email }}</p>
                    <p class="mt-1 text-xs text-slate-400">Bergabung sejak {{ $user->created_at ? $user->created_at->format('d M Y') : '-' }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Status Alert --}}
    @if (session('status') === 'profile-updated')
        <div class="mb-6 flex items-center gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-medium text-emerald-800 shadow-sm">
            <svg class="h-5 w-5 shrink-0 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            Profil berhasil diperbarui.
        </div>
    @endif

    @if (session('status') === 'password-updated')
        <div class="mb-6 flex items-center gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-medium text-emerald-800 shadow-sm">
            <svg class="h-5 w-5 shrink-0 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            Kata sandi berhasil diubah!
        </div>
    @endif

    <div class="grid gap-8 lg:grid-cols-3">
        
        {{-- Kolom Kiri: Informasi Pribadi & Kontak --}}
        <div class="lg:col-span-2 space-y-8">
            <div class="rounded-3xl border border-[#e7e2d8] bg-white p-6 shadow-sm sm:p-8">
                <div class="mb-6 border-b border-slate-100 pb-4">
                    <h2 class="text-lg font-bold text-slate-900">Informasi Pribadi & Kontak</h2>
                    <p class="mt-1 text-sm text-slate-500">Perbarui data diri dan nomor kontak yang dapat dihubungi</p>
                </div>

                <form action="{{ route('profile.update') }}" method="POST" class="space-y-5">
                    @csrf
                    @method('PATCH')

                    <div>
                        <label class="block text-sm font-semibold text-slate-700">Nama Lengkap</label>
                        <input type="text" 
                               name="name" 
                               value="{{ old('name', $user->name) }}" 
                               required
                               class="mt-2 block w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 transition focus:border-[#c9a227] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#c9a227]/20">
                        @error('name') <span class="mt-1 block text-xs font-medium text-red-600">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700">Alamat Email</label>
                        <input type="email" 
                               name="email" 
                               value="{{ old('email', $user->email) }}" 
                               required
                               class="mt-2 block w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 transition focus:border-[#c9a227] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#c9a227]/20">
                        @error('email') <span class="mt-1 block text-xs font-medium text-red-600">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid gap-5 sm:grid-cols-2">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700">Nomor Telepon / WhatsApp</label>
                            <input type="tel" 
                                   name="phone" 
                                   placeholder="0812xxxxxxx"
                                   value="{{ old('phone', $user->phone ?? '') }}" 
                                   class="mt-2 block w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 transition focus:border-[#c9a227] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#c9a227]/20">
                            @error('phone') <span class="mt-1 block text-xs font-medium text-red-600">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700">Alamat Asal / Domisili</label>
                            <input type="text" 
                                   name="address" 
                                   placeholder="Kota / Kabupaten asal"
                                   value="{{ old('address', $user->address ?? '') }}" 
                                   class="mt-2 block w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 transition focus:border-[#c9a227] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#c9a227]/20">
                            @error('address') <span class="mt-1 block text-xs font-medium text-red-600">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="pt-3">
                        <button type="submit" 
                                class="inline-flex items-center gap-2 rounded-xl bg-[#c9a227] px-6 py-3 text-sm font-bold text-white shadow-md shadow-[#c9a227]/20 transition hover:bg-[#b68d1f] active:scale-[0.98]">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Kolom Kanan: Keamanan & Akun Terhubung --}}
        <div class="space-y-8">
            
            {{-- Form Ubah Password --}}
            <div class="rounded-3xl border border-[#e7e2d8] bg-white p-6 shadow-sm sm:p-8">
                <div class="mb-6 border-b border-slate-100 pb-4">
                    <h2 class="text-lg font-bold text-slate-900">Ubah Kata Sandi</h2>
                    <p class="mt-1 text-sm text-slate-500">Perbarui kata sandi untuk keamanan akun Anda</p>
                </div>

                <form method="post" action="{{ route('password.update') }}" class="space-y-4">
                    @csrf
                    @method('put')

                    <div>
                        <label class="block text-sm font-semibold text-slate-700">Kata Sandi Saat Ini</label>
                        <input type="password" 
                               name="current_password"
                               autocomplete="current-password"
                               class="mt-2 block w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 transition focus:border-[#c9a227] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#c9a227]/20">
                        @error('current_password', 'updatePassword') <span class="mt-1 block text-xs font-medium text-red-600">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700">Kata Sandi Baru</label>
                        <input type="password" 
                               name="password"
                               autocomplete="new-password"
                               class="mt-2 block w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 transition focus:border-[#c9a227] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#c9a227]/20">
                        @error('password', 'updatePassword') <span class="mt-1 block text-xs font-medium text-red-600">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700">Konfirmasi Kata Sandi Baru</label>
                        <input type="password" 
                               name="password_confirmation"
                               autocomplete="new-password"
                               class="mt-2 block w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 transition focus:border-[#c9a227] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#c9a227]/20">
                        @error('password_confirmation', 'updatePassword') <span class="mt-1 block text-xs font-medium text-red-600">{{ $message }}</span> @enderror
                    </div>

                    <div class="pt-2">
                        <button type="submit" 
                                class="w-full rounded-xl border border-slate-200 bg-slate-50 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-100 active:scale-[0.98]">
                            Perbarui Kata Sandi
                        </button>
                    </div>
                </form>
            </div>

            {{-- Akun Terhubung --}}
            <div class="rounded-3xl border border-[#e7e2d8] bg-white p-6 shadow-sm sm:p-8">
                <div class="mb-4 border-b border-slate-100 pb-4">
                    <h2 class="text-lg font-bold text-slate-900">Metode Login</h2>
                </div>

                <div class="flex items-center justify-between rounded-2xl border border-slate-100 bg-slate-50 p-4">
                    <div class="flex items-center gap-3">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-white shadow-sm border border-slate-200">
                            <svg class="h-5 w-5" viewBox="0 0 24 24">
                                <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                                <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                                <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/>
                                <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-slate-900">Google Auth</p>
                            <p class="text-xs text-slate-500">{{ $user->google_id ? 'Terhubung' : 'Belum terhubung' }}</p>
                        </div>
                    </div>
                    @if(!$user->google_id)
                        <a href="{{ route('auth.google') }}" class="rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 hover:bg-slate-50">Hubungkan</a>
                    @else
                        <span class="rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700">Aktif</span>
                    @endif
                </div>
            </div>

        </div>

    </div>
</div>
@endsection

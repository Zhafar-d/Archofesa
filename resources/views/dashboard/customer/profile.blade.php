@extends('layouts.dashboard-app')

@section('title', 'Profil · ARCHOFESA KOST')

@section('content')
<div class="px-6 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-slate-900">Pengaturan Profil</h1>
        <p class="mt-2 text-slate-600">Kelola akun dan preferensi Anda</p>
    </div>

    @if (session('status') === 'profile-updated')
        <div class="mb-6 rounded-lg bg-emerald-50 border border-emerald-200 p-4 text-sm font-semibold text-emerald-800">
            ✓ Profil berhasil diperbarui.
        </div>
    @endif

    <div class="grid gap-6 lg:grid-cols-3">
        <x-dashboard.section title="Foto Profil">
            <div class="space-y-4">
                <img src="{{ $user->avatar ?? 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&background=random' }}" alt="{{ $user->name }}" class="h-24 w-24 rounded-2xl object-cover">
                <button class="rounded-lg border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                    Ganti Foto
                </button>
            </div>
        </x-dashboard.section>

        <div class="lg:col-span-2">
            <x-dashboard.section title="Informasi Pribadi">
                <form action="{{ route('profile.update') }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PATCH')
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Nama Lengkap</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" class="mt-1 w-full rounded-lg border border-slate-200 bg-slate-50 px-4 py-2">
                        @error('name') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Alamat Email</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" class="mt-1 w-full rounded-lg border border-slate-200 bg-slate-50 px-4 py-2">
                        @error('email') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Nomor Telepon</label>
                        <input type="tel" name="phone" value="{{ old('phone', $user->phone ?? '') }}" class="mt-1 w-full rounded-lg border border-slate-200 bg-slate-50 px-4 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Alamat</label>
                        <input type="text" name="address" value="{{ old('address', $user->address ?? '') }}" class="mt-1 w-full rounded-lg border border-slate-200 bg-slate-50 px-4 py-2">
                    </div>
                    <button type="submit" class="rounded-lg bg-[#c9a227] px-6 py-2 font-semibold text-white hover:bg-[#b68d1f]">Simpan Perubahan</button>
                </form>
            </x-dashboard.section>
        </div>
    </div>

    <div class="mt-8 grid gap-6 lg:grid-cols-2">
        <x-dashboard.section title="Keamanan">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700">Kata Sandi Saat Ini</label>
                    <input type="password" class="mt-1 w-full rounded-lg border border-slate-200 bg-slate-50 px-4 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700">Kata Sandi Baru</label>
                    <input type="password" class="mt-1 w-full rounded-lg border border-slate-200 bg-slate-50 px-4 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700">Konfirmasi Kata Sandi</label>
                    <input type="password" class="mt-1 w-full rounded-lg border border-slate-200 bg-slate-50 px-4 py-2">
                </div>
                <button class="rounded-lg border border-slate-200 px-6 py-2 font-semibold text-slate-700 hover:bg-slate-50">Ganti Kata Sandi</button>
            </div>
        </x-dashboard.section>

        <x-dashboard.section title="Akun Terhubung">
            <div class="space-y-3">
                <div class="flex items-center justify-between rounded-lg border border-slate-200 p-4">
                    <div>
                        <p class="font-medium text-slate-900">{{ $user->google_id ? 'Akun Google Terhubung' : 'Tidak Ada Akun Terhubung' }}</p>
                        @if($user->google_id)
                            <p class="text-sm text-slate-600">{{ $user->email }}</p>
                        @endif
                    </div>
                    @if($user->google_id)
                        <button class="text-sm font-semibold text-red-600 hover:text-red-700">Putuskan</button>
                    @endif
                </div>
                <button class="w-full rounded-lg border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">+ Hubungkan Akun Lain</button>
            </div>
        </x-dashboard.section>
    </div>
</div>
@endsection

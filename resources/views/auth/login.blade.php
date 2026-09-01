<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="mb-6 text-center">
        <h1 class="text-2xl font-bold text-gray-800">Selamat Datang Kembali</h1>
        <p class="text-sm text-gray-500 mt-1">Masuk dengan email atau nomor WhatsApp Anda</p>
    </div>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address or Phone -->
        <div>
            <x-input-label for="login" :value="__('Email atau Nomor Telepon / WA')" />
            <x-text-input id="login" class="block mt-1 w-full" type="text" name="login" :value="old('login')" required autofocus autocomplete="username" placeholder="0882... atau nama@email.com" />
            <x-input-error :messages="$errors->get('login')" class="mt-2" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <div class="flex items-center justify-between">
                <x-input-label for="password" :value="__('Password')" />
                @if (Route::has('password.request'))
                    <a class="text-xs text-gray-500 hover:text-[#c9a227] hover:underline" href="{{ route('password.request') }}">
                        {{ __('Forgot your password?') }}
                    </a>
                @endif
            </div>

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" 
                            placeholder="Masukkan kata sandi" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-[#c9a227] shadow-sm focus:ring-[#c9a227]" name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="mt-6">
            <button type="submit" class="w-full inline-flex items-center justify-center rounded-xl bg-[#1f2937] px-4 py-2.5 text-sm font-bold text-white shadow-sm transition hover:bg-slate-800 focus:outline-none">
                {{ __('LOG IN') }}
            </button>
        </div>

        <div class="relative my-6 flex items-center justify-center">
            <div class="w-full border-t border-gray-200"></div>
            <span class="absolute bg-white px-2 text-xs uppercase tracking-wider text-gray-400">atau</span>
        </div>

        <div>
            <button id="firebase-google-login" type="button" class="inline-flex w-full items-center justify-center gap-2 rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 shadow-sm hover:bg-gray-50">
                <svg class="h-4 w-4" viewBox="0 0 24 24">
                    <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                    <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                    <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/>
                    <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/>
                </svg>
                Continue with Google
            </button>
        </div>

        {{-- Tombol / Banner Daftar Akun Baru --}}
        <div class="mt-6 rounded-xl border border-[#e7e2d8] bg-[#faf8f5] p-3.5 text-center">
            <p class="text-xs text-gray-500">Belum memiliki akun?</p>
            <a href="{{ route('register') }}" class="mt-1 inline-flex items-center gap-1 text-sm font-bold text-[#c9a227] hover:underline">
                <span>Daftar / Buat Akun Baru</span> &rarr;
            </a>
        </div>
    </form>
</x-guest-layout>

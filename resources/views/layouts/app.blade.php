<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <script>
            window.firebaseConfig = {
                apiKey: "{{ env('FIREBASE_API_KEY', env('VITE_FIREBASE_API_KEY')) }}",
                authDomain: "{{ env('FIREBASE_AUTH_DOMAIN', env('VITE_FIREBASE_AUTH_DOMAIN')) }}",
                projectId: "{{ env('FIREBASE_PROJECT_ID', env('VITE_FIREBASE_PROJECT_ID')) }}",
                storageBucket: "{{ env('FIREBASE_STORAGE_BUCKET', env('VITE_FIREBASE_STORAGE_BUCKET')) }}",
                messagingSenderId: "{{ env('FIREBASE_MESSAGING_SENDER_ID', env('VITE_FIREBASE_MESSAGING_SENDER_ID')) }}",
                appId: "{{ env('FIREBASE_APP_ID', env('VITE_FIREBASE_APP_ID')) }}"
            };
        </script>
        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @stack('styles')
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot ?? '' }}
                @yield('content')
            </main>
        </div>
        @stack('scripts')
    </body>
</html>

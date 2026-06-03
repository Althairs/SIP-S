<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="light">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'SIP-S') - Sistem Informasi Pengelolaan Skripsi</title>
    <meta name="description" content="Sistem Informasi Pengelolaan Skripsi Fakultas Pertanian">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800&display=swap"
        rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @fluxAppearance
    @livewireStyles
    @stack('styles')
</head>

<body class="font-sans antialiased bg-gray-50">
    <!-- Sidebar berdasarkan role -->
    @if(Auth::user()->hasRole('super_admin'))
        <x-navigation.sidebar />
    @elseif(Auth::user()->hasRole('kajur'))
        <x-navigation.sidebar-kajur />
    @elseif(Auth::user()->hasRole('sekjur'))
        <x-navigation.sidebar-sekjur />
    @elseif(Auth::user()->hasRole('mahasiswa'))
        <x-navigation.sidebar-mahasiswa />
    @elseif(Auth::user()->hasRole('panitia_verifikasi'))
        <x-navigation.sidebar-panitia-verifikasi />
    @elseif(Auth::user()->hasRole('panitia_penjadwalan'))
        <x-navigation.sidebar-panitia-penjadwalan />
    @elseif(Auth::user()->hasRole('panitia_administrasi'))
        <x-navigation.sidebar-panitia-administrasi />
    @elseif(Auth::user()->hasRole('dosen'))
        <x-navigation.sidebar-dosen />
    @endif

    <!-- Main Content -->
    <main class="p-4 sm:ml-64 mt-14 min-h-screen">
        {{ $slot }}
    </main>

    <!-- Footer -->
    {{-- <x-partials.footer-auth /> --}}

    @livewireScripts
    @fluxScripts
    @stack('scripts')

    {{-- Flowbite JS --}}
    <script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.js"></script>
</body>

</html>

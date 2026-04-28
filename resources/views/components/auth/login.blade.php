<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - SIP-S</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gradient-to-br from-green-50 via-white to-amber-50 min-h-screen">
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <!-- Logo -->
            <div class="text-center">
                <a href="{{ route('home') }}" class="inline-flex items-center space-x-3 mb-6">
                    <div class="w-12 h-12 bg-gradient-to-br from-green-600 to-green-700 rounded-xl flex items-center justify-center">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                    </div>
                    <span class="text-2xl font-bold text-gray-800">SIP-<span class="text-green-700">S</span></span>
                </a>
                <h2 class="text-3xl font-bold text-gray-900">Selamat Datang</h2>
                <p class="mt-2 text-gray-600">Silakan login untuk melanjutkan</p>
            </div>

            <!-- Form Login -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
                @if ($errors->any())
                <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-800 rounded-xl text-sm">
                    {{ $errors->first() }}
                </div>
                @endif

                @if (session('status'))
                <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-800 rounded-xl text-sm">
                    {{ session('status') }}
                </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" id="email" name="email" required autofocus
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500"
                               placeholder="contoh: kajur.agroteknologi@sips.com"
                               value="{{ old('email') }}">
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                        <input type="password" id="password" name="password" required
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500"
                               placeholder="Masukkan password">
                    </div>

                    <div class="flex items-center justify-between">
                        <label class="flex items-center">
                            <input type="checkbox" name="remember" class="w-4 h-4 text-green-700 border-gray-300 rounded focus:ring-green-500">
                            <span class="ml-2 text-sm text-gray-600">Ingat saya</span>
                        </label>
                    </div>

                    <button type="submit" class="w-full py-3 bg-green-700 text-white rounded-xl hover:bg-green-800 transition font-semibold">
                        Masuk
                    </button>
                </form>
            </div>

            <!-- Info Akun Testing -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-sm font-semibold text-gray-900 mb-3 flex items-center">
                    <svg class="w-4 h-4 text-amber-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                    Akun Testing (Password: password123)
                </h3>
                <div class="space-y-2 text-xs text-gray-600">
                    <div class="grid grid-cols-2 gap-2">
                        <div class="p-2 bg-green-50 rounded-lg">
                            <p class="font-medium text-green-800">Super Admin</p>
                            <p class="text-green-600">superadmin@sips.com</p>
                        </div>
                        <div class="p-2 bg-emerald-50 rounded-lg">
                            <p class="font-medium text-emerald-800">Kajur</p>
                            <p class="text-emerald-600">kajur.agroteknologi@sips.com</p>
                        </div>
                        <div class="p-2 bg-violet-50 rounded-lg">
                            <p class="font-medium text-violet-800">Sekjur</p>
                            <p class="text-violet-600">sekjur.agroteknologi@sips.com</p>
                        </div>
                        <div class="p-2 bg-blue-50 rounded-lg">
                            <p class="font-medium text-blue-800">Dosen</p>
                            <p class="text-blue-600">ahmad.fauzi@sips.com</p>
                        </div>
                        <div class="p-2 bg-amber-50 rounded-lg">
                            <p class="font-medium text-amber-800">Panitia</p>
                            <p class="text-amber-600">andi.pratama.agt@sips.com</p>
                        </div>
                        <div class="p-2 bg-purple-50 rounded-lg">
                            <p class="font-medium text-purple-800">Mahasiswa</p>
                            <p class="text-purple-600">dewi.kusuma@sips.com</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

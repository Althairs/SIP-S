<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - SIP-S</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Alpine.js untuk interaktivitas -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="font-sans antialiased bg-gradient-to-br from-green-50 via-white to-amber-50 min-h-screen">
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8" x-data="loginApp()">
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

                    <!-- Login Field (Email / NIM / NIP) -->
                    <div>
                        <label for="login" class="block text-sm font-medium text-gray-700 mb-1">
                            Email / NIM / NIP
                        </label>
                        <input type="text" id="login" name="login" required autofocus
                               x-model="loginInput"
                               @input="filterAccounts()"
                               @focus="showAccountList = true"
                               @blur="setTimeout(() => showAccountList = false, 200)"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500"
                               placeholder="Masukkan email, NIM, atau NIP"
                               value="{{ old('login') }}">

                        <!-- Daftar Akun (Auto-fill) -->
                        <div x-show="showAccountList && filteredAccounts.length > 0"
                             x-transition
                             class="relative">
                            <div class="absolute z-10 w-full mt-1 bg-white border border-gray-200 rounded-xl shadow-lg max-h-60 overflow-y-auto">
                                <template x-for="account in filteredAccounts" :key="account.id">
                                    <div @mousedown="selectAccount(account)"
                                         class="flex items-center justify-between px-4 py-3 hover:bg-green-50 cursor-pointer border-b border-gray-100 last:border-0 transition">
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-gray-900 truncate" x-text="account.name"></p>
                                            <div class="flex items-center gap-2 text-xs text-gray-500">
                                                <span x-text="account.email"></span>
                                                <template x-if="account.nim">
                                                    <span class="text-green-600">| NIM: <span x-text="account.nim"></span></span>
                                                </template>
                                                <template x-if="account.nip">
                                                    <span class="text-green-600">| NIP: <span x-text="account.nip"></span></span>
                                                </template>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-2 ml-3 flex-shrink-0">
                                            <span x-show="account.role"
                                                  class="px-2 py-0.5 text-xs rounded-full"
                                                  :class="{
                                                      'bg-green-100 text-green-700': account.role === 'super_admin',
                                                      'bg-emerald-100 text-emerald-700': account.role === 'kajur',
                                                      'bg-green-100 text-green-700': account.role === 'sekjur',
                                                      'bg-green-100 text-green-700': account.role === 'dosen',
                                                      'bg-amber-100 text-amber-700': account.role === 'panitia_verifikasi' || account.role === 'panitia_penjadwalan' || account.role === 'panitia_administrasi',
                                                      'bg-green-100 text-green-700': account.role === 'mahasiswa'
                                                  }"
                                                  x-text="account.role_label || account.role"></span>
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                            </svg>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <p class="mt-1 text-xs text-gray-400">Masukkan email, NIM, atau NIP untuk login</p>
                    </div>

                    <!-- Password dengan Toggle Visibility -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                        <div class="relative">
                            <input :type="showPassword ? 'text' : 'password'"
                                   id="password"
                                   name="password"
                                   required
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 pr-12"
                                   placeholder="Masukkan password">
                            <button type="button"
                                    @click="showPassword = !showPassword"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 focus:outline-none transition">
                                <svg x-show="!showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                <svg x-show="showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.124-2.203m2.825-2.825A10.05 10.05 0 0112 5c4.478 0 8.268 2.943 9.542 7a9.977 9.977 0 01-1.124 2.203m-2.825 2.825A10.05 10.05 0 0112 19z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18"></path>
                                </svg>
                            </button>
                        </div>
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

            <!-- Akun Testing dengan Scroll -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6" x-data="testAccounts()">
                <h3 class="text-sm font-semibold text-gray-900 mb-3 flex items-center">
                    <svg class="w-4 h-4 text-amber-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                    Akun Testing (Password: <span class="font-mono bg-gray-100 px-2 py-0.5 rounded">password123</span>)
                    <span class="ml-2 text-xs text-gray-400">Klik akun untuk auto-fill</span>
                </h3>
                <div class="max-h-48 overflow-y-auto space-y-1.5 pr-1 scrollbar-thin scrollbar-thumb-gray-300 scrollbar-track-gray-100">
                    <template x-for="account in accounts" :key="account.id">
                        <div @click="selectTestAccount(account)"
                             class="flex items-center justify-between p-2.5 rounded-lg hover:bg-green-50 cursor-pointer transition border border-transparent hover:border-green-200">
                            <div class="flex items-center gap-3 min-w-0 flex-1">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center text-white text-xs font-bold flex-shrink-0"
                                     :style="'background-color: ' + account.color">
                                    <span x-text="account.initials"></span>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="text-sm font-medium text-gray-900 truncate" x-text="account.name"></p>
                                    <p class="text-xs text-gray-500 truncate" x-text="account.login"></p>
                                </div>
                            </div>
                            <span class="px-2 py-0.5 text-xs rounded-full flex-shrink-0 ml-2"
                                  :class="{
                                      'bg-green-100 text-green-700': account.role === 'Super Admin',
                                      'bg-emerald-100 text-emerald-700': account.role === 'Kajur',
                                      'bg-green-100 text-green-700': account.role === 'Sekjur',
                                      'bg-green-100 text-green-700': account.role === 'Dosen',
                                      'bg-amber-100 text-amber-700': account.role === 'Panitia',
                                      'bg-green-100 text-green-700': account.role === 'Mahasiswa'
                                  }"
                                  x-text="account.role"></span>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>

    <script>
        function loginApp() {
            return {
                showPassword: false,
                showAccountList: false,
                loginInput: '',
                filteredAccounts: [],
                accounts: @json($testAccounts ?? []),

                init() {
                    this.filteredAccounts = this.accounts;
                },

                filterAccounts() {
                    if (!this.loginInput.trim()) {
                        this.filteredAccounts = this.accounts;
                        return;
                    }
                    const search = this.loginInput.toLowerCase().trim();
                    this.filteredAccounts = this.accounts.filter(acc =>
                        acc.name.toLowerCase().includes(search) ||
                        acc.email.toLowerCase().includes(search) ||
                        (acc.nim && acc.nim.toLowerCase().includes(search)) ||
                        (acc.nip && acc.nip.toLowerCase().includes(search))
                    );
                },

                selectAccount(account) {
                    this.loginInput = account.email;
                    this.showAccountList = false;
                    // Isi password otomatis
                    document.getElementById('password').value = 'password123';
                }
            }
        }

        function testAccounts() {
            return {
                accounts: @json($testAccounts ?? []),

                selectTestAccount(account) {
                    // Isi field login
                    const loginField = document.getElementById('login');
                    loginField.value = account.login;
                    // Trigger input event untuk Alpine
                    loginField.dispatchEvent(new Event('input'));

                    // Isi password
                    document.getElementById('password').value = 'password123';

                    // Focus ke password
                    document.getElementById('password').focus();
                }
            }
        }
    </script>

    <style>
        .scrollbar-thin::-webkit-scrollbar {
            width: 4px;
        }
        .scrollbar-thin::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }
        .scrollbar-thin::-webkit-scrollbar-thumb {
            background: #d1d5db;
            border-radius: 10px;
        }
        .scrollbar-thin::-webkit-scrollbar-thumb:hover {
            background: #9ca3af;
        }
    </style>
</body>
</html>

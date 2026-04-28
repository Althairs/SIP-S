<div>
    @section('title', 'Pengaturan Akun')
    @section('page-title', 'Pengaturan Akun')

    <div class="max-w-3xl mx-auto">
        @if (session()->has('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-800 rounded-xl flex items-center">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
            </svg>
            {{ session('success') }}
        </div>
        @endif

        <!-- Profile Info Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Informasi Akun</h2>
            <div class="flex items-center space-x-4">
                <img class="w-16 h-16 rounded-full bg-green-200" src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&size=64&background=16a34a&color=fff" alt="">
                <div>
                    <p class="text-xl font-semibold text-gray-900">{{ Auth::user()->name }}</p>
                    <p class="text-gray-500">{{ Auth::user()->email }}</p>
                    <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-medium capitalize mt-1 inline-block">
                        {{ Auth::user()->getRoleNames()->first() ?? 'No Role' }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Change Password Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Ubah Password</h2>
            <form wire:submit="updatePassword">
                <div class="space-y-4">
                    <div>
                        <label for="current_password" class="block text-sm font-medium text-gray-700 mb-1">
                            Password Saat Ini
                        </label>
                        <input type="password" id="current_password" wire:model="current_password"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('current_password') border-red-500 @enderror">
                        @error('current_password') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                            Password Baru
                        </label>
                        <input type="password" id="password" wire:model="password"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('password') border-red-500 @enderror">
                        @error('password') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">
                            Konfirmasi Password Baru
                        </label>
                        <input type="password" id="password_confirmation" wire:model="password_confirmation"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500">
                    </div>
                </div>

                <div class="mt-6">
                    <button type="submit"
                            class="px-6 py-2.5 bg-green-700 text-white rounded-xl hover:bg-green-800 transition font-medium">
                        Update Password
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

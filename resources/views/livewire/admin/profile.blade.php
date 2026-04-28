<div>
    @section('title', 'Profile')
    @section('page-title', 'Profile Saya')

    <div class="max-w-3xl mx-auto">
        @if (session()->has('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-800 rounded-xl flex items-center">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
            </svg>
            {{ session('success') }}
        </div>
        @endif

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <form wire:submit="updateProfile">
                <div class="space-y-6">
                    <!-- Foto -->
                    <div class="flex items-center space-x-4">
                        <img class="w-20 h-20 rounded-full bg-green-200"
                             src="{{ Auth::user()->foto ? asset('storage/' . Auth::user()->foto) : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&size=80&background=16a34a&color=fff' }}"
                             alt="">
                        <div>
                            <label class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg cursor-pointer hover:bg-gray-200 transition text-sm font-medium">
                                Upload Foto
                                <input type="file" wire:model="foto" class="hidden">
                            </label>
                            <p class="text-xs text-gray-500 mt-1">Max 2MB, format: JPG, PNG</p>
                            @error('foto') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                            Nama Lengkap
                        </label>
                        <input type="text" id="name" wire:model="name"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('name') border-red-500 @enderror">
                        @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                            Email
                        </label>
                        <input type="email" id="email" wire:model="email"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('email') border-red-500 @enderror">
                        @error('email') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <!-- HP -->
                    <div>
                        <label for="nomor_hp" class="block text-sm font-medium text-gray-700 mb-1">
                            Nomor HP
                        </label>
                        <input type="text" id="nomor_hp" wire:model="nomor_hp"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500">
                    </div>

                    <!-- Alamat -->
                    <div>
                        <label for="alamat" class="block text-sm font-medium text-gray-700 mb-1">
                            Alamat
                        </label>
                        <textarea id="alamat" wire:model="alamat" rows="3"
                                  class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500"></textarea>
                    </div>
                </div>

                <div class="mt-6">
                    <button type="submit"
                            class="px-6 py-2.5 bg-green-700 text-white rounded-xl hover:bg-green-800 transition font-medium">
                        Simpan Profile
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

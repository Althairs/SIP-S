<div>
    @section('title', 'Profile')
    @section('page-title', 'Profile Saya')

    <div class="max-w-4xl mx-auto">
        @if (session()->has('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-800 rounded-xl flex items-center" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
            {{ session('success') }}
        </div>
        @endif

        <!-- Profile Header -->
        <div class="bg-gradient-to-r from-blue-600 to-blue-800 rounded-2xl p-6 mb-6 text-white">
            <div class="flex items-center space-x-4">
                <img class="w-20 h-20 rounded-full border-4 border-white/30"
                     src="{{ $currentFoto ? asset('storage/' . $currentFoto) : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&size=80&background=2563eb&color=fff' }}"
                     alt="{{ Auth::user()->name }}">
                <div>
                    <h2 class="text-2xl font-bold">{{ Auth::user()->name }}</h2>
                    <p class="text-blue-100">{{ Auth::user()->nim }} | {{ Auth::user()->jurusan?->nama_jurusan }}</p>
                    <p class="text-blue-200 text-sm">{{ Auth::user()->prodi?->nama_prodi }}</p>
                </div>
            </div>
        </div>

        <!-- Tabs -->
        <div class="flex border-b border-gray-200 mb-6">
            <button wire:click="setTab('profile')"
                    class="px-6 py-3 text-sm font-medium border-b-2 transition {{ $activeTab === 'profile' ? 'border-blue-600 text-blue-700' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
                Edit Profile
            </button>
            <button wire:click="setTab('password')"
                    class="px-6 py-3 text-sm font-medium border-b-2 transition {{ $activeTab === 'password' ? 'border-blue-600 text-blue-700' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
                Ubah Password
            </button>
            <button wire:click="setTab('info')"
                    class="px-6 py-3 text-sm font-medium border-b-2 transition {{ $activeTab === 'info' ? 'border-blue-600 text-blue-700' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
                Informasi Akademik
            </button>
        </div>

        <!-- Tab: Edit Profile -->
        @if($activeTab === 'profile')
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <form wire:submit="updateProfile">
                <div class="space-y-6">
                    <!-- Foto -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Foto Profile</label>
                        <div class="flex items-center space-x-4">
                            <img class="w-16 h-16 rounded-full"
                                 src="{{ $currentFoto ? asset('storage/' . $currentFoto) : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&size=64&background=2563eb&color=fff' }}"
                                 alt="">
                            <div>
                                <label class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg cursor-pointer hover:bg-gray-200 transition text-sm font-medium">
                                    Upload Foto
                                    <input type="file" wire:model="foto" class="hidden" accept="image/*">
                                </label>
                                <p class="text-xs text-gray-500 mt-1">Max 2MB, JPG/PNG</p>
                                @error('foto') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>

                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                            <input type="text" wire:model="name" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 @enderror">
                            @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email <span class="text-red-500">*</span></label>
                            <input type="email" wire:model="email" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 @error('email') border-red-500 @enderror">
                            @error('email') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">NIM</label>
                            <input type="text" value="{{ $nim }}" disabled class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-xl text-gray-500 cursor-not-allowed">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nomor HP</label>
                            <input type="text" wire:model="nomor_hp" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500" placeholder="0812xxxxxx">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Angkatan</label>
                            <input type="text" wire:model="angkatan" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500" placeholder="2021">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                            <textarea wire:model="alamat" rows="3" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500" placeholder="Alamat lengkap..."></textarea>
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex justify-end">
                    <button type="submit" class="px-6 py-2.5 bg-blue-700 text-white rounded-xl hover:bg-blue-800 transition font-medium">
                        Simpan Profile
                    </button>
                </div>
            </form>
        </div>
        @endif

        <!-- Tab: Ubah Password -->
        @if($activeTab === 'password')
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <form wire:submit="updatePassword">
                <div class="space-y-4 max-w-md">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Password Saat Ini <span class="text-red-500">*</span></label>
                        <input type="password" wire:model="current_password" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 @error('current_password') border-red-500 @enderror">
                        @error('current_password') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Password Baru <span class="text-red-500">*</span></label>
                        <input type="password" wire:model="password" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 @error('password') border-red-500 @enderror">
                        @error('password') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        <p class="text-xs text-gray-500 mt-1">Minimal 8 karakter</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password Baru <span class="text-red-500">*</span></label>
                        <input type="password" wire:model="password_confirmation" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>

                <div class="mt-6 flex justify-end">
                    <button type="submit" class="px-6 py-2.5 bg-blue-700 text-white rounded-xl hover:bg-blue-800 transition font-medium">
                        Update Password
                    </button>
                </div>
            </form>
        </div>
        @endif

        <!-- Tab: Informasi Akademik -->
        @if($activeTab === 'info')
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-6">Informasi Akademik</h3>

            <div class="grid md:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <div>
                        <p class="text-sm text-gray-500">Nama Lengkap</p>
                        <p class="font-medium text-gray-900">{{ Auth::user()->name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">NIM</p>
                        <p class="font-medium text-gray-900">{{ Auth::user()->nim ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Email</p>
                        <p class="font-medium text-gray-900">{{ Auth::user()->email }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Nomor HP</p>
                        <p class="font-medium text-gray-900">{{ Auth::user()->nomor_hp ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Angkatan</p>
                        <p class="font-medium text-gray-900">{{ Auth::user()->angkatan ?? '-' }}</p>
                    </div>
                </div>

                <div class="space-y-4">
                    <div>
                        <p class="text-sm text-gray-500">Jurusan</p>
                        <p class="font-medium text-gray-900">{{ Auth::user()->jurusan?->nama_jurusan ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Program Studi</p>
                        <p class="font-medium text-gray-900">{{ Auth::user()->prodi?->nama_prodi ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Status Aktif</p>
                        <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs font-medium">Aktif</span>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Total Ujian</p>
                        <p class="font-medium text-gray-900">{{ \App\Models\Pendaftaran::where('mahasiswa_id', Auth::id())->count() }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Ujian Selesai</p>
                        <p class="font-medium text-gray-900">{{ \App\Models\Pendaftaran::where('mahasiswa_id', Auth::id())->where('status', 'selesai')->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="mt-6">
                <p class="text-sm text-gray-500">Alamat</p>
                <p class="font-medium text-gray-900">{{ Auth::user()->alamat ?? '-' }}</p>
            </div>
        </div>
        @endif
    </div>
</div>

<div>
    @section('title', 'Profile Dosen')
    @section('page-title', 'Profile Saya')

    @if (session()->has('success'))
    <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-800 rounded-xl flex items-center" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)">
        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
        {{ session('success') }}
    </div>
    @endif

    <div class="max-w-4xl mx-auto">
        <!-- Profile Header -->
        <div class="bg-gradient-to-r from-indigo-600 to-indigo-800 rounded-2xl p-6 mb-6 text-white">
            <div class="flex items-center space-x-4">
                <img class="w-20 h-20 rounded-full border-4 border-white/30"
                     src="{{ $currentFoto ? asset('storage/' . $currentFoto) : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&size=80&background=4f46e5&color=fff' }}"
                     alt="">
                <div>
                    <h2 class="text-2xl font-bold">{{ Auth::user()->name }}</h2>
                    <p class="text-indigo-100">NIP: {{ Auth::user()->nip }}</p>
                    <p class="text-indigo-200 text-sm">{{ Auth::user()->jurusan?->nama_jurusan }}</p>
                    @if(Auth::user()->kepakaran)
                    <span class="px-2 py-0.5 bg-white/20 rounded-full text-xs mt-1 inline-block">{{ Auth::user()->kepakaran->nama_kepakaran }}</span>
                    @endif
                    @if(Auth::user()->bidangKeahlians->count() > 0)
                    <div class="flex flex-wrap gap-1 mt-2">
                        @foreach(Auth::user()->bidangKeahlians as $bk)
                        <span class="px-2 py-0.5 bg-white/20 rounded-full text-xs">{{ $bk->nama_bidang }}</span>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Tabs -->
        <div class="flex border-b border-gray-200 mb-6">
            <button wire:click="setTab('profile')" class="px-6 py-3 text-sm font-medium border-b-2 transition {{ $activeTab === 'profile' ? 'border-indigo-600 text-indigo-700' : 'border-transparent text-gray-500 hover:text-gray-700' }}">Edit Profile</button>
            <button wire:click="setTab('password')" class="px-6 py-3 text-sm font-medium border-b-2 transition {{ $activeTab === 'password' ? 'border-indigo-600 text-indigo-700' : 'border-transparent text-gray-500 hover:text-gray-700' }}">Ubah Password</button>
            <button wire:click="setTab('info')" class="px-6 py-3 text-sm font-medium border-b-2 transition {{ $activeTab === 'info' ? 'border-indigo-600 text-indigo-700' : 'border-transparent text-gray-500 hover:text-gray-700' }}">Info Akademik</button>
        </div>

        {{-- Tab Profile --}}
        @if($activeTab === 'profile')
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <form wire:submit="updateProfile">
                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Foto Profile</label>
                        <div class="flex items-center space-x-4">
                            <img class="w-16 h-16 rounded-full" src="{{ $currentFoto ? asset('storage/' . $currentFoto) : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&size=64&background=4f46e5&color=fff' }}">
                            <label class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg cursor-pointer hover:bg-gray-200 text-sm">
                                Upload Foto
                                <input type="file" wire:model="foto" class="hidden" accept="image/*">
                            </label>
                        </div>
                    </div>

                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                            <input type="text" wire:model="name" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input type="email" wire:model="email" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">NIP</label>
                            <input type="text" value="{{ $nip }}" disabled class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-xl text-gray-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nomor HP</label>
                            <input type="text" wire:model="nomor_hp" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                            <textarea wire:model="alamat" rows="3" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500"></textarea>
                        </div>
                    </div>
                </div>
                <div class="mt-6 flex justify-end">
                    <button type="submit" class="px-6 py-2.5 bg-indigo-700 text-white rounded-xl hover:bg-indigo-800 font-medium">Simpan Profile</button>
                </div>
            </form>
        </div>
        @endif

        {{-- Tab Password --}}
        @if($activeTab === 'password')
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <form wire:submit="updatePassword">
                <div class="space-y-4 max-w-md">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Password Saat Ini</label>
                        <input type="password" wire:model="current_password" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 @error('current_password') border-red-500 @enderror">
                        @error('current_password') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Password Baru</label>
                        <input type="password" wire:model="password" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 @error('password') border-red-500 @enderror">
                        @error('password') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password</label>
                        <input type="password" wire:model="password_confirmation" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500">
                    </div>
                </div>
                <div class="mt-6">
                    <button type="submit" class="px-6 py-2.5 bg-indigo-700 text-white rounded-xl hover:bg-indigo-800 font-medium">Update Password</button>
                </div>
            </form>
        </div>
        @endif

        {{-- Tab Info Akademik --}}
        @if($activeTab === 'info')
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="grid md:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <div><p class="text-sm text-gray-500">Nama</p><p class="font-medium">{{ Auth::user()->name }}</p></div>
                    <div><p class="text-sm text-gray-500">NIP</p><p class="font-medium">{{ Auth::user()->nip }}</p></div>
                    <div><p class="text-sm text-gray-500">Email</p><p class="font-medium">{{ Auth::user()->email }}</p></div>
                    <div><p class="text-sm text-gray-500">Nomor HP</p><p class="font-medium">{{ Auth::user()->nomor_hp ?? '-' }}</p></div>
                </div>
                <div class="space-y-4">
                    <div><p class="text-sm text-gray-500">Jurusan</p><p class="font-medium">{{ Auth::user()->jurusan?->nama_jurusan }}</p></div>
                    <div><p class="text-sm text-gray-500">Prodi</p><p class="font-medium">{{ Auth::user()->prodi?->nama_prodi }}</p></div>
                    <div>
                        <p class="text-sm text-gray-500">Kepakaran</p>
                        <p class="font-medium">{{ Auth::user()->kepakaran?->nama_kepakaran ?? 'Belum diatur' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Bidang Keahlian</p>
                        @if(Auth::user()->bidangKeahlians->count() > 0)
                        <div class="flex flex-wrap gap-1 mt-1">
                            @foreach(Auth::user()->bidangKeahlians as $bk)
                            <span class="px-2 py-0.5 bg-teal-100 text-teal-800 rounded-full text-xs">{{ $bk->nama_bidang }}</span>
                            @endforeach
                        </div>
                        @else
                        <span class="text-gray-400 text-sm">Belum diatur</span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Kuota Info -->
            @php $kuota = \App\Models\KuotaDosen::where('dosen_id', Auth::id())->first(); @endphp
            @if($kuota)
            <div class="mt-6 pt-6 border-t">
                <h3 class="text-sm font-semibold text-gray-700 mb-3">Informasi Kuota</h3>
                <div class="grid grid-cols-4 gap-4">
                    <div class="bg-blue-50 rounded-xl p-3 text-center">
                        <p class="text-xs text-gray-500">Kuota Pembimbing</p>
                        <p class="text-lg font-bold text-blue-700">{{ $kuota->kuota_pembimbing }}</p>
                    </div>
                    <div class="bg-amber-50 rounded-xl p-3 text-center">
                        <p class="text-xs text-gray-500">Terpakai</p>
                        <p class="text-lg font-bold text-amber-700">{{ $kuota->terpakai_pembimbing }}</p>
                    </div>
                    <div class="bg-purple-50 rounded-xl p-3 text-center">
                        <p class="text-xs text-gray-500">Kuota Penguji</p>
                        <p class="text-lg font-bold text-purple-700">{{ $kuota->kuota_penguji }}</p>
                    </div>
                    <div class="bg-amber-50 rounded-xl p-3 text-center">
                        <p class="text-xs text-gray-500">Terpakai</p>
                        <p class="text-lg font-bold text-amber-700">{{ $kuota->terpakai_penguji }}</p>
                    </div>
                </div>
            </div>
            @endif
        </div>
        @endif
    </div>
</div>

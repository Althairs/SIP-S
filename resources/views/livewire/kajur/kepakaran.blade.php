<div>
    @section('title', 'Kepakaran')
    @section('page-title', 'Daftar Kepakaran')

    @if (session()->has('success'))
    <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-800 rounded-xl flex items-center" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)">
        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
        {{ session('success') }}
    </div>
    @endif

    @if (session()->has('error'))
    <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-800 rounded-xl flex items-center" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)">
        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
        {{ session('error') }}
    </div>
    @endif

    <!-- Info Card -->
    <div class="bg-gradient-to-r from-cyan-600 to-cyan-800 rounded-2xl p-6 mb-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold">Hierarki Kepakaran</h2>
                <p class="text-cyan-100 mt-1">Level 1 = Tertinggi (Profesor), Level 7 = Terendah</p>
            </div>
            <button wire:click="openCreateModal" class="px-5 py-2.5 bg-white text-cyan-700 rounded-xl hover:bg-cyan-50 transition font-medium flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Tambah Kepakaran
            </button>
        </div>
    </div>

    <!-- Search -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 mb-6">
        <div class="relative">
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari kepakaran..."
                   class="w-full md:w-96 pl-10 pr-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-cyan-500">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
        </div>
    </div>

    <!-- Hierarki Visual -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Visualisasi Hierarki</h3>
        <div class="space-y-3">
            @foreach($kepakarans as $k)
            <div class="flex items-center space-x-4 p-3 rounded-xl {{ $k->is_active ? 'bg-gray-50' : 'bg-red-50 opacity-60' }}">
                <div class="w-12 h-12 bg-cyan-{{ min($k->hierarki_level * 100, 900) }} rounded-full flex items-center justify-center text-black font-bold text-lg flex-shrink-0">
                    {{ $k->hierarki_level }}
                </div>
                <div class="flex-1">
                    <div class="flex items-center gap-2">
                        <p class="font-semibold text-gray-900">{{ $k->nama_kepakaran }}</p>
                        @if(!$k->is_active)
                        <span class="px-2 py-0.5 bg-red-100 text-red-800 rounded-full text-xs">Nonaktif</span>
                        @endif
                    </div>
                    <p class="text-xs text-gray-500">{{ $k->deskripsi ?? 'Tidak ada deskripsi' }}</p>
                    <p class="text-xs text-cyan-600 mt-1">{{ $k->users_count }} dosen</p>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-xs text-gray-400">Level {{ $k->hierarki_level }}</span>
                    <button wire:click="openEditModal({{ $k->id }})" class="px-3 py-1.5 bg-amber-50 text-amber-700 rounded-lg hover:bg-amber-100 text-sm font-medium">Edit</button>
                    <button wire:click="toggleStatus({{ $k->id }})" class="px-3 py-1.5 {{ $k->is_active ? 'bg-gray-50 text-gray-600' : 'bg-green-50 text-green-700' }} rounded-lg hover:bg-gray-100 text-sm">
                        {{ $k->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                    </button>
                    @if($k->users_count == 0)
                    <button wire:click="deleteKepakaran({{ $k->id }})" wire:confirm="Hapus kepakaran ini?" class="px-3 py-1.5 bg-red-50 text-red-700 rounded-lg hover:bg-red-100 text-sm">Hapus</button>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Table View -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Level</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Nama Kepakaran</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Deskripsi</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-500 uppercase">Dosen</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-500 uppercase">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($kepakarans as $k)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center justify-center w-8 h-8 bg-cyan-100 text-cyan-800 rounded-full font-bold text-sm">
                                {{ $k->hierarki_level }}
                            </span>
                        </td>
                        <td class="px-6 py-4 font-medium text-gray-900">{{ $k->nama_kepakaran }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ Str::limit($k->deskripsi, 60) }}</td>
                        <td class="px-6 py-4 text-center text-sm">{{ $k->users_count }}</td>
                        <td class="px-6 py-4 text-center">
                            <span class="px-2 py-1 rounded-full text-xs font-medium {{ $k->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $k->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="px-6 py-8 text-center text-gray-500">Belum ada data kepakaran</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Create/Edit -->
    @if($showModal)
    <div class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-black/50" wire:click="closeModal"></div>
            <div class="relative bg-white rounded-2xl shadow-xl max-w-md w-full p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-gray-900">{{ $editMode ? 'Edit' : 'Tambah' }} Kepakaran</h3>
                    <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <form wire:submit="save">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Kepakaran <span class="text-red-500">*</span></label>
                            <input type="text" wire:model="nama_kepakaran" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-cyan-500 @error('nama_kepakaran') border-red-500 @enderror" placeholder="Contoh: Profesor">
                            @error('nama_kepakaran') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Level Hierarki <span class="text-red-500">*</span></label>
                            <input type="number" wire:model="hierarki_level" min="1" max="20" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-cyan-500 @error('hierarki_level') border-red-500 @enderror">
                            <p class="text-xs text-gray-400 mt-1">1 = Tertinggi (Profesor), 7 = Terendah</p>
                            @error('hierarki_level') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                            <textarea wire:model="deskripsi" rows="2" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-cyan-500" placeholder="Deskripsi singkat..."></textarea>
                        </div>

                        <div class="flex items-center">
                            <input type="checkbox" wire:model="is_active" class="w-4 h-4 text-cyan-700 border-gray-300 rounded focus:ring-cyan-500">
                            <label class="ml-2 text-sm text-gray-700">Aktif</label>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end gap-4">
                        <button type="button" wire:click="closeModal" class="px-6 py-2.5 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 font-medium">Batal</button>
                        <button type="submit" class="px-6 py-2.5 bg-cyan-700 text-white rounded-xl hover:bg-cyan-800 font-medium">{{ $editMode ? 'Perbarui' : 'Simpan' }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>

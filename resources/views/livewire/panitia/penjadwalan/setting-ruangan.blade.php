<div>
    @section('title', 'Pengaturan Ruangan')
    @section('page-title', 'Pengaturan Ruangan Ujian')

    @if (session()->has('success'))
    <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-800 rounded-xl flex items-center" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)">
        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
        {{ session('success') }}
    </div>
    @endif

    <!-- Breadcrumb -->
    <nav class="flex items-center text-sm text-gray-500 mb-4">
        <a href="{{ route('panitia.penjadwalan.setting-ruangan') }}" class="hover:text-green-700 transition">Pengaturan</a>
        <span class="mx-2">/</span>
        <a href="{{ route('panitia.penjadwalan.setting-ruangan') }}" class="hover:text-green-700 transition">Ruangan</a>
        @if($showForm)
        <span class="mx-2">/</span>
        <span class="text-gray-900 font-medium">{{ $editMode ? 'Edit' : 'Tambah' }}</span>
        @endif
    </nav>

    @if($showForm)
    <!-- Inline Form Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-bold text-gray-900">{{ $editMode ? 'Edit' : 'Tambah' }} Ruangan</h3>
            <button wire:click="closeForm" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form wire:submit="save">
            <div class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kode <span class="text-red-500">*</span></label>
                        <input type="text" wire:model="kode_ruangan" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 @error('kode_ruangan') border-red-500 @enderror" placeholder="R001">
                        @error('kode_ruangan') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kapasitas <span class="text-red-500">*</span></label>
                        <input type="number" wire:model="kapasitas" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500" min="1" max="100">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Ruangan <span class="text-red-500">*</span></label>
                    <input type="text" wire:model="nama_ruangan" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 @error('nama_ruangan') border-red-500 @enderror">
                    @error('nama_ruangan') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Lokasi</label>
                    <input type="text" wire:model="lokasi" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500" placeholder="Lt. 2, Gedung A">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                    <textarea wire:model="deskripsi" rows="2" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500"></textarea>
                </div>
                <div class="flex items-center">
                    <input type="checkbox" wire:model="is_active" class="w-4 h-4 text-green-700 rounded focus:ring-green-500">
                    <label class="ml-2 text-sm">Aktif</label>
                </div>
            </div>
            <div class="mt-6 flex justify-end gap-4">
                <button type="button" wire:click="closeForm" class="px-6 py-2.5 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 font-medium">Batal</button>
                <button type="submit" class="px-6 py-2.5 bg-green-700 text-white rounded-xl hover:bg-green-800 font-medium">{{ $editMode ? 'Perbarui' : 'Simpan' }}</button>
            </div>
        </form>
    </div>

    @else
    <!-- List Mode -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div class="relative flex-1">
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari ruangan..."
                       class="w-full md:w-96 pl-10 pr-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>
            <button wire:click="openCreate" class="px-5 py-2.5 bg-green-700 text-white rounded-xl hover:bg-green-800 transition font-medium flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Tambah Ruangan
            </button>
        </div>
    </div>

    <!-- Ruangan Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
        @forelse($ruangans as $ruangan)
        <div class="bg-white rounded-2xl shadow-sm border {{ $ruangan->is_active ? 'border-gray-100' : 'border-red-200 opacity-60' }} p-6">
            <div class="flex items-start justify-between mb-3">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-green-100 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-900">{{ $ruangan->nama_ruangan }}</p>
                        <p class="text-xs text-gray-500">{{ $ruangan->kode_ruangan }}</p>
                    </div>
                </div>
                <span class="px-2 py-1 rounded-full text-xs font-medium {{ $ruangan->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                    {{ $ruangan->is_active ? 'Aktif' : 'Nonaktif' }}
                </span>
            </div>

            <div class="space-y-1 text-sm text-gray-600">
                @if($ruangan->lokasi)
                <p><span class="text-gray-400">Lokasi:</span> {{ $ruangan->lokasi }}</p>
                @endif
                <p><span class="text-gray-400">Kapasitas:</span> {{ $ruangan->kapasitas }} orang</p>
            </div>

            <div class="mt-3 flex items-center gap-2">
                <button wire:click="openEdit({{ $ruangan->id }})" class="flex-1 px-3 py-1.5 bg-amber-50 text-amber-700 rounded-lg hover:bg-amber-100 text-sm font-medium text-center">Edit</button>
                <button wire:click="toggleStatus({{ $ruangan->id }})" class="px-3 py-1.5 {{ $ruangan->is_active ? 'bg-gray-50 text-gray-600' : 'bg-green-50 text-green-700' }} rounded-lg hover:bg-gray-100 text-sm">{{ $ruangan->is_active ? 'Nonaktifkan' : 'Aktifkan' }}</button>
                <button wire:click="deleteRuangan({{ $ruangan->id }})" wire:confirm="Hapus ruangan ini?" class="px-3 py-1.5 bg-red-50 text-red-700 rounded-lg hover:bg-red-100 text-sm">Hapus</button>
            </div>
        </div>
        @empty
        <div class="col-span-full text-center py-12 text-gray-500">Belum ada ruangan</div>
        @endforelse
    </div>

    {{ $ruangans->links() }}
    @endif
</div>

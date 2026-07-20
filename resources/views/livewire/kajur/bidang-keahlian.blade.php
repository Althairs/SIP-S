<div>
    @section('title', 'Bidang Keahlian')
    @section('page-title', 'Bidang Keahlian')

    <nav class="mb-4 text-sm text-gray-500">
        <ol class="flex items-center space-x-1">
            <li>Data Master</li>
            <li><span class="mx-1">/</span></li>
            @if($showForm)
                <li><a href="{{ route('kajur.data-master.bidang-keahlian') }}" class="hover:text-green-700">Bidang Keahlian</a></li>
                <li><span class="mx-1">/</span></li>
                <li class="text-gray-900 font-medium">{{ $editMode ? 'Edit' : 'Tambah' }}</li>
            @else
                <li class="text-gray-900 font-medium">Bidang Keahlian</li>
            @endif
        </ol>
    </nav>

    @if (session()->has('success'))
    <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-800 rounded-xl flex items-center" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)">
        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
        {{ session('success') }}
    </div>
    @endif

    @if($showForm)
    {{-- Inline Form Card --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h2 class="text-lg font-semibold mb-4">{{ $editMode ? 'Edit' : 'Tambah' }} Bidang Keahlian</h2>
        <form wire:submit="save">
            <div class="space-y-4">
                @if(count($listJurusan) > 0)
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Jurusan <span class="text-red-500">*</span></label>
                    <select wire:model="selectedJurusan" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 @error('selectedJurusan') border-red-500 @enderror">
                        <option value="">Pilih Jurusan</option>
                        @foreach($listJurusan as $j)
                        <option value="{{ $j->id }}">{{ $j->nama_jurusan }}</option>
                        @endforeach
                    </select>
                    @error('selectedJurusan') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
                @endif
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kode <span class="text-red-500">*</span></label>
                    <input type="text" wire:model="kode" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 @error('kode') border-red-500 @enderror" placeholder="Contoh: BDT">
                    @error('kode') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Bidang <span class="text-red-500">*</span></label>
                    <input type="text" wire:model="nama_bidang" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 @error('nama_bidang') border-red-500 @enderror" placeholder="Contoh: Budidaya Tanaman">
                    @error('nama_bidang') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                    <textarea wire:model="deskripsi" rows="3" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500" placeholder="Deskripsi singkat..."></textarea>
                </div>
                <div class="flex items-center">
                    <input type="checkbox" wire:model="is_active" class="w-4 h-4 text-green-700 rounded focus:ring-green-500">
                    <label class="ml-2 text-sm text-gray-700">Aktif</label>
                </div>
            </div>
            <div class="mt-6 flex justify-end gap-4">
                <button type="button" wire:click="closeForm" class="px-6 py-2.5 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 font-medium">Batal</button>
                <button type="submit" class="px-6 py-2.5 bg-green-700 text-white rounded-xl hover:bg-green-800 font-medium">{{ $editMode ? 'Perbarui' : 'Simpan' }}</button>
            </div>
        </form>
    </div>
    @else
    {{-- Table View --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div class="relative flex-1">
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari bidang keahlian..."
                       class="w-full md:w-96 pl-10 pr-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500">
                <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
            <button wire:click="openCreate" class="px-5 py-2.5 bg-green-700 text-white rounded-xl hover:bg-green-800 transition font-medium flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Tambah Bidang
            </button>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">No</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Kode</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Nama Bidang</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Deskripsi</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Pendaftaran</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($bidangs as $index => $bidang)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 text-sm">{{ $bidangs->firstItem() + $index }}</td>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $bidang->kode }}</td>
                        <td class="px-6 py-4 text-sm text-gray-700">{{ $bidang->nama_bidang }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500 max-w-xs truncate">{{ Str::limit($bidang->deskripsi, 50) }}</td>
                        <td class="px-6 py-4 text-sm text-gray-700">{{ $bidang->pendaftarans_count }}</td>
                        <td class="px-6 py-4">
                            <button wire:click="toggleStatus({{ $bidang->id }})"
                                    class="px-3 py-1 rounded-full text-xs font-medium {{ $bidang->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $bidang->is_active ? 'Aktif' : 'Nonaktif' }}
                            </button>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <button wire:click="openEdit({{ $bidang->id }})" class="px-3 py-1.5 bg-amber-50 text-amber-700 rounded-lg hover:bg-amber-100 text-sm font-medium">Edit</button>
                                <button wire:click="deleteBidang({{ $bidang->id }})" wire:confirm="Hapus bidang ini?" class="px-3 py-1.5 bg-red-50 text-red-700 rounded-lg hover:bg-red-100 text-sm font-medium">Hapus</button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="px-6 py-8 text-center text-gray-500">Belum ada bidang keahlian</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t">{{ $bidangs->links() }}</div>
    </div>
    @endif
</div>

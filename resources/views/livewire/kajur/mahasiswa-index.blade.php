<div>
    @section('title', 'Data Mahasiswa')
    @section('page-title', 'Data Mahasiswa')

    @if (session()->has('success'))
        <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-800 rounded-xl flex items-center"
            x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd"
                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                    clip-rule="evenodd"></path>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    @if (session()->has('info'))
        <div class="mb-4 p-4 bg-blue-50 border border-blue-200 text-blue-800 rounded-xl flex items-center"
            x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd"
                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                    clip-rule="evenodd"></path>
            </svg>
            {{ session('info') }}
        </div>
    @endif

    <!-- Action Bar -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div class="flex-1 flex flex-col md:flex-row gap-3">
                <div class="relative flex-1">
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari mahasiswa..."
                        class="w-full pl-10 pr-4 py-2.5 border border-gray-300 text-gray-400 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                    <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <select wire:model.change="prodiFilter"
                    class="px-4 py-2.5 pr-10 border border-gray-300 text-gray-400 rounded-xl focus:ring-2 appearance-none cursor-pointer bg-white focus:ring-purple-500 focus:border-purple-500">
                    <option value="">Semua Prodi</option>
                    @foreach($prodis as $prodi)
                        <option value="{{ $prodi->id }}">{{ $prodi->nama_prodi }}</option>
                    @endforeach
                </select>
                <select wire:model.change="statusFilter"
                    class="px-4 py-2.5 pr-10 border border-gray-300 text-gray-400 rounded-xl focus:ring-2 appearance-none cursor-pointer bg-white focus:ring-purple-500 focus:border-purple-500">
                    <option value="">Semua Status</option>
                    <option value="1">Aktif</option>
                    <option value="0">Nonaktif</option>
                </select>
            </div>
            <div class="flex items-center gap-3">
                <button wire:click="exportExcel"
                    class="px-4 py-2.5 bg-green-50 text-green-700 border border-green-200 rounded-xl hover:bg-green-100 transition font-medium flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                        </path>
                    </svg>
                    Export
                </button>
                @if($canCreate)

                    <button wire:click="openCreateModal"
                        class="px-5 py-2.5 bg-purple-700 text-white rounded-xl hover:bg-purple-800 transition font-medium flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Tambah Mahasiswa
                    </button>

                @endif
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-500 uppercase">No</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-500 uppercase">Mahasiswa</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-500 uppercase">NIM</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-500 uppercase">Prodi</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-500 uppercase">Kontak</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($mahasiswas as $index => $mhs)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm text-gray-700">{{ $mahasiswas->firstItem() + $index }}</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-3">
                                    <img class="w-10 h-10 rounded-full"
                                        src="https://ui-avatars.com/api/?name={{ urlencode($mhs->name) }}&background=8b5cf6&color=fff"
                                        alt="">
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $mhs->name }}</p>
                                        <p class="text-sm text-gray-500">{{ $mhs->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $mhs->nim ?? '-' }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 bg-purple-100 text-purple-800 rounded-full text-xs font-medium whitespace-nowrap">
                                    {{ $mhs->prodi->nama_prodi ?? '-' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700">{{ $mhs->nomor_hp ?? '-' }}</td>
                            <td class="px-6 py-4">
                                <button wire:click="toggleStatus({{ $mhs->id }})"
                                    class="px-3 py-1 rounded-full text-xs font-medium {{ $mhs->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $mhs->is_active ? 'Aktif' : 'Nonaktif' }}
                                </button>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    @if($canEdit)
                                    <button wire:click="openEditModal({{ $mhs->id }})"
                                        class="px-3 py-1.5 bg-amber-50 text-amber-700 rounded-lg hover:bg-amber-100 transition text-sm font-medium">
                                        Edit
                                    </button>
                                    @endif
                                    @if($canDelete)
                                    <button wire:click="deleteMahasiswa({{ $mhs->id }})"
                                        wire:confirm="Yakin ingin menghapus mahasiswa ini?"
                                        class="px-3 py-1.5 bg-red-50 text-red-700 rounded-lg hover:bg-red-100 transition text-sm font-medium">
                                        Hapus
                                    </button>
                                    @endif
                                    @if(!$canEdit && !$canDelete)
                                        <span class="text-xs text-gray-400">Read only</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-gray-500">Tidak ada data mahasiswa</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $mahasiswas->links() }}
        </div>
    </div>

    <!-- Modal Create/Edit -->
    @if($showModal)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="fixed inset-0 bg-black/50" wire:click="closeModal"></div>
                <div class="relative bg-white rounded-2xl shadow-xl max-w-lg w-full p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">
                            {{ $editMode ? 'Edit Mahasiswa' : 'Tambah Mahasiswa' }}</h3>
                        <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <form wire:submit="save">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nama <span
                                        class="text-red-500">*</span></label>
                                <input type="text" wire:model="name"
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('name') border-red-500 @enderror">
                                @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Email <span
                                        class="text-red-500">*</span></label>
                                <input type="email" wire:model="email"
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('email') border-red-500 @enderror">
                                @error('email') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Password
                                        {{ $editMode ? '' : '*' }}</label>
                                    <input type="password" wire:model="password"
                                        class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                                        placeholder="{{ $editMode ? 'Kosongkan jika tidak diubah' : '' }}">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password</label>
                                    <input type="password" wire:model="password_confirmation"
                                        class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">NIM <span
                                        class="text-red-500">*</span></label>
                                <input type="text" wire:model="nim"
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('nim') border-red-500 @enderror">
                                @error('nim') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Program Studi</label>
                                <select wire:model="prodi_id"
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                                    <option value="">Pilih Prodi</option>
                                    @foreach($prodis as $prodi)
                                        <option value="{{ $prodi->id }}">{{ $prodi->nama_prodi }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nomor HP</label>
                                <input type="text" wire:model="nomor_hp"
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                                <textarea wire:model="alamat" rows="2"
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500"></textarea>
                            </div>

                            <div class="flex items-center">
                                <input type="checkbox" wire:model="is_active"
                                    class="w-4 h-4 text-purple-700 border-gray-300 rounded focus:ring-purple-500">
                                <label class="ml-2 text-sm text-gray-700">Akun Aktif</label>
                            </div>
                        </div>

                        <div class="mt-6 flex items-center justify-end gap-4">
                            <button type="button" wire:click="closeModal"
                                class="px-6 py-2.5 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition font-medium">Batal</button>
                            <button type="submit"
                                class="px-6 py-2.5 bg-purple-700 text-white rounded-xl hover:bg-purple-800 transition font-medium">{{ $editMode ? 'Perbarui' : 'Simpan' }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>

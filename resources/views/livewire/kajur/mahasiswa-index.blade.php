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
        <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-800 rounded-xl flex items-center"
            x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd"
                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                    clip-rule="evenodd"></path>
            </svg>
            {{ session('info') }}
        </div>
    @endif

    @if($showImportView)
        <!-- Import Layout -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
            <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-100">
                <div>
                    <h2 class="text-xl font-bold text-gray-900">Import Data Mahasiswa</h2>
                    <p class="text-sm text-gray-500 mt-1">Unggah file Excel (.xlsx atau .xls) untuk mengimpor atau memperbarui data mahasiswa.</p>
                </div>
                <button wire:click="toggleImportView" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl font-medium transition flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali
                </button>
            </div>

            <form wire:submit.prevent="importExcel" class="space-y-6">
                <!-- Dropzone / File Upload -->
                <div class="flex justify-center items-center w-full">
                    <label class="flex flex-col justify-center items-center w-full h-64 bg-gray-50 rounded-2xl border-2 border-gray-300 border-dashed cursor-pointer hover:bg-gray-100 transition relative">
                        <div class="flex flex-col justify-center items-center pt-5 pb-6">
                            <svg class="mb-3 w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                            </svg>
                            <p class="mb-2 text-sm text-gray-500"><span class="font-semibold">Klik untuk memilih</span> atau seret file ke sini</p>
                            <p class="text-xs text-gray-400">Berkas Excel (XLSX, XLS) maks. 10MB</p>
                        </div>
                        <input type="file" wire:model="file" class="hidden" accept=".xlsx,.xls" />
                    </label>
                </div>

                @if($file)
                    <div class="flex items-center justify-between p-4 bg-green-50 text-green-700 rounded-xl border border-green-100">
                        <div class="flex items-center gap-3">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <div>
                                <p class="text-sm font-medium">{{ $file->getClientOriginalName() }}</p>
                                <p class="text-xs opacity-75">{{ round($file->getSize() / 1024, 2) }} KB</p>
                            </div>
                        </div>
                        <button type="button" wire:click="$set('file', null)" class="text-green-500 hover:text-green-700">
                            Hapus
                        </button>
                    </div>
                @endif

                @error('file')
                    <div class="p-4 bg-red-50 border border-red-200 text-red-800 rounded-xl text-sm flex items-start gap-2">
                        <svg class="w-5 h-5 text-red-600 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                        <div>
                            <span class="font-semibold">Kesalahan Validasi:</span> {{ $message }}
                        </div>
                    </div>
                @enderror

                <!-- Requirements Info -->
                <div class="p-4 bg-green-50 border border-green-200 text-green-800 rounded-xl">
                    <h3 class="font-bold text-sm">Daftar Kolom Wajib di Excel:</h3>
                    <p class="text-xs mt-1">File Excel harus memiliki header di baris pertama dengan kolom-kolom berikut (tidak sensitif huruf besar/kecil, urutan bebas):</p>
                    <div class="mt-2 flex flex-wrap gap-2">
                        @foreach(['No', 'Nama', 'NIM', 'Fakultas', 'Prodi', 'Status Awal', 'Semester Awal Terdaftar', 'Status Aktif'] as $col)
                            <span class="px-2.5 py-1 bg-green-100 text-green-800 rounded-md text-xs font-semibold">{{ $col }}</span>
                        @endforeach
                    </div>
                </div>

                <!-- Submit buttons -->
                <div class="flex justify-end gap-3">
                    <button type="button" wire:click="toggleImportView" class="px-6 py-2.5 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition font-medium">Batal</button>
                    <button type="submit" class="px-6 py-2.5 bg-green-700 text-white rounded-xl hover:bg-green-800 transition font-medium flex items-center gap-2" wire:loading.attr="disabled" wire:target="file, importExcel">
                        <span wire:loading.remove wire:target="importExcel">Proses Import</span>
                        <span wire:loading wire:target="importExcel" class="animate-spin inline-block w-4 h-4 border-2 border-current border-t-transparent rounded-full" role="status"></span>
                        <span wire:loading wire:target="importExcel">Memproses...</span>
                    </button>
                </div>
            </form>
        </div>
    @elseif($showForm)
        <nav class="mb-6">
            <ol class="flex items-center space-x-2 text-sm text-gray-500">
                <li><a href="#" class="hover:text-green-700">Data Master</a></li>
                <li><span class="mx-2">/</span></li>
                <li><a href="{{ route('kajur.data-master.mahasiswa') }}" class="hover:text-green-700">Data Mahasiswa</a></li>
                <li><span class="mx-2">/</span></li>
                <li class="text-gray-900 font-medium">{{ $editMode ? 'Edit' : 'Tambah' }}</li>
            </ol>
        </nav>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-100">
                <h2 class="text-xl font-bold text-gray-900">{{ $editMode ? 'Edit Mahasiswa' : 'Tambah Mahasiswa' }}</h2>
                <button wire:click="closeForm" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl font-medium transition flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali
                </button>
            </div>

            <form wire:submit="save" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama <span class="text-red-500">*</span></label>
                        <input type="text" wire:model="name" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('name') border-red-500 @enderror">
                        @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email <span class="text-red-500">*</span></label>
                        <input type="email" wire:model="email" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('email') border-red-500 @enderror">
                        @error('email') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Password {{ $editMode ? '' : '*' }}</label>
                        <input type="password" wire:model="password" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500" placeholder="{{ $editMode ? 'Kosongkan jika tidak diubah' : '' }}">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password</label>
                        <input type="password" wire:model="password_confirmation" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">NIM <span class="text-red-500">*</span></label>
                        <input type="text" wire:model="nim" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('nim') border-red-500 @enderror">
                        @error('nim') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Program Studi</label>
                        <select wire:model="prodi_id" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500">
                            <option value="">Pilih Prodi</option>
                            @foreach($prodis as $prodi)
                                <option value="{{ $prodi->id }}">{{ $prodi->nama_prodi }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nomor HP</label>
                        <input type="text" wire:model="nomor_hp" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500">
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                        <textarea wire:model="alamat" rows="2" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500"></textarea>
                    </div>

                    <div class="md:col-span-2">
                        <div class="flex items-center">
                            <input type="checkbox" wire:model="is_active" class="w-4 h-4 text-green-700 border-gray-300 rounded focus:ring-green-500">
                            <label class="ml-2 text-sm text-gray-700">Akun Aktif</label>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                    <button type="button" wire:click="closeForm" class="px-6 py-2.5 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition font-medium">Batal</button>
                    <button type="submit" class="px-6 py-2.5 bg-green-700 text-white rounded-xl hover:bg-green-800 transition font-medium">{{ $editMode ? 'Perbarui' : 'Simpan' }}</button>
                </div>
            </form>
        </div>

    @else
        <nav class="mb-6">
            <ol class="flex items-center space-x-2 text-sm text-gray-500">
                <li><a href="#" class="hover:text-green-700">Data Master</a></li>
                <li><span class="mx-2">/</span></li>
                <li class="text-gray-900 font-medium">Data Mahasiswa</li>
            </ol>
        </nav>

        <!-- Action Bar -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 mb-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div class="flex-1 flex flex-col md:flex-row gap-3">
                    <div class="relative flex-1">
                        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari mahasiswa..."
                            class="w-full pl-10 pr-4 py-2.5 border border-gray-300 text-gray-400 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <select wire:model.change="prodiFilter"
                        class="px-4 py-2.5 pr-10 border border-gray-300 text-gray-400 rounded-xl focus:ring-2 appearance-none cursor-pointer bg-white focus:ring-green-500 focus:border-green-500">
                        <option value="">Semua Prodi</option>
                        @foreach($prodis as $prodi)
                            <option value="{{ $prodi->id }}">{{ $prodi->nama_prodi }}</option>
                        @endforeach
                    </select>
                    <select wire:model.change="statusFilter"
                        class="px-4 py-2.5 pr-10 border border-gray-300 text-gray-400 rounded-xl focus:ring-2 appearance-none cursor-pointer bg-white focus:ring-green-500 focus:border-green-500">
                        <option value="">Semua Status</option>
                        <option value="1">Aktif</option>
                        <option value="0">Nonaktif</option>
                    </select>
                </div>
                <div class="flex items-center gap-3">
                    <button wire:click="toggleImportView"
                        class="px-4 py-2.5 bg-green-50 text-green-700 border border-green-200 rounded-xl hover:bg-green-100 transition font-medium flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12">
                            </path>
                        </svg>
                        Import
                    </button>
                    @if($canCreate)

                        <button wire:click="openCreate"
                            class="px-5 py-2.5 bg-green-700 text-white rounded-xl hover:bg-green-800 transition font-medium flex items-center gap-2">
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
                                        src="https://ui-avatars.com/api/?name={{ urlencode($mhs->name) }}&background=16a34a&color=fff"
                                        alt="">
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $mhs->name }}</p>
                                        <p class="text-sm text-gray-500">{{ $mhs->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $mhs->nim ?? '-' }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs font-medium whitespace-nowrap">
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
                                    <button wire:click="openEdit({{ $mhs->id }})"
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
    @endif


</div>

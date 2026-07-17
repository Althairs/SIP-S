<div>
    @section('title', 'Tambah Program Studi')
    @section('page-title', 'Tambah Program Studi Baru')

    <div class="max-w-3xl mx-auto">
        <nav class="flex mb-6" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('admin.dashboard') }}" class="text-gray-500 hover:text-gray-700">Dashboard</a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-4 h-4 text-gray-400 mx-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <a href="{{ route('admin.prodis.index') }}" class="text-gray-500 hover:text-gray-700">Prodi</a>
                    </div>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-4 h-4 text-gray-400 mx-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="text-green-700 font-medium">Tambah</span>
                    </div>
                </li>
            </ol>
        </nav>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <form wire:submit="save">
                <div class="space-y-6">
                    <div>
                        <label for="jurusan_id" class="block text-sm font-medium text-gray-700 mb-1">
                            Jurusan <span class="text-red-500">*</span>
                        </label>
                        <select id="jurusan_id"
                                wire:model="jurusan_id"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('jurusan_id') border-red-500 @enderror">
                            <option value="">Pilih Jurusan</option>
                            @foreach($jurusans as $jurusan)
                            <option value="{{ $jurusan->id }}">{{ $jurusan->nama_jurusan }} ({{ $jurusan->kode_jurusan }})</option>
                            @endforeach
                        </select>
                        @error('jurusan_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="kode_prodi" class="block text-sm font-medium text-gray-700 mb-1">
                            Kode Prodi <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               id="kode_prodi"
                               wire:model="kode_prodi"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('kode_prodi') border-red-500 @enderror"
                               placeholder="Contoh: AGT">
                        @error('kode_prodi')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="nama_prodi" class="block text-sm font-medium text-gray-700 mb-1">
                            Nama Prodi <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               id="nama_prodi"
                               wire:model="nama_prodi"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('nama_prodi') border-red-500 @enderror"
                               placeholder="Contoh: Agroteknologi">
                        @error('nama_prodi')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-1">
                            Deskripsi
                        </label>
                        <textarea id="deskripsi"
                                  wire:model="deskripsi"
                                  rows="3"
                                  class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                  placeholder="Deskripsi program studi..."></textarea>
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox"
                               id="is_active"
                               wire:model="is_active"
                               class="w-4 h-4 text-green-700 border-gray-300 rounded focus:ring-green-500">
                        <label for="is_active" class="ml-2 text-sm text-gray-700">
                            Aktifkan program studi ini
                        </label>
                    </div>
                </div>

                <div class="mt-8 flex items-center justify-end gap-4">
                    <a href="{{ route('admin.prodis.index') }}"
                       class="px-6 py-2.5 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition font-medium">
                        Batal
                    </a>
                    <button type="submit"
                            class="px-6 py-2.5 bg-green-700 text-white rounded-xl hover:bg-green-800 transition font-medium">
                        Simpan Prodi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

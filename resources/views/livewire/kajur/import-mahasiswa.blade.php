<div>
    @section('title', 'Import Data Mahasiswa')
    @section('page-title', 'Import Data Mahasiswa')

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

    <nav class="mb-6">
        <ol class="flex items-center space-x-2 text-sm text-gray-500">
            <li><a href="#" class="hover:text-green-700">Data Master</a></li>
            <li><span class="mx-2">/</span></li>
            <li class="text-gray-900 font-medium">Import Data Mahasiswa</li>
        </ol>
    </nav>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div class="mb-6 pb-4 border-b border-gray-100">
            <h2 class="text-xl font-bold text-gray-900">Import Data Mahasiswa</h2>
            <p class="text-sm text-gray-500 mt-1">Unggah file Excel (.xlsx atau .xls) untuk mengimpor atau memperbarui data mahasiswa.</p>
        </div>

        <form wire:submit.prevent="importExcel" class="space-y-6">
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

            <div class="p-4 bg-green-50 border border-green-200 text-green-800 rounded-xl">
                <h3 class="font-bold text-sm">Daftar Kolom Wajib di Excel:</h3>
                <p class="text-xs mt-1">File Excel harus memiliki header di baris pertama dengan kolom-kolom berikut :</p>
                <div class="mt-2 flex flex-wrap gap-2">
                    @foreach(['No', 'Nama', 'NIM', 'Fakultas', 'Prodi', 'Status Awal', 'Semester Awal Terdaftar', 'Status Aktif'] as $col)
                        <span class="px-2.5 py-1 bg-green-100 text-green-800 rounded-md text-xs font-semibold">{{ $col }}</span>
                    @endforeach
                </div>
            </div>

            <div class="flex justify-end gap-3">
                <a href="{{ route('kajur.data-master.mahasiswa') }}" class="px-6 py-2.5 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition font-medium text-center">Kembali</a>
                <button type="submit" class="px-6 py-2.5 bg-green-700 text-white rounded-xl hover:bg-green-800 transition font-medium flex items-center gap-2" wire:loading.attr="disabled" wire:target="file, importExcel">
                    <span wire:loading.remove wire:target="importExcel">Proses Import</span>
                    <span wire:loading wire:target="importExcel" class="animate-spin inline-block w-4 h-4 border-2 border-current border-t-transparent rounded-full" role="status"></span>
                    <span wire:loading wire:target="importExcel">Memproses...</span>
                </button>
            </div>
        </form>
    </div>
</div>

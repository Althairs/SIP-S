<div>
    @section('title', 'Upload Nilai via Berkas')
    @section('page-title', 'Upload Nilai via Berkas')

    @if (session()->has('success'))
    <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-800 rounded-xl" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)">{{ session('success') }}</div>
    @endif

    <div class="max-w-2xl mx-auto">
        <!-- Info -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-500">Mahasiswa</p>
                    <p class="font-bold">{{ $pendaftaran->mahasiswa->name }} ({{ $pendaftaran->mahasiswa->nim }})</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Jenis Ujian</p>
                    <span class="px-2 py-1 bg-indigo-100 text-indigo-800 rounded-full text-xs">{{ ucwords(str_replace('_', ' ', $pendaftaran->jenis_ujian)) }}</span>
                </div>
                <div class="col-span-2">
                    <p class="text-sm text-gray-500">Judul</p>
                    <p class="font-medium">{{ $pendaftaran->judul_penelitian }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Peran Anda</p>
                    <span class="px-2 py-1 bg-amber-100 text-amber-800 rounded-full text-xs">{{ ucwords(str_replace('_', ' ', $peranDosen)) }}</span>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Tipe Input</p>
                    <span class="px-2 py-1 bg-orange-100 text-orange-800 rounded-full text-xs">📎 Upload Berkas</span>
                </div>
            </div>
        </div>

        <!-- Info Upload -->
        <div class="bg-amber-50 border border-amber-200 rounded-2xl p-4 mb-6">
            <div class="flex items-start space-x-3">
                <svg class="w-5 h-5 text-amber-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
                <div>
                    <p class="text-sm font-medium text-amber-800">Informasi Penting</p>
                    <p class="text-xs text-amber-700 mt-1">Berkas yang diupload akan diteruskan ke <strong>Panitia Administrasi</strong> untuk diinput nilainya ke sistem. Pastikan format penilaian sudah sesuai ketentuan.</p>
                </div>
            </div>
        </div>

        <!-- Existing File -->
        @if($existingPenilaian && $existingPenilaian->file_penilaian)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
            <h3 class="text-sm font-semibold text-gray-700 mb-2">📄 Berkas Sebelumnya</h3>
            <a href="{{ asset('storage/' . $existingPenilaian->file_penilaian) }}" target="_blank" class="flex items-center gap-2 p-3 bg-blue-50 rounded-xl text-blue-700 hover:bg-blue-100">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                <span class="text-sm font-medium">Lihat Berkas Sebelumnya</span>
            </a>
        </div>
        @endif

        <!-- Form Upload -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <form wire:submit="save">
                <div class="space-y-6">
                    <!-- Upload -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Upload Berkas Penilaian <span class="text-red-500">*</span></label>
                        <div class="border-2 border-dashed border-gray-300 rounded-2xl p-8 text-center hover:border-indigo-400 transition cursor-pointer"
                             x-data
                             x-on:click="$refs.fileInput.click()">
                            <input type="file" wire:model="filePenilaian" x-ref="fileInput" class="hidden" accept=".pdf,.jpg,.jpeg,.png">
                            @if($filePenilaian)
                            <div class="text-green-600">
                                <svg class="w-10 h-10 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <p class="font-medium">{{ $filePenilaian->getClientOriginalName() }}</p>
                                <p class="text-xs">{{ round($filePenilaian->getSize() / 1024, 1) }} KB</p>
                            </div>
                            @else
                            <svg class="w-10 h-10 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                            <p class="text-gray-500">Klik untuk upload atau <span class="text-indigo-600">drag & drop</span></p>
                            <p class="text-xs text-gray-400 mt-1">PDF, JPG, PNG (Maks. 5MB)</p>
                            @endif
                        </div>
                        @error('filePenilaian') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <!-- Catatan -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Catatan (Opsional)</label>
                        <textarea wire:model="catatan" rows="3" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500" placeholder="Catatan untuk panitia administrasi..."></textarea>
                    </div>
                </div>

                <div class="mt-6 flex justify-between">
                    <a href="{{ route('dosen.nilai.index') }}" class="px-6 py-2.5 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 font-medium">Kembali</a>
                    <button type="submit" class="px-6 py-2.5 bg-indigo-700 text-white rounded-xl hover:bg-indigo-800 font-medium shadow-sm shadow-indigo-200">
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                        {{ $existingPenilaian ? 'Upload Ulang' : 'Upload Berkas' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

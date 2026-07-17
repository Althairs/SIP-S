<div>
    @section('title', 'Upload Nilai via Berkas')
    @section('page-title', 'Upload Nilai via Berkas')

    @if (session()->has('success'))
    <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-800 rounded-xl flex items-center">
        <svg class="w-5 h-5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
        {{ session('success') }}
    </div>
    @endif

    <div class="max-w-4xl mx-auto">
        <!-- Info Ujian -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-500">Mahasiswa</p>
                    <p class="font-bold text-lg">{{ $pendaftaran->mahasiswa->name }} ({{ $pendaftaran->mahasiswa->nim }})</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Jenis Ujian</p>
                    <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">{{ ucwords(str_replace('_', ' ', $pendaftaran->jenis_ujian)) }}</span>
                </div>
                <div class="col-span-2">
                    <p class="text-sm text-gray-500">Judul</p>
                    <p class="font-medium">{{ $pendaftaran->judul_penelitian }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Peran Anda</p>
                    <span class="px-2 py-1 bg-amber-100 text-amber-800 rounded-full text-xs font-medium">{{ ucwords(str_replace('_', ' ', $peranDosen)) }}</span>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Tipe Input</p>
                    <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs inline-flex items-center">
                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                        Upload Berkas
                    </span>
                </div>
            </div>
        </div>

        <!-- Info Panitia Administrasi -->
        <div class="bg-amber-50 border border-amber-200 rounded-2xl p-4 mb-6">
            <div class="flex items-start space-x-3">
                <svg class="w-5 h-5 text-amber-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
                <div>
                    <p class="text-sm font-medium text-amber-800">Berkas akan diteruskan ke Panitia Administrasi</p>
                    <p class="text-xs text-amber-700 mt-1">Upload berkas penilaian dalam format PDF, JPG, atau PNG. Panitia administrasi akan mengelola berkas ini dan menginput nilai ke sistem.</p>
                </div>
            </div>
        </div>

        <!-- Existing File -->
        @if($existingPenilaian && $existingPenilaian->file_penilaian)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
            <h3 class="text-sm font-semibold text-gray-700 mb-3 flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Berkas Sebelumnya
            </h3>
            <a href="{{ asset('storage/' . $existingPenilaian->file_penilaian) }}" target="_blank" class="flex items-center gap-2 p-3 bg-green-50 rounded-xl text-green-700 hover:bg-green-100 border border-green-100">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                <span class="text-sm font-medium">Lihat Berkas Sebelumnya</span>
            </a>
            @if($existingPenilaian->submitted_at)
            <p class="text-xs text-gray-400 mt-2">Diupload: {{ $existingPenilaian->submitted_at->format('d M Y H:i') }}</p>
            @endif
        </div>
        @endif

        <!-- Form Upload -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Upload Berkas Penilaian</h3>
            <form wire:submit="save">
                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">File Penilaian <span class="text-red-500">*</span></label>
                        <div class="border-2 border-dashed border-gray-300 rounded-2xl p-8 text-center hover:border-green-400 transition cursor-pointer"
                             x-data
                             x-on:click="$refs.fileInput.click()">
                            <input type="file" wire:model="filePenilaian" x-ref="fileInput" class="hidden" accept=".pdf,.jpg,.jpeg,.png">
                            @if($filePenilaian)
                            <div class="text-green-600">
                                <svg class="w-10 h-10 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <p class="font-medium">{{ $filePenilaian->getClientOriginalName() }}</p>
                                <p class="text-xs text-gray-500">{{ round($filePenilaian->getSize() / 1024, 1) }} KB</p>
                            </div>
                            @else
                            <svg class="w-10 h-10 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                            <p class="text-gray-500">Klik untuk upload berkas penilaian</p>
                            <p class="text-xs text-gray-400 mt-1">PDF, JPG, PNG (Maks. 5MB)</p>
                            @endif
                        </div>
                        @error('filePenilaian') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        <div wire:loading wire:target="filePenilaian" class="text-sm text-gray-500 mt-2">Mengunggah...</div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Catatan untuk Panitia (Opsional)</label>
                        <textarea wire:model="catatan" rows="3" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 text-sm" placeholder="Catatan tambahan untuk panitia administrasi..."></textarea>
                    </div>
                </div>

                <div class="mt-6 flex justify-between items-center pt-4 border-t border-gray-100">
                    <a href="{{ route('dosen.nilai.index') }}" class="px-6 py-2.5 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 font-medium text-sm">Kembali</a>
                    <button type="submit" class="px-6 py-2.5 bg-green-700 text-white rounded-xl hover:bg-green-800 font-medium shadow-sm text-sm inline-flex items-center gap-2" wire:loading.attr="disabled" wire:target="save">
                        <svg wire:loading wire:target="save" class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/></svg>
                        <svg wire:loading.remove wire:target="save" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                        {{ $existingPenilaian ? 'Upload Ulang' : 'Kirim ke Panitia Administrasi' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

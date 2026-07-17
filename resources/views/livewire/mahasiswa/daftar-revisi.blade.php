<div>
    @section('title', 'Daftar Revisi')
    @section('page-title', 'Daftar Revisi Mahasiswa')

    <!-- Breadcrumb -->
    <nav class="mb-6 flex items-center text-sm text-gray-500">
        <span class="text-green-600 font-medium">Informasi</span>
        <svg class="w-4 h-4 mx-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        @if($selectedRevisiId)
            <a href="#" wire:click="closeDetail" class="text-green-600 hover:text-green-700 font-medium">Revisi</a>
            <svg class="w-4 h-4 mx-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            <span class="text-gray-700 font-medium">Detail</span>
        @else
            <span class="text-gray-700 font-medium">Revisi</span>
        @endif
    </nav>

    @unless($selectedRevisiId)

    <div class="max-w-6xl mx-auto">
        @if (session()->has('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-800 rounded-xl flex items-center">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
            </svg>
            {{ session('success') }}
        </div>
        @endif

        @if($revisis->isEmpty())
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center">
            <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Belum Ada Revisi</h3>
            <p class="text-gray-500">Anda belum memiliki catatan revisi dari dosen.</p>
        </div>
        @else
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-100">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Judul Ujian</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Dosen (Peran)</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Kategori</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($revisis as $revisi)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4">
                                <p class="text-sm font-medium text-gray-900 line-clamp-2" title="{{ $revisi->pendaftaran->judul_penelitian ?? '-' }}">
                                    {{ Str::limit($revisi->pendaftaran->judul_penelitian ?? '-', 50) }}
                                </p>
                                <p class="text-xs text-gray-500 mt-1">{{ ucwords(str_replace('_', ' ', $revisi->pendaftaran->jenis_ujian ?? '-')) }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm font-medium text-gray-900">{{ $revisi->dosen->name ?? '-' }}</p>
                                <p class="text-xs text-gray-500 uppercase">{{ str_replace('_', ' ', $revisi->peran_pemberi ?? '-') }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 bg-{{ $revisi->kategori_color ?? 'green' }}-100 text-{{ $revisi->kategori_color ?? 'green' }}-800 rounded-full text-xs font-medium">
                                    {{ $revisi->kategori_label ?? 'Revisi' }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @if($revisi->status == 'pending')
                                    <span class="px-2.5 py-1 bg-gray-100 text-gray-800 rounded-full text-xs font-medium">Butuh Revisi</span>
                                @elseif($revisi->status == 'diperiksa')
                                    <span class="px-2.5 py-1 bg-green-100 text-green-800 rounded-full text-xs font-medium">Diperiksa</span>
                                @elseif(in_array($revisi->status, ['disetujui', 'selesai']))
                                    <span class="px-2.5 py-1 bg-green-100 text-green-800 rounded-full text-xs font-medium">Disetujui</span>
                                @else
                                    <span class="px-2.5 py-1 bg-amber-100 text-amber-800 rounded-full text-xs font-medium">{{ ucfirst($revisi->status) }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <button wire:click="selectRevisi({{ $revisi->id }})" class="text-green-600 hover:text-green-800 font-medium text-sm flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    Detail
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif
    </div>

    @endunless

    {{-- ============= INLINE DETAIL CARD ============= --}}
    @if($selectedRevisiId)
        @php
            $revisi = $revisis->firstWhere('id', $selectedRevisiId);
        @endphp
        @if($revisi)
        <div class="bg-white rounded-2xl shadow-sm border border-green-200 overflow-hidden">
            <div class="bg-gradient-to-r from-green-600 to-green-800 px-6 py-4 flex justify-between items-center">
                <h3 class="text-lg leading-6 font-semibold text-white">
                    Detail Revisi
                </h3>
                <button wire:click="closeDetail" class="text-white hover:text-gray-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

                    <div class="px-6 py-5 max-h-[80vh] overflow-y-auto">
                        <div class="mb-5 bg-gray-50 p-4 rounded-xl border border-gray-100">
                            <div class="flex justify-between items-start mb-3">
                                <div class="flex-1">
                                    <p class="text-xs text-gray-500 font-medium mb-1">Judul Penelitian</p>
                                    <p class="text-sm font-semibold text-gray-900">{{ $revisi->pendaftaran->judul_penelitian ?? '-' }}</p>
                                    <p class="text-xs text-gray-500 mt-1">{{ ucwords(str_replace('_', ' ', $revisi->pendaftaran->jenis_ujian ?? '-')) }}</p>
                                </div>
                                <div class="text-right">
                                    <span class="px-2 py-1 bg-{{ $revisi->kategori_color ?? 'green' }}-100 text-{{ $revisi->kategori_color ?? 'green' }}-800 rounded-full text-xs font-medium">
                                        {{ $revisi->kategori_label ?? 'Revisi' }}
                                    </span>
                                </div>
                            </div>
                            <div class="flex items-center gap-2 pt-3 border-t border-gray-200">
                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                <p class="text-sm text-gray-700">{{ $revisi->dosen->name ?? '-' }}</p>
                                <span class="px-2 py-0.5 bg-gray-200 text-gray-700 rounded text-xs">{{ ucwords(str_replace('_', ' ', $revisi->peran_pemberi ?? '-')) }}</span>
                            </div>
                        </div>

                        <div class="mb-5 bg-amber-50 p-4 rounded-xl border border-amber-100">
                            <p class="text-xs text-amber-600 font-medium mb-1">Catatan Revisi dari Dosen</p>
                            <p class="text-gray-800 text-sm whitespace-pre-line">{{ $revisi->isi_revisi }}</p>
                        </div>

                        @if($revisi->deadline)
                        <div class="mb-5 bg-red-50 p-4 rounded-xl border border-red-100">
                            <p class="text-xs text-red-600 font-medium mb-1">Deadline</p>
                            <p class="text-gray-800 text-sm">{{ \Carbon\Carbon::parse($revisi->deadline)->format('d M Y') }}</p>
                        </div>
                        @endif

                        @if($revisi->file_revisi_mahasiswa)
                        <div class="mb-5 bg-green-50 p-4 rounded-xl border border-green-100">
                            <p class="text-xs text-green-600 font-medium mb-2">File Revisi yang Diunggah</p>
                            <div class="flex items-center justify-between">
                                <a href="{{ asset('storage/' . $revisi->file_revisi_mahasiswa) }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 text-sm">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                    Lihat File
                                </a>
                                <p class="text-xs text-gray-500">Diunggah: {{ $revisi->uploaded_at ? \Carbon\Carbon::parse($revisi->uploaded_at)->format('d M Y, H:i') : '-' }}</p>
                            </div>
                        </div>
                        @endif

                        @if($revisi->catatan_mahasiswa)
                        <div class="mb-5 bg-green-50 p-4 rounded-xl border border-green-100">
                            <p class="text-xs text-green-600 font-medium mb-1">Catatan Anda</p>
                            <p class="text-gray-800 text-sm whitespace-pre-line">{{ $revisi->catatan_mahasiswa }}</p>
                        </div>
                        @endif

                        @if($revisi->catatan_dosen)
                        <div class="mb-5 bg-green-50 p-4 rounded-xl border border-green-100">
                            <p class="text-xs text-green-600 font-medium mb-1">Catatan Review dari Dosen</p>
                            <p class="text-gray-800 text-sm whitespace-pre-line">{{ $revisi->catatan_dosen }}</p>
                        </div>
                        @endif

                        @if(!in_array($revisi->status, ['disetujui', 'selesai']))
                        <div class="mt-6 border-t border-gray-200 pt-5">
                            <h4 class="text-sm font-semibold text-gray-900 mb-4">
                                {{ $revisi->status == 'pending' ? 'Unggah File Revisi' : 'Unggah Ulang File Revisi' }}
                            </h4>

                            <form wire:submit="uploadRevisi">
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Pilih File (PDF/Word, Max 10MB)</label>
                                    <input type="file" wire:model="file_revisi" class="block w-full text-sm text-gray-500 border border-gray-300 rounded-xl cursor-pointer overflow-hidden bg-white focus:outline-none focus:ring-2 focus:ring-green-500 file:mr-4 file:py-3 file:px-5 file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100 file:cursor-pointer transition">
                                    @error('file_revisi') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                </div>

                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Catatan Tambahan (Opsional)</label>
                                    <textarea wire:model="catatan_mahasiswa" rows="3" class="w-full border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 p-3 text-sm" placeholder="Tambahkan catatan untuk dosen..."></textarea>
                                </div>

                                <div class="flex justify-end gap-3">
                                    <button type="button" wire:click="closeDetail" class="px-5 py-2 bg-gray-200 text-gray-700 rounded-xl hover:bg-gray-300 transition text-sm font-medium">
                                        Batal
                                    </button>
                                    <button type="submit" class="px-5 py-2 bg-green-600 text-white rounded-xl hover:bg-green-700 transition shadow-sm font-medium text-sm flex items-center gap-2">
                                        <svg wire:loading wire:target="uploadRevisi" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                        Unggah Revisi
                                    </button>
                                </div>
                            </form>
                        </div>
                        @endif
                    </div>
        </div>
        @endif
    @endif
</div>

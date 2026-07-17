<div>
    @section('title', 'Kelola Nilai Berkas')
    @section('page-title', 'Kelola Nilai Berkas Dosen')

    @if (session()->has('success'))
    <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-800 rounded-xl flex items-center" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)">
        <svg class="w-5 h-5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
        {{ session('success') }}
    </div>
    @endif

    <div class="bg-gradient-to-r from-gray-700 to-gray-900 rounded-2xl p-6 mb-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold">Nilai Berkas Dosen</h2>
                <p class="text-gray-200 mt-1">{{ Auth::user()->jurusan?->nama_jurusan }}</p>
                <p class="text-gray-300 text-sm mt-2">Kelola berkas penilaian yang diupload dosen untuk diinput ke sistem.</p>
            </div>
            <div class="hidden md:block">
                <svg class="w-16 h-16 text-gray-300 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- Breadcrumb -->
    <nav class="mb-6 text-sm text-gray-500">
        <ol class="flex items-center space-x-2">
            <li><a href="{{ route('panitia.administrasi.index') }}" class="hover:text-green-600 transition">Administrasi</a></li>
            <li><span class="mx-1">/</span></li>
            @if($showForm)
            <li><a href="#" wire:click.prevent="closeForm" class="hover:text-green-600 transition">Kelola Nilai</a></li>
            <li><span class="mx-1">/</span></li>
            <li class="text-green-700 font-semibold">Input Nilai</li>
            @elseif($showDetail)
            <li><a href="#" wire:click.prevent="closeDetail" class="hover:text-green-600 transition">Kelola Nilai</a></li>
            <li><span class="mx-1">/</span></li>
            <li class="text-green-700 font-semibold">Detail</li>
            @else
            <li class="text-gray-800 font-semibold">Kelola Nilai</li>
            @endif
        </ol>
    </nav>

    @if(!$showForm && !$showDetail)
    <!-- Filter -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Cari Mahasiswa / Dosen</label>
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Nama atau NIM..." class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-gray-500 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select wire:model.live="statusFilter" class="w-full px-4 py-2.5 pr-10 border border-gray-300 rounded-xl appearance-none cursor-pointer bg-white text-sm">
                    <option value="">Semua Status</option>
                    <option value="selesai">Selesai (Belum Diinput)</option>
                    <option value="diverifikasi">Diverifikasi (Sudah Diinput)</option>
                    <option value="draft">Draft</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Tabel -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-4 border-b bg-gray-50">
            <p class="text-sm text-gray-600">Daftar berkas penilaian yang diupload dosen via metode upload berkas.</p>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Mahasiswa</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Dosen</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Jenis Ujian</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Peran</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Nilai Akhir</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse($penilaians as $penilaian)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <p class="text-sm font-medium text-gray-900">{{ $penilaian->pendaftaran->mahasiswa->name }}</p>
                            <p class="text-xs text-gray-500">{{ $penilaian->pendaftaran->mahasiswa->nim }}</p>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-700">{{ $penilaian->dosen->name }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-0.5 bg-gray-100 rounded-full text-xs">{{ ucwords(str_replace('_', ' ', $penilaian->pendaftaran->jenis_ujian)) }}</span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ ucwords(str_replace('_', ' ', $penilaian->peran_pemberi)) }}</td>
                        <td class="px-6 py-4 text-center">
                            <span class="px-2 py-0.5 bg-{{ $penilaian->statusColor }}-100 text-{{ $penilaian->statusColor }}-800 rounded-full text-xs capitalize">
                                {{ $penilaian->status === 'diverifikasi' ? 'Sudah Diinput' : 'Belum Diinput' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm font-bold text-gray-800">
                            {{ $penilaian->nilai_akhir ? $penilaian->nilai_akhir . ' (' . $penilaian->nilai_huruf . ')' : '-' }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <button wire:click="openForm({{ $penilaian->id }})" class="px-3 py-1.5 bg-green-50 text-green-700 rounded-lg hover:bg-green-100 text-xs font-medium inline-flex items-center gap-1" title="Input Nilai ke Sistem">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    {{ $penilaian->nilai_akhir ? 'Edit Nilai' : 'Input Nilai' }}
                                </button>

                                @if($penilaian->file_penilaian)
                                <a href="{{ asset('storage/' . $penilaian->file_penilaian) }}" target="_blank" class="px-3 py-1.5 bg-green-50 text-green-700 rounded-lg hover:bg-green-100 text-xs font-medium inline-flex items-center gap-1" title="Download Berkas">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                    Berkas
                                </a>
                                @endif

                                <button wire:click="openDetail({{ $penilaian->id }})" class="px-3 py-1.5 bg-gray-50 text-gray-700 rounded-lg hover:bg-gray-100 text-xs font-medium inline-flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    Detail
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                            <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            Belum ada berkas nilai yang diupload dosen.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($penilaians->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $penilaians->links() }}
        </div>
        @endif
    </div>
    @endif

    <!-- INLINE FORM: Input Nilai -->
    @if($showForm && $penilaianToInput)
    <div class="bg-white rounded-2xl shadow-sm border border-green-200 overflow-hidden">
        <div class="bg-gradient-to-r from-green-700 to-green-800 px-6 py-4 flex justify-between items-center text-white">
            <div>
                <h3 class="text-lg font-semibold">Input Nilai Berdasarkan Berkas Dosen</h3>
                <p class="text-xs text-green-200">Mahasiswa: {{ $penilaianToInput->pendaftaran->mahasiswa->name }} | Dosen: {{ $penilaianToInput->dosen->name }}</p>
            </div>
            <button wire:click="closeForm" class="text-white hover:text-gray-200">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <div class="p-6 space-y-6">
            <!-- Banner Berkas Dosen -->
            <div class="bg-green-50 border border-green-200 rounded-xl p-4 flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <svg class="w-8 h-8 text-green-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <div>
                        <p class="text-sm font-bold text-green-900">Dokumen Penilaian Dosen</p>
                        <p class="text-xs text-green-700">Silakan buka atau unduh dokumen untuk melihat rincian nilai yang diberikan dosen.</p>
                    </div>
                </div>
                @if($penilaianToInput->file_penilaian)
                <a href="{{ asset('storage/' . $penilaianToInput->file_penilaian) }}" target="_blank" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 text-xs font-semibold shadow flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                    Buka Berkas (Tab Baru)
                </a>
                @endif
            </div>

            <!-- Form Input 7 Komponen -->
            <form wire:submit="simpanNilai">
                <div class="space-y-4">
                    <h4 class="font-semibold text-gray-800 text-md border-b pb-2">Input Komponen Nilai (Skala 0-100)</h4>
                    @php
                        $komponen = [
                            'presentasi' => ['label' => 'Presentasi Karya Ilmiah', 'bobot' => '10%'],
                            'penguasaan' => ['label' => 'Penguasaan Materi', 'bobot' => '15%'],
                            'menjawab' => ['label' => 'Cara Menjawab', 'bobot' => '10%'],
                            'deskripsi' => ['label' => 'Daya Deskripsi', 'bobot' => '10%'],
                            'analisis' => ['label' => 'Daya Analisis', 'bobot' => '20%'],
                            'menyimpulkan' => ['label' => 'Daya Menyimpulkan', 'bobot' => '15%'],
                            'implikasi' => ['label' => 'Daya Implikasi', 'bobot' => '20%'],
                        ];
                    @endphp

                    @foreach($komponen as $key => $k)
                    <div class="flex items-center gap-4 p-3 bg-gray-50 rounded-xl border border-gray-100">
                        <div class="flex-1">
                            <label class="block text-sm font-medium text-gray-700">
                                {{ $k['label'] }} <span class="text-xs text-gray-400">(Bobot {{ $k['bobot'] }})</span>
                            </label>
                            <div class="flex items-center gap-3 mt-1">
                                <input type="range" wire:model.live="{{ $key }}" min="0" max="100" class="flex-1 h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-green-600">
                                <input type="number" wire:model.live="{{ $key }}" min="0" max="100" step="0.01" class="w-24 px-3 py-1.5 border border-gray-300 rounded-lg text-center text-sm font-bold focus:ring-2 focus:ring-green-500">
                                <span class="text-xs text-gray-500 w-12 text-right font-semibold">{{ round($$key * \App\Models\Penilaian::BOBOT[$key], 2) }}</span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Preview Hasil -->
                @if($showPreview)
                <div class="mt-6 bg-gradient-to-r from-green-50 to-green-50 rounded-2xl p-5 border border-green-100">
                    <h4 class="text-sm font-bold text-green-900 mb-3">Hasil Kalkulasi Otomatis:</h4>
                    <div class="grid grid-cols-3 gap-4 text-center">
                        <div class="bg-white p-3 rounded-xl shadow-sm">
                            <p class="text-xs text-gray-500 font-medium">Nilai Akhir (NA)</p>
                            <p class="text-2xl font-black text-green-700">{{ $nilaiAkhir }}</p>
                        </div>
                        <div class="bg-white p-3 rounded-xl shadow-sm">
                            <p class="text-xs text-gray-500 font-medium">Nilai Huruf</p>
                            <p class="text-2xl font-black {{ $nilaiHuruf === 'A' ? 'text-green-600' : ($nilaiHuruf === 'B' ? 'text-green-600' : 'text-amber-600') }}">{{ $nilaiHuruf }}</p>
                        </div>
                        <div class="bg-white p-3 rounded-xl shadow-sm">
                            <p class="text-xs text-gray-500 font-medium">Predikat</p>
                            <p class="text-lg font-bold text-gray-700 mt-1">{{ $predikat }}</p>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Catatan -->
                <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Catatan Panitia / Dosen (Opsional)</label>
                    <textarea wire:model="catatanPanitia" rows="2" class="w-full px-4 py-2 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-green-500" placeholder="Tambahkan catatan jika ada..."></textarea>
                </div>

                <div class="mt-6 flex justify-end gap-3 pt-4 border-t">
                    <button type="button" wire:click="closeForm" class="px-5 py-2.5 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 font-medium text-sm">Batal</button>
                    <button type="submit" class="px-6 py-2.5 bg-green-600 text-white rounded-xl hover:bg-green-700 font-medium text-sm shadow-sm inline-flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Simpan & Verifikasi Nilai
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif

    <!-- INLINE DETAIL: Detail Berkas Penilaian -->
    @if($showDetail && $selectedPenilaian)
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="bg-gradient-to-r from-gray-700 to-gray-900 px-6 py-4 flex justify-between items-center">
            <h3 class="text-lg font-semibold text-white">Detail Berkas Penilaian</h3>
            <button wire:click="closeDetail" class="text-white hover:text-gray-200">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <div class="px-6 py-5 space-y-4">
            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <p class="text-xs text-gray-500 font-medium">Mahasiswa</p>
                    <p class="text-sm font-semibold">{{ $selectedPenilaian->pendaftaran->mahasiswa->name }}</p>
                    <p class="text-xs text-gray-500">{{ $selectedPenilaian->pendaftaran->mahasiswa->nim }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 font-medium">Dosen Penguji</p>
                    <p class="text-sm font-semibold">{{ $selectedPenilaian->dosen->name }}</p>
                    <p class="text-xs text-gray-500">{{ ucwords(str_replace('_', ' ', $selectedPenilaian->peran_pemberi)) }}</p>
                </div>
                <div class="md:col-span-2">
                    <p class="text-xs text-gray-500 font-medium">Judul Penelitian</p>
                    <p class="text-sm">{{ $selectedPenilaian->pendaftaran->judul_penelitian }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 font-medium">Jenis Ujian</p>
                    <p class="text-sm">{{ ucwords(str_replace('_', ' ', $selectedPenilaian->pendaftaran->jenis_ujian)) }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 font-medium">Status Penilaian</p>
                    <p class="text-sm font-bold capitalize text-{{ $selectedPenilaian->statusColor }}-600">{{ $selectedPenilaian->status === 'diverifikasi' ? 'Sudah Diinput ke Sistem' : 'Belum Diinput' }}</p>
                </div>
            </div>

            @if($selectedPenilaian->catatan)
            <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                <p class="text-xs text-gray-500 font-medium mb-1">Catatan dari Dosen</p>
                <p class="text-sm text-gray-800 whitespace-pre-line">{{ $selectedPenilaian->catatan }}</p>
            </div>
            @endif

            @if($selectedPenilaian->file_penilaian)
            <div class="flex gap-3">
                <a href="{{ asset('storage/' . $selectedPenilaian->file_penilaian) }}" target="_blank" class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-green-600 text-white rounded-xl hover:bg-green-700 text-sm font-medium">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    Lihat Berkas
                </a>
                <a href="{{ asset('storage/' . $selectedPenilaian->file_penilaian) }}" download class="inline-flex items-center justify-center gap-2 px-4 py-2.5 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 text-sm font-medium">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    Download
                </a>
            </div>
            @endif

            <div class="pt-4 border-t">
                <button wire:click="closeDetail" class="px-5 py-2.5 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 font-medium text-sm">Kembali ke Daftar</button>
            </div>
        </div>
    </div>
    @endif
</div>

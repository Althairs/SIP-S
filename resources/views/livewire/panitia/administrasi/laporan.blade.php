<div>
    @section('title', 'Laporan')
    @section('page-title', 'Unduh Laporan')

    <div class="bg-gradient-to-r from-slate-700 to-slate-900 rounded-2xl p-6 mb-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold">Laporan Administrasi</h2>
                <p class="text-slate-200 mt-1">{{ Auth::user()->jurusan?->nama_jurusan }}</p>
                <p class="text-slate-300 text-sm mt-2">Unduh laporan PDF untuk arsip dan rekap administrasi ujian.</p>
            </div>
            <div class="hidden md:block">
                <svg class="w-16 h-16 text-slate-300 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Filter Laporan</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Prodi</label>
                <select wire:model.live="prodiFilter" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl">
                    <option value="">Semua Prodi</option>
                    @foreach($prodis as $prodi)
                        <option value="{{ $prodi->id }}">{{ $prodi->nama_prodi }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Ujian</label>
                <select wire:model.live="jenisUjianFilter" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl">
                    <option value="">Semua Jenis</option>
                    <option value="seminar_proposal">Seminar Proposal</option>
                    <option value="seminar_hasil">Seminar Hasil</option>
                    <option value="sidang_skripsi">Sidang Skripsi</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Bulan</label>
                <select wire:model.live="bulanFilter" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl">
                    <option value="">Semua Bulan</option>
                    @for($m = 1; $m <= 12; $m++)
                        <option value="{{ $m }}">{{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}</option>
                    @endfor
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tahun</label>
                <select wire:model.live="tahunFilter" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl">
                    <option value="">Semua Tahun</option>
                    @for($y = now()->year; $y >= now()->year - 5; $y--)
                        <option value="{{ $y }}">{{ $y }}</option>
                    @endfor
                </select>
            </div>
        </div>
        <p class="text-xs text-gray-500 mt-3">Filter bulan/tahun berlaku untuk laporan berbasis pendaftaran. Kosongkan untuk menampilkan semua data.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @foreach($laporans as $laporan)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex flex-col">
            <div class="flex-1">
                <h3 class="text-lg font-semibold text-gray-900">{{ $laporan['judul'] }}</h3>
                <p class="text-sm text-gray-500 mt-2">{{ $laporan['deskripsi'] }}</p>
            </div>
            <div class="mt-6">
                <a href="{{ $this->downloadUrl($laporan['jenis']) }}"
                   target="_blank"
                   class="inline-flex items-center gap-2 px-5 py-2.5 bg-slate-800 text-white rounded-xl hover:bg-slate-900 font-medium text-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Download PDF
                </a>
            </div>
        </div>
        @endforeach
    </div>
</div>

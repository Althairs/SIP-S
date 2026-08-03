<div>
    @section('title', $title)
    @section('page-title', $title)

    @if (session()->has('success'))
    <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-800 rounded-xl flex items-center" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)">
        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
        {{ session('success') }}
    </div>
    @endif

    <!-- Stats -->
    <div class="grid grid-cols-3 gap-6 mb-6">
        @php
            $scope = \App\Services\PermissionService::jurusanScope();
            $countMenunggu = \App\Models\Pendaftaran::where($scope)
                ->where('jenis_ujian', $jenisUjian)
                ->where('status', 'disetujui_sekjur')->count();
            $countDisetujui = \App\Models\Pendaftaran::where($scope)
                ->where('jenis_ujian', $jenisUjian)
                ->where('status', 'disetujui_kajur')->count();
            $countDitolak = \App\Models\Pendaftaran::where($scope)
                ->where('jenis_ujian', $jenisUjian)
                ->where('status', 'ditolak_kajur')->count();
        @endphp
        <div class="bg-white rounded-2xl p-4 shadow-sm border border-yellow-100 text-center">
            <p class="text-sm text-gray-500">Menunggu Verifikasi</p>
            <p class="text-3xl font-bold text-yellow-700">{{ $countMenunggu }}</p>
        </div>
        <div class="bg-white rounded-2xl p-4 shadow-sm border border-green-100 text-center">
            <p class="text-sm text-gray-500">Disetujui</p>
            <p class="text-3xl font-bold text-green-700">{{ $countDisetujui }}</p>
        </div>
        <div class="bg-white rounded-2xl p-4 shadow-sm border border-red-100 text-center">
            <p class="text-sm text-gray-500">Ditolak</p>
            <p class="text-3xl font-bold text-red-700">{{ $countDitolak }}</p>
        </div>
    </div>

    @unless($showDetail && $selectedPendaftaran)

    <!-- Filter -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 mb-6">
        <div class="flex flex-col md:flex-row gap-3">
            <div class="relative flex-1">
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari..."
                       class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
            <select wire:model.live="statusFilter" class="px-4 py-2.5 border border-gray-300 rounded-xl">
                <option value="">Semua Status</option>
                <option value="disetujui_sekjur">Menunggu Verifikasi</option>
                <option value="disetujui_kajur">Disetujui</option>
                <option value="ditolak_kajur">Ditolak</option>
            </select>
        </div>
    </div>

    <!-- List -->
    <div class="space-y-4">
        @forelse($pendaftarans as $p)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <span class="px-2 py-1 bg-{{ $p->statusColor }}-100 text-{{ $p->statusColor }}-800 rounded-full text-xs font-medium">{{ $p->statusLabel }}</span>

                    <h3 class="font-semibold text-gray-900 mt-2">{{ $p->judul_penelitian }}</h3>

                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mt-3 text-sm">
                        <div>
                            <p class="text-xs text-gray-500">Mahasiswa</p>
                            <p class="font-medium">{{ $p->mahasiswa->name }}</p>
                            <p class="text-xs text-gray-400">{{ $p->mahasiswa->nim }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Pembimbing</p>
                            <p class="font-medium text-xs">{{ $p->pembimbing1?->dosen?->name ?? '-' }}</p>
                            <p class="text-xs text-gray-400">{{ $p->pembimbing2?->dosen?->name ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Penguji</p>
                            @foreach($p->pengujis as $penguji)
                            <p class="text-xs">{{ str_replace('_', ' ', $penguji->peran) }}: {{ $penguji->dosen->name ?? '-' }}</p>
                            @endforeach
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Tanggal Daftar</p>
                            <p class="text-xs">{{ $p->created_at->format('d M Y') }}</p>
                        </div>
                    </div>
                </div>
                <div>
                    <button wire:click="showDetail({{ $p->id }})" class="px-3 py-1.5 bg-emerald-50 text-emerald-700 hover:bg-emerald-100 rounded-xl text-xs font-medium">Detail</button>
                </div>
            </div>

            @if($p->status === 'disetujui_sekjur')
            <div class="mt-4 flex items-center gap-3 pt-4 border-t">
                <button wire:click="approvePendaftaran({{ $p->id }})" wire:confirm="Setujui dan teruskan ke Panitia Penjadwalan?" class="px-4 py-2 bg-emerald-700 text-white rounded-xl hover:bg-emerald-800 text-sm font-medium">Setujui</button>
                <button wire:click="revisiPendaftaran({{ $p->id }})" wire:confirm="Kembalikan untuk revisi?" class="px-4 py-2 bg-amber-50 text-amber-700 border border-amber-200 rounded-xl hover:bg-amber-100 text-sm font-medium">Revisi</button>
                <button wire:click="rejectPendaftaran({{ $p->id }})" wire:confirm="Tolak pendaftaran ini?" class="px-4 py-2 bg-red-50 text-red-700 border border-red-200 rounded-xl hover:bg-red-100 text-sm font-medium">Tolak</button>
            </div>
            @endif
        </div>
        @empty
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center text-gray-500">Tidak ada data</div>
        @endforelse
    </div>

    <div class="mt-4">{{ $pendaftarans->links() }}</div>

    @endunless

    {{-- ============= INLINE DETAIL CARD ============= --}}
    @if($showDetail && $selectedPendaftaran)
        <div class="bg-white rounded-2xl shadow-sm border border-emerald-200 overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r from-emerald-600 to-emerald-800 p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="px-3 py-1 bg-white/20 rounded-full text-xs">{{ ucwords(str_replace('_', ' ', $selectedPendaftaran->jenis_ujian)) }}</span>
                        <h2 class="text-xl font-bold mt-2">Detail Pendaftaran</h2>
                    </div>
                    <button wire:click="closeDetail" class="text-white/80 hover:text-white bg-white/10 rounded-full p-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            <div class="p-6 space-y-6">
                <!-- Status -->
                <div class="flex items-center gap-3">
                    <span class="px-3 py-1.5 bg-{{ $selectedPendaftaran->statusColor }}-100 text-{{ $selectedPendaftaran->statusColor }}-800 rounded-full text-sm font-medium">{{ $selectedPendaftaran->statusLabel }}</span>
                </div>

                <!-- Judul & Abstrak -->
                <div class="bg-gray-50 rounded-2xl p-5">
                    <h3 class="text-lg font-bold text-gray-900 mb-2">{{ $selectedPendaftaran->judul_penelitian }}</h3>
                    @if($selectedPendaftaran->abstrak)
                        <div class="mt-3">
                            <p class="text-sm font-medium text-gray-700 mb-1">Abstrak</p>
                            <p class="text-sm text-gray-600 leading-relaxed">{{ $selectedPendaftaran->abstrak }}</p>
                        </div>
                    @endif
                </div>

                <!-- Info Grid -->
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                    <div class="border rounded-xl p-3">
                        <p class="text-xs text-gray-500">Nama</p>
                        <p class="font-bold">{{ $selectedPendaftaran->mahasiswa->name }}</p>
                    </div>
                    <div class="border rounded-xl p-3">
                        <p class="text-xs text-gray-500">NIM</p>
                        <p class="font-bold">{{ $selectedPendaftaran->mahasiswa->nim }}</p>
                    </div>
                    <div class="border rounded-xl p-3">
                        <p class="text-xs text-gray-500">Email</p>
                        <p class="font-bold text-sm">{{ $selectedPendaftaran->mahasiswa->email }}</p>
                    </div>
                    <div class="border rounded-xl p-3">
                        <p class="text-xs text-gray-500">Jurusan</p>
                        <p class="font-bold">{{ $selectedPendaftaran->jurusan?->nama_jurusan }}</p>
                    </div>
                    <div class="border rounded-xl p-3">
                        <p class="text-xs text-gray-500">Prodi</p>
                        <p class="font-bold">{{ $selectedPendaftaran->prodi?->nama_prodi }}</p>
                    </div>
                    <div class="border rounded-xl p-3">
                        <p class="text-xs text-gray-500">HP</p>
                        <p class="font-bold">{{ $selectedPendaftaran->mahasiswa->nomor_hp ?? '-' }}</p>
                    </div>
                </div>

                <!-- Pembimbing -->
                <div>
                    <p class="text-sm font-semibold text-gray-700 mb-3">Dosen Pembimbing</p>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-emerald-50 rounded-xl p-4 border border-emerald-200">
                            <p class="text-xs text-emerald-600 mb-1">Pembimbing 1</p>
                            @if($selectedPendaftaran->pembimbing1?->dosen)
                                <p class="font-bold">{{ $selectedPendaftaran->pembimbing1->dosen->name }}</p>
                                <p class="text-xs text-gray-500">NIP: {{ $selectedPendaftaran->pembimbing1->dosen->nip }}</p>
                            @else
                                <p class="text-gray-400">-</p>
                            @endif
                        </div>
                        <div class="bg-emerald-50 rounded-xl p-4 border border-emerald-200">
                            <p class="text-xs text-emerald-600 mb-1">Pembimbing 2</p>
                            @if($selectedPendaftaran->pembimbing2?->dosen)
                                <p class="font-bold">{{ $selectedPendaftaran->pembimbing2->dosen->name }}</p>
                                <p class="text-xs text-gray-500">NIP: {{ $selectedPendaftaran->pembimbing2->dosen->nip }}</p>
                            @else
                                <p class="text-gray-400">-</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Bidang Keahlian -->
                @if($selectedPendaftaran->bidangKeahlians->count() > 0)
                    <div>
                        <p class="text-sm font-semibold text-gray-700 mb-2">Bidang Keahlian</p>
                        <div class="flex flex-wrap gap-2">
                            @foreach($selectedPendaftaran->bidangKeahlians as $bk)
                                <span class="px-3 py-1.5 bg-emerald-100 text-emerald-800 rounded-full text-sm font-medium">{{ $bk->nama_bidang }}</span>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Berkas -->
                <div>
                    <p class="text-sm font-semibold text-gray-700 mb-2">Berkas Pendaftaran</p>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                        @if($selectedPendaftaran->file_proposal)
                            <a href="{{ asset('storage/' . $selectedPendaftaran->file_proposal) }}" target="_blank"
                                class="flex items-center gap-2 p-3 bg-emerald-50 rounded-xl hover:bg-emerald-100 transition border border-emerald-200">
                                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                </svg>
                                <span class="text-sm font-medium text-emerald-700">File Proposal</span>
                            </a>
                        @endif
                        @if($selectedPendaftaran->file_skripsi)
                            <a href="{{ asset('storage/' . $selectedPendaftaran->file_skripsi) }}" target="_blank"
                                class="flex items-center gap-2 p-3 bg-emerald-50 rounded-xl hover:bg-emerald-100 transition border border-emerald-200">
                                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                </svg>
                                <span class="text-sm font-medium text-emerald-700">File Skripsi</span>
                            </a>
                        @endif
                        @if($selectedPendaftaran->file_persetujuan)
                            <a href="{{ asset('storage/' . $selectedPendaftaran->file_persetujuan) }}" target="_blank"
                                class="flex items-center gap-2 p-3 bg-emerald-50 rounded-xl hover:bg-emerald-100 transition border border-emerald-200">
                                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span class="text-sm font-medium text-emerald-700">Persetujuan</span>
                            </a>
                        @endif
                        @if($selectedPendaftaran->file_krs)
                            <a href="{{ asset('storage/' . $selectedPendaftaran->file_krs) }}" target="_blank"
                                class="flex items-center gap-2 p-3 bg-amber-50 rounded-xl hover:bg-amber-100 transition border border-amber-200">
                                <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2" />
                                </svg>
                                <span class="text-sm font-medium text-amber-700">KRS</span>
                            </a>
                        @endif
                        @if($selectedPendaftaran->file_transkrip)
                            <a href="{{ asset('storage/' . $selectedPendaftaran->file_transkrip) }}" target="_blank"
                                class="flex items-center gap-2 p-3 bg-emerald-50 rounded-xl hover:bg-emerald-100 transition border border-emerald-200">
                                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2z" />
                                </svg>
                                <span class="text-sm font-medium text-emerald-700">Transkrip</span>
                            </a>
                        @endif
                        @if($selectedPendaftaran->file_bukti_bimbingan)
                            <a href="{{ asset('storage/' . $selectedPendaftaran->file_bukti_bimbingan) }}" target="_blank"
                                class="flex items-center gap-2 p-3 bg-emerald-50 rounded-xl hover:bg-emerald-100 transition border border-emerald-200">
                                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <span class="text-sm font-medium text-emerald-700">Bukti Bimbingan</span>
                            </a>
                        @endif
                    </div>
                </div>

                <!-- Actions -->
                @if($selectedPendaftaran->status === 'disetujui_sekjur')
                    <div class="flex justify-end gap-3 pt-4 border-t">
                        <button wire:click="rejectPendaftaran({{ $selectedPendaftaran->id }})" wire:confirm="Tolak pendaftaran ini?" class="px-6 py-2.5 bg-red-50 text-red-700 border border-red-200 rounded-xl hover:bg-red-100 font-medium">Tolak</button>
                        <button wire:click="revisiPendaftaran({{ $selectedPendaftaran->id }})" wire:confirm="Kembalikan untuk revisi?" class="px-6 py-2.5 bg-amber-50 text-amber-700 border border-amber-200 rounded-xl hover:bg-amber-100 font-medium">Revisi</button>
                        <button wire:click="approvePendaftaran({{ $selectedPendaftaran->id }})" wire:confirm="Setujui dan teruskan ke Panitia Penjadwalan?" class="px-6 py-2.5 bg-emerald-700 text-white rounded-xl hover:bg-emerald-800 font-medium">Setujui</button>
                    </div>
                @endif
            </div>
        </div>
    @endif
</div>
</div>

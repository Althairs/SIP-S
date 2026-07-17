<div>
    @section('title', 'Verifikasi Berkas')
    @section('page-title', 'Verifikasi Berkas Pendaftaran')

    @if (session()->has('success'))
        <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-800 rounded-xl flex items-center"
            x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd"
                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                    clip-rule="evenodd" />
            </svg>
            {{ session('success') }}
        </div>
    @endif

    <!-- Breadcrumb -->
    <nav class="mb-4 flex items-center text-sm text-gray-500">
        <a href="{{ route('panitia.verifikasi.berkas') }}" class="text-green-600 hover:text-green-700 font-medium">Verifikasi</a>
        <svg class="w-4 h-4 mx-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <a href="{{ route('panitia.verifikasi.berkas') }}" class="text-green-600 hover:text-green-700 font-medium">Berkas</a>
        @if($showDetail && $selectedPendaftaran)
            <svg class="w-4 h-4 mx-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            <span class="text-gray-700 font-medium">Detail — {{ $selectedPendaftaran->mahasiswa->name }}</span>
        @endif
    </nav>

    @unless($showDetail && $selectedPendaftaran)

    <!-- Filter -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 mb-6">
        <div class="flex flex-col md:flex-row gap-3">
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari mahasiswa atau judul..."
                class="flex-1 px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500">
            <select wire:model.live="statusFilter" class="px-4 py-2.5 pr-10 border border-gray-300 rounded-xl appearance-none cursor-pointer bg-white">
                <option value="">Semua Status</option>
                <option value="pending">Menunggu Verifikasi</option>
                <option value="disetujui_panitia">Disetujui (Lanjut ke Sekjur)</option>
                <option value="ditolak_panitia">Ditolak</option>
            </select>
        </div>
    </div>

    <!-- Info Jumlah per Status -->
    @php
        $jurusanId = auth()->user()->jurusan_id;
        $countPending = \App\Models\Pendaftaran::where('jurusan_id', $jurusanId)->where('status', 'pending')->count();
        $countDisetujui = \App\Models\Pendaftaran::where('jurusan_id', $jurusanId)->where('status', 'disetujui_panitia')->count();
        $countDitolak = \App\Models\Pendaftaran::where('jurusan_id', $jurusanId)->where('status', 'ditolak_panitia')->count();
    @endphp

    <div class="grid grid-cols-3 gap-4 mb-6">
        <div class="bg-amber-50 rounded-xl p-4 text-center border border-amber-200">
            <p class="text-2xl font-bold text-amber-700">{{ $countPending }}</p>
            <p class="text-xs text-amber-600">Menunggu</p>
        </div>
        <div class="bg-green-50 rounded-xl p-4 text-center border border-green-200">
            <p class="text-2xl font-bold text-green-700">{{ $countDisetujui }}</p>
            <p class="text-xs text-green-600">Disetujui</p>
        </div>
        <div class="bg-red-50 rounded-xl p-4 text-center border border-red-200">
            <p class="text-2xl font-bold text-red-700">{{ $countDitolak }}</p>
            <p class="text-xs text-red-600">Ditolak</p>
        </div>
    </div>

    <!-- List -->
    <div class="space-y-4">
        @forelse($pendaftarans as $p)
            <div
                class="bg-white rounded-2xl shadow-sm border {{ $p->status === 'pending' ? 'border-amber-200' : ($p->status === 'disetujui_panitia' ? 'border-green-200' : 'border-red-200') }} p-6">
                <div class="flex flex-col md:flex-row md:items-start justify-between gap-4">
                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-2 flex-wrap">
                            <span
                                class="px-2 py-1 bg-{{ $p->statusColor }}-100 text-{{ $p->statusColor }}-800 rounded-full text-xs font-medium">
                                {{ $p->statusLabel }}
                            </span>
                            <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">
                                {{ ucwords(str_replace('_', ' ', $p->jenis_ujian)) }}
                            </span>
                            <span class="text-xs text-gray-400">{{ $p->created_at->format('d M Y H:i') }}</span>
                        </div>

                        <h3 class="font-semibold text-gray-900">{{ $p->judul_penelitian }}</h3>

                        <div class="mt-3 grid grid-cols-2 md:grid-cols-4 gap-3 text-sm">
                            <div>
                                <p class="text-xs text-gray-500">Mahasiswa</p>
                                <p class="font-medium">{{ $p->mahasiswa->name }}</p>
                                <p class="text-xs text-gray-400">{{ $p->mahasiswa->nim }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Pembimbing</p>
                                <p class="font-medium text-sm">{{ $p->pembimbing1?->dosen?->name ?? '-' }}</p>
                                @if($p->pembimbing2?->dosen)
                                    <p class="text-xs text-gray-400">{{ $p->pembimbing2->dosen->name }}</p>
                                @endif
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Berkas</p>
                                <div class="flex flex-wrap gap-1 mt-0.5">
                                    @if($p->file_proposal)
                                        <span class="px-2 py-0.5 bg-green-100 text-green-700 rounded text-xs flex items-center">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                            Proposal
                                        </span>
                                    @endif
                                    @if($p->file_krs)
                                        <span class="px-2 py-0.5 bg-green-100 text-green-700 rounded text-xs flex items-center">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                                            KRS
                                        </span>
                                    @endif
                                    @if($p->file_transkrip)
                                        <span class="px-2 py-0.5 bg-green-100 text-green-700 rounded text-xs flex items-center">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                                            Transkrip
                                        </span>
                                    @endif
                                    @if(!$p->file_proposal && !$p->file_krs && !$p->file_transkrip)
                                        <span class="text-xs text-gray-400">-</span>
                                    @endif
                                </div>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Bidang Keahlian</p>
                                <div class="flex flex-wrap gap-1 mt-0.5">
                                    @forelse($p->bidangKeahlians as $bk)
                                        <span
                                            class="px-2 py-0.5 bg-green-100 text-green-800 rounded-full text-xs">{{ $bk->nama_bidang }}</span>
                                    @empty
                                        <span class="text-xs text-gray-400">-</span>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col gap-2 md:flex-shrink-0">
                        <button wire:click="showDetail({{ $p->id }})"
                            class="px-4 py-2 bg-green-50 text-green-700 border border-green-200 rounded-xl hover:bg-green-100 text-sm font-medium whitespace-nowrap">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            Detail
                        </button>

                        {{-- HANYA TAMPILKAN TOMBOL JIKA STATUS PENDING --}}
                        @if($p->status === 'pending')
                            <button wire:click="updateStatus({{ $p->id }}, 'disetujui_panitia')"
                                wire:confirm="Setujui dan teruskan ke Sekjur?"
                                class="px-4 py-2 bg-green-700 text-white rounded-xl hover:bg-green-800 text-sm font-medium whitespace-nowrap">
                                Setujui
                            </button>
                            <button wire:click="updateStatus({{ $p->id }}, 'ditolak_panitia')"
                                wire:confirm="Tolak pendaftaran ini?"
                                class="px-4 py-2 bg-red-50 text-red-700 border border-red-200 rounded-xl hover:bg-red-100 text-sm font-medium whitespace-nowrap">
                                Tolak
                            </button>
                        @endif

                        {{-- Info jika sudah disetujui --}}
                        @if($p->status === 'disetujui_panitia')
                            <span class="px-3 py-2 bg-green-50 text-green-700 rounded-xl text-xs text-center border border-green-200 inline-flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                Diteruskan ke Sekjur
                            </span>
                        @endif

                        {{-- Info jika ditolak --}}
                        @if($p->status === 'ditolak_panitia')
                            <span class="px-3 py-2 bg-red-50 text-red-700 rounded-xl text-xs text-center border border-red-200 inline-flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                Ditolak
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center">
                <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <p class="text-gray-500">
                    @if($statusFilter === 'pending')
                        Tidak ada pendaftaran yang menunggu verifikasi.
                    @elseif($statusFilter === 'disetujui_panitia')
                        Belum ada pendaftaran yang disetujui.
                    @elseif($statusFilter === 'ditolak_panitia')
                        Tidak ada pendaftaran yang ditolak.
                    @else
                        Tidak ada data pendaftaran.
                    @endif
                </p>
            </div>
        @endforelse
    </div>

    <div class="mt-4">{{ $pendaftarans->links() }}</div>

    @endunless

    {{-- ============= INLINE DETAIL CARD ============= --}}
    @if($showDetail && $selectedPendaftaran)
        <div class="bg-white rounded-2xl shadow-sm border border-green-200 overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r from-green-600 to-green-800 p-6 text-white">
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
                        <div class="bg-green-50 rounded-xl p-4 border border-green-200">
                            <p class="text-xs text-green-600 mb-1">Pembimbing 1</p>
                            @if($selectedPendaftaran->pembimbing1?->dosen)
                                <p class="font-bold">{{ $selectedPendaftaran->pembimbing1->dosen->name }}</p>
                                <p class="text-xs text-gray-500">NIP: {{ $selectedPendaftaran->pembimbing1->dosen->nip }}</p>
                            @else
                                <p class="text-gray-400">-</p>
                            @endif
                        </div>
                        <div class="bg-green-50 rounded-xl p-4 border border-green-200">
                            <p class="text-xs text-green-600 mb-1">Pembimbing 2</p>
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
                                <span class="px-3 py-1.5 bg-green-100 text-green-800 rounded-full text-sm font-medium">{{ $bk->nama_bidang }}</span>
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
                                class="flex items-center gap-2 p-3 bg-green-50 rounded-xl hover:bg-green-100 transition border border-green-200">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                </svg>
                                <span class="text-sm font-medium text-green-700">File Proposal</span>
                            </a>
                        @endif
                        @if($selectedPendaftaran->file_skripsi)
                            <a href="{{ asset('storage/' . $selectedPendaftaran->file_skripsi) }}" target="_blank"
                                class="flex items-center gap-2 p-3 bg-green-50 rounded-xl hover:bg-green-100 transition border border-green-200">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                </svg>
                                <span class="text-sm font-medium text-green-700">File Skripsi</span>
                            </a>
                        @endif
                        @if($selectedPendaftaran->file_persetujuan)
                            <a href="{{ asset('storage/' . $selectedPendaftaran->file_persetujuan) }}" target="_blank"
                                class="flex items-center gap-2 p-3 bg-green-50 rounded-xl hover:bg-green-100 transition border border-green-200">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span class="text-sm font-medium text-green-700">Persetujuan</span>
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
                                class="flex items-center gap-2 p-3 bg-green-50 rounded-xl hover:bg-green-100 transition border border-green-200">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2z" />
                                </svg>
                                <span class="text-sm font-medium text-green-700">Transkrip</span>
                            </a>
                        @endif
                        @if($selectedPendaftaran->file_bukti_bimbingan)
                            <a href="{{ asset('storage/' . $selectedPendaftaran->file_bukti_bimbingan) }}" target="_blank"
                                class="flex items-center gap-2 p-3 bg-green-50 rounded-xl hover:bg-green-100 transition border border-green-200">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <span class="text-sm font-medium text-green-700">Bukti Bimbingan</span>
                            </a>
                        @endif
                    </div>
                </div>

                <!-- Actions -->
                @if($selectedPendaftaran->status === 'pending')
                    <div class="flex justify-end gap-3 pt-4 border-t">
                        <button wire:click="updateStatus({{ $selectedPendaftaran->id }}, 'ditolak_panitia')"
                            wire:confirm="Tolak pendaftaran ini?"
                            class="px-6 py-2.5 bg-red-50 text-red-700 border border-red-200 rounded-xl hover:bg-red-100 font-medium">Tolak</button>
                        <button wire:click="updateStatus({{ $selectedPendaftaran->id }}, 'disetujui_panitia')"
                            wire:confirm="Setujui dan teruskan ke Sekjur?"
                            class="px-6 py-2.5 bg-green-700 text-white rounded-xl hover:bg-green-800 font-medium">Setujui & Lanjutkan</button>
                    </div>
                @endif
            </div>
        </div>
    @endif
</div>

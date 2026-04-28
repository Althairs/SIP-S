<div>
    @section('title', 'Penambahan Penguji')
    @section('page-title', 'Penambahan Penguji Ujian')

    @if (session()->has('success'))
    <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-800 rounded-xl flex items-center" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)">
        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
        {{ session('success') }}
    </div>
    @endif

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-orange-100">
            <p class="text-sm text-gray-500">Menunggu Penguji</p>
            <p class="text-3xl font-bold text-orange-700">{{ $totalMenunggu }}</p>
        </div>
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-green-100">
            <p class="text-sm text-gray-500">Sudah Diatur</p>
            <p class="text-3xl font-bold text-green-700">{{ $totalSudahDiatur }}</p>
        </div>
    </div>

    <!-- Filter -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 mb-6">
        <div class="flex flex-col md:flex-row gap-3">
            <div class="relative flex-1">
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari mahasiswa atau judul..."
                       class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
            <select wire:model.change="statusFilter" class="px-4 py-2.5 border border-gray-300 rounded-xl">
                <option value="">Semua</option>
                <option value="no_penguji">Belum Ada Penguji</option>
                <option value="has_penguji">Sudah Ada Penguji</option>
            </select>
        </div>
    </div>

    <!-- List -->
    <div class="space-y-4">
        @forelse($pendaftarans as $p)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex flex-col md:flex-row md:items-start justify-between gap-4">
                <div class="flex-1">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="px-2 py-1 bg-{{ $p->statusColor }}-100 text-{{ $p->statusColor }}-800 rounded-full text-xs font-medium">{{ $p->statusLabel }}</span>
                        <span class="px-2 py-1 bg-orange-100 text-orange-800 rounded-full text-xs">{{ ucwords(str_replace('_', ' ', $p->jenis_ujian)) }}</span>
                    </div>

                    <h3 class="font-semibold text-gray-900">{{ $p->judul_penelitian }}</h3>

                    <div class="mt-3 grid grid-cols-2 md:grid-cols-4 gap-3 text-sm">
                        <div>
                            <p class="text-gray-500 text-xs">Mahasiswa</p>
                            <p class="font-medium">{{ $p->mahasiswa->name }}</p>
                            <p class="text-xs text-gray-400">{{ $p->mahasiswa->nim }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500 text-xs">Pembimbing 1</p>
                            <p class="font-medium text-sm">{{ $p->pembimbing1?->dosen?->name ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500 text-xs">Pembimbing 2</p>
                            <p class="font-medium text-sm">{{ $p->pembimbing2?->dosen?->name ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500 text-xs">Tanggal Daftar</p>
                            <p class="font-medium text-sm">{{ $p->created_at->format('d M Y') }}</p>
                        </div>
                    </div>

                    <!-- Bidang Keahlian -->
                    @if($p->bidangKeahlians->count() > 0)
                    <div class="flex flex-wrap gap-1 mt-2">
                        @foreach($p->bidangKeahlians as $bk)
                        <span class="px-2 py-0.5 bg-teal-100 text-teal-800 rounded-full text-xs">{{ $bk->nama_bidang }}</span>
                        @endforeach
                    </div>
                    @endif

                    <!-- Penguji yang sudah ditentukan -->
                    @if($p->pengujis->count() > 0)
                    <div class="mt-3 p-3 bg-green-50 rounded-xl">
                        <p class="text-xs font-medium text-green-700 mb-2">Penguji:</p>
                        <div class="grid grid-cols-2 gap-2">
                            @foreach($p->pengujis as $penguji)
                            <div class="text-sm">
                                <span class="text-xs text-gray-500">{{ str_replace('_', ' ', $penguji->peran) }}:</span>
                                <span class="font-medium ml-1">{{ $penguji->dosen->name ?? '-' }}</span>
                                @if($penguji->is_overload)
                                <span class="ml-1 px-1.5 py-0.5 bg-red-100 text-red-700 rounded text-xs" title="Kuota overload">⚠️</span>
                                @endif
                                @if($penguji->kepakaran)
                                <span class="block text-xs text-purple-600 ml-4">{{ $penguji->kepakaran->nama_kepakaran }}</span>
                                @endif
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Actions -->
                <div class="flex flex-wrap gap-2 md:flex-shrink-0">
                    <a href="{{ route('sekjur.data-master.penguji.generate', $p->id) }}"
                       class="px-4 py-2 bg-orange-700 text-white rounded-xl hover:bg-orange-800 text-sm font-medium whitespace-nowrap">
                        {{ $p->pengujis->count() > 0 ? 'Edit Penguji' : 'Generate Penguji' }}
                    </a>
                </div>
            </div>
        </div>
        @empty
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center text-gray-500">
            <div class="w-20 h-20 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-10 h-10 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
            <p>Tidak ada pendaftaran yang perlu diatur pengujinya.</p>
        </div>
        @endforelse
    </div>

    <div class="mt-4">{{ $pendaftarans->links() }}</div>
</div>

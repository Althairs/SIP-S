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
            $jurusanId = auth()->user()->jurusan_id;
            $countMenunggu = \App\Models\Pendaftaran::where('jurusan_id', $jurusanId)
                ->where('jenis_ujian', $jenisUjian)
                ->where('status', 'disetujui_sekjur')->count();
            $countDisetujui = \App\Models\Pendaftaran::where('jurusan_id', $jurusanId)
                ->where('jenis_ujian', $jenisUjian)
                ->where('status', 'disetujui_kajur')->count();
            $countDitolak = \App\Models\Pendaftaran::where('jurusan_id', $jurusanId)
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

    <!-- Filter -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 mb-6">
        <div class="flex flex-col md:flex-row gap-3">
            <div class="relative flex-1">
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari..."
                       class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
            <select wire:model.change="statusFilter" class="px-4 py-2.5 border border-gray-300 rounded-xl">
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
</div>

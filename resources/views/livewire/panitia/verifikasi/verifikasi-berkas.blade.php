<div>
    @section('title', 'Verifikasi Berkas')
    @section('page-title', 'Verifikasi Berkas Pendaftaran')

    {{-- @if($p->status === 'pending')
        <div class="mt-4 flex items-center gap-3 pt-4 border-t">
            <button wire:click="updateStatus({{ $p->id }}, 'disetujui_panitia')"
                wire:confirm="Setujui pendaftaran ini dan teruskan ke Sekjur?"
                class="px-4 py-2 bg-green-700 text-white rounded-xl hover:bg-green-800 text-sm font-medium">
                Setujui & Lanjutkan ke Sekjur
            </button>
            <button wire:click="updateStatus({{ $p->id }}, 'ditolak_panitia')" wire:confirm="Tolak pendaftaran ini?"
                class="px-4 py-2 bg-red-50 text-red-700 border border-red-200 rounded-xl hover:bg-red-100 text-sm font-medium">
                Tolak
            </button>
        </div>
    @endif --}}

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 mb-6">
        <div class="flex flex-col md:flex-row gap-3">
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari mahasiswa atau judul..."
                class="flex-1 px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500">
            <select wire:model.change="statusFilter" class="px-4 py-2.5 border border-gray-300 rounded-xl">
                <option value="pending">Menunggu Verifikasi</option>
                <option value="disetujui_panitia">Disetujui</option>
                <option value="ditolak_panitia">Ditolak</option>
                <option value="">Semua</option>
            </select>
        </div>
    </div>

    <div class="space-y-4">
        @forelse($pendaftarans as $p)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-2">
                            <span
                                class="px-3 py-1 bg-{{ $p->statusColor }}-100 text-{{ $p->statusColor }}-800 rounded-full text-xs font-medium">{{ $p->statusLabel }}</span>
                            <span
                                class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs">{{ ucwords(str_replace('_', ' ', $p->jenis_ujian)) }}</span>
                        </div>

                        <h3 class="font-semibold text-gray-900 text-lg">{{ $p->judul_penelitian }}</h3>

                        <div class="mt-3 grid grid-cols-2 md:grid-cols-4 gap-3 text-sm">
                            <div>
                                <p class="text-gray-500">Mahasiswa</p>
                                <p class="font-medium">{{ $p->mahasiswa->name }}</p>
                                <p class="text-xs text-gray-400">{{ $p->mahasiswa->nim }}</p>
                            </div>
                            <div>
                                <p class="text-gray-500">Pembimbing</p>
                                <p class="font-medium">{{ $p->pembimbing1?->dosen?->name ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-gray-500">Tanggal Daftar</p>
                                <p class="font-medium">{{ $p->created_at->format('d M Y') }}</p>
                            </div>
                            <div>
                                <p class="text-gray-500">Berkas</p>
                                @if($p->file_proposal)
                                    <a href="{{ asset('storage/' . $p->file_proposal) }}" target="_blank"
                                        class="text-blue-600 hover:underline text-sm">Lihat Berkas</a>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </div>
                        </div>

                        @if($p->bidangKeahlians->count() > 0)
                            <div class="flex flex-wrap gap-1 mt-2">
                                @foreach($p->bidangKeahlians as $bk)
                                    <span
                                        class="px-2 py-0.5 bg-teal-100 text-teal-800 rounded-full text-xs">{{ $bk->nama_bidang }}</span>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

                @if($p->status === 'pending')
                    <div class="mt-4 flex items-center gap-3 pt-4 border-t">
                        <button wire:click="updateStatus({{ $p->id }}, 'disetujui_panitia')"
                            wire:confirm="Setujui pendaftaran ini?"
                            class="px-4 py-2 bg-green-700 text-white rounded-xl hover:bg-green-800 text-sm font-medium">Setujui
                            & Lanjutkan ke Sekjur</button>
                        <button wire:click="updateStatus({{ $p->id }}, 'ditolak_panitia')" wire:confirm="Tolak pendaftaran ini?"
                            class="px-4 py-2 bg-red-50 text-red-700 border border-red-200 rounded-xl hover:bg-red-100 text-sm font-medium">Tolak</button>
                    </div>
                @endif
            </div>
        @empty
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center text-gray-500">Tidak ada
                pendaftaran untuk diverifikasi</div>
        @endforelse
    </div>

    <div class="mt-4">{{ $pendaftarans->links() }}</div>
</div>

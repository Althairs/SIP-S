<div>
    @section('title', 'Generate Penguji')
    @section('page-title', 'Atur Penguji Ujian')

    <!-- Detail Pendaftaran -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
        <h2 class="text-lg font-semibold mb-4">Detail Pendaftaran</h2>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <p class="text-sm text-gray-500">Mahasiswa</p>
                <p class="font-medium">{{ $pendaftaran->mahasiswa->name }} ({{ $pendaftaran->mahasiswa->nim }})</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Jenis Ujian</p>
                <p class="font-medium">{{ ucwords(str_replace('_', ' ', $pendaftaran->jenis_ujian)) }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Pembimbing 1</p>
                <p class="font-medium">{{ $pendaftaran->pembimbing1?->dosen?->name ?? '-' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Pembimbing 2</p>
                <p class="font-medium">{{ $pendaftaran->pembimbing2?->dosen?->name ?? '-' }}</p>
            </div>
            <div class="col-span-2">
                <p class="text-sm text-gray-500">Judul</p>
                <p class="font-medium">{{ $pendaftaran->judul_penelitian }}</p>
            </div>
        </div>

        @if($pendaftaran->bidangKeahlians->count() > 0)
        <div class="mt-3">
            <p class="text-sm text-gray-500 mb-1">Bidang Keahlian:</p>
            <div class="flex flex-wrap gap-1">
                @foreach($pendaftaran->bidangKeahlians as $bk)
                <span class="px-2 py-0.5 bg-teal-100 text-teal-800 rounded-full text-xs">{{ $bk->nama_bidang }}</span>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    <!-- Mode Selection -->
    <div class="flex gap-3 mb-6">
        <button wire:click="generatePenguji" class="px-5 py-2.5 {{ $mode === 'auto' ? 'bg-orange-700 text-white' : 'bg-gray-100 text-gray-700' }} rounded-xl font-medium transition">
            <svg class="w-5 h-5 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/></svg>
            Generate Otomatis
        </button>
        <button wire:click="setManual" class="px-5 py-2.5 {{ $mode === 'manual' ? 'bg-orange-700 text-white' : 'bg-gray-100 text-gray-700' }} rounded-xl font-medium transition">
            <svg class="w-5 h-5 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
            Pilih Manual
        </button>
    </div>

    <div class="grid md:grid-cols-2 gap-6 mb-6">
        <!-- Penguji 1 -->
        <div class="bg-white rounded-2xl shadow-sm border border-green-200 p-6">
            <div class="flex items-center space-x-2 mb-4">
                <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-medium">Penguji 1 (Utama)</span>
                <span class="text-xs text-gray-500">Hierarki Tertinggi</span>
            </div>

            @if($mode === 'auto')
                @if($penguji1Id)
                    <div class="space-y-2">
                        <p class="font-semibold text-lg">{{ $penguji1['name'] ?? '-' }}</p>
                        <p class="text-sm text-gray-500">NIP: {{ $penguji1['nip'] ?? '-' }}</p>
                        <p class="text-sm">Kepakaran: <span class="font-medium text-purple-700">{{ $penguji1Kepakaran }}</span></p>
                        <p class="text-sm">
                            Kuota Tersisa:
                            <span class="font-medium {{ $penguji1Overload ? 'text-red-600' : 'text-green-600' }}">
                                {{ $penguji1Kuota }}
                                @if($penguji1Overload) ⚠️ Overload @endif
                            </span>
                        </p>
                    </div>
                @else
                    <p class="text-red-500">Tidak ada dosen tersedia.</p>
                @endif
            @else
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Dosen Penguji 1</label>
                    <select wire:model="manualPenguji1" wire:change="setManual" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500">
                        <option value="">-- Pilih Dosen --</option>
                        @foreach($availableDosens as $d)
                        <option value="{{ $d->id }}" {{ $d->id == $manualPenguji2 ? 'disabled' : '' }}>
                            {{ $d->name }} | {{ $d->kepakaran?->nama_kepakaran ?? 'No Kepakaran' }} | Sisa: {{ $d->kuota?->sisa_penguji ?? 0 }}
                        </option>
                        @endforeach
                    </select>
                </div>
            @endif
        </div>

        <!-- Penguji 2 -->
        <div class="bg-white rounded-2xl shadow-sm border border-blue-200 p-6">
            <div class="flex items-center space-x-2 mb-4">
                <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-medium">Penguji 2</span>
                <span class="text-xs text-gray-500">Kuota Terbanyak / Manual</span>
            </div>

            @if($mode === 'auto')
                @if($penguji2Id)
                    <div class="space-y-2">
                        <p class="font-semibold text-lg">{{ $penguji2['name'] ?? '-' }}</p>
                        <p class="text-sm text-gray-500">NIP: {{ $penguji2['nip'] ?? '-' }}</p>
                        <p class="text-sm">Kepakaran: <span class="font-medium text-purple-700">{{ $penguji2Kepakaran }}</span></p>
                        <p class="text-sm">
                            Kuota Tersisa:
                            <span class="font-medium {{ $penguji2Overload ? 'text-red-600' : 'text-green-600' }}">
                                {{ $penguji2Kuota }}
                                @if($penguji2Overload) ⚠️ Overload @endif
                            </span>
                        </p>
                    </div>
                @else
                    <p class="text-red-500">Tidak ada dosen tersedia.</p>
                @endif
            @else
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Dosen Penguji 2</label>
                    <select wire:model="manualPenguji2" wire:change="setManual" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500">
                        <option value="">-- Pilih Dosen (Opsional) --</option>
                        @foreach($availableDosens as $d)
                        <option value="{{ $d->id }}" {{ $d->id == $manualPenguji1 ? 'disabled' : '' }}>
                            {{ $d->name }} | {{ $d->kepakaran?->nama_kepakaran ?? 'No Kepakaran' }} | Sisa: {{ $d->kuota?->sisa_penguji ?? 0 }}
                        </option>
                        @endforeach
                    </select>
                </div>
            @endif
        </div>
    </div>

    <!-- Available Dosen Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-6">
        <div class="px-6 py-4 border-b border-gray-100">
            <h2 class="text-lg font-semibold text-gray-900">Dosen Tersedia</h2>
            <p class="text-xs text-gray-500">Diurutkan berdasarkan skor (kepakaran + kuota)</p>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Nama</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Kepakaran</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Kuota Tersisa</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Skor</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($availableDosens->take(10) as $d)
                    <tr class="hover:bg-gray-50 {{ $d->id == $penguji1Id ? 'bg-green-50' : '' }} {{ $d->id == $penguji2Id ? 'bg-blue-50' : '' }}">
                        <td class="px-4 py-3">
                            <p class="font-medium text-gray-900 text-sm">{{ $d->name }}</p>
                            <p class="text-xs text-gray-500">{{ $d->nip }}</p>
                        </td>
                        <td class="px-4 py-3 text-sm">{{ $d->kepakaran?->nama_kepakaran ?? '-' }}</td>
                        <td class="px-4 py-3 text-sm text-center">
                            <span class="font-medium {{ ($d->kuota?->sisa_penguji ?? 0) < 0 ? 'text-red-600' : 'text-green-600' }}">
                                {{ $d->kuota?->sisa_penguji ?? 'N/A' }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            @if($d->kuota && $d->kuota->is_overload_penguji)
                            <span class="px-2 py-0.5 bg-red-100 text-red-800 rounded-full text-xs">Overload</span>
                            @else
                            <span class="px-2 py-0.5 bg-green-100 text-green-800 rounded-full text-xs">Tersedia</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-sm font-medium">{{ $d->score }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Actions -->
    <div class="flex items-center gap-4">
        <button wire:click="simpanPenguji" class="px-6 py-3 bg-orange-700 text-white rounded-xl hover:bg-orange-800 font-medium" {{ !$penguji1Id ? 'disabled' : '' }}>
            <svg class="w-5 h-5 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            Simpan Penguji & Teruskan ke Penjadwalan
        </button>
        <a href="{{ route('sekjur.data-master.penguji') }}" class="px-6 py-3 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 font-medium">
            Batal
        </a>
    </div>
</div>

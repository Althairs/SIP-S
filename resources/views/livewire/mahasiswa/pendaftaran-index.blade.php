<div>
    @section('title', 'Pendaftaran Ujian')

    @if (session()->has('success'))
    <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-800 rounded-xl flex items-center" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)">
        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
        {{ session('success') }}
    </div>
    @endif

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Daftar Pendaftaran</h1>
        <a href="{{ route('mahasiswa.pendaftaran.create') }}" class="px-5 py-2.5 bg-blue-700 text-white rounded-xl hover:bg-blue-800 transition font-medium flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Daftar Baru
        </a>
    </div>

    <div class="grid gap-4">
        @forelse($pendaftarans as $pendaftaran)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    {{-- Status Badge --}}
                    <div class="flex items-center gap-3 mb-2">
                        <span class="px-3 py-1 bg-{{ $pendaftaran->statusColor }}-100 text-{{ $pendaftaran->statusColor }}-800 rounded-full text-xs font-medium">
                            {{ $pendaftaran->statusLabel }}
                        </span>
                        <span class="text-sm text-gray-500">{{ $pendaftaran->created_at->format('d M Y H:i') }}</span>
                    </div>

                    {{-- Judul --}}
                    <h3 class="font-semibold text-gray-900 text-lg">{{ $pendaftaran->judul_penelitian }}</h3>

                    {{-- Jenis Ujian --}}
                    <p class="text-sm text-gray-500 mt-1">
                        Jenis: {{ ucwords(str_replace('_', ' ', $pendaftaran->jenis_ujian)) }}
                    </p>

                    {{-- Bidang Keahlian --}}
                    @if($pendaftaran->bidangKeahlians && $pendaftaran->bidangKeahlians->count() > 0)
                    <div class="flex flex-wrap gap-1 mt-2">
                        @foreach($pendaftaran->bidangKeahlians as $bk)
                        <span class="px-2 py-0.5 bg-teal-100 text-teal-800 rounded-full text-xs font-medium">
                            {{ $bk->nama_bidang }}
                        </span>
                        @endforeach
                    </div>
                    @endif

                    {{-- Info Tambahan --}}
                    @if($pendaftaran->tanggal_ujian)
                    <p class="text-sm text-blue-600 mt-2">
                        📅 Ujian: {{ $pendaftaran->tanggal_ujian->format('d M Y, H:i') }}
                        @if($pendaftaran->ruangan)
                        - {{ $pendaftaran->ruangan }}
                        @endif
                    </p>
                    @endif

                    {{-- Nilai (jika sudah selesai) --}}
                    @if($pendaftaran->status === 'selesai' && $pendaftaran->nilai_total)
                    <div class="mt-3 inline-flex items-center gap-2 bg-green-50 rounded-lg px-3 py-1.5">
                        <span class="text-sm text-green-600 font-medium">Nilai:</span>
                        <span class="text-lg font-bold text-green-800">{{ $pendaftaran->nilai_total }}</span>
                        <span class="px-2 py-0.5 bg-green-200 text-green-800 rounded text-xs font-bold">{{ $pendaftaran->grade }}</span>
                    </div>
                    @endif
                </div>

                {{-- Action Buttons --}}
                <div class="flex gap-2 ml-4 flex-shrink-0">
                    @if(in_array($pendaftaran->status, ['draft', 'revisi']))
                    <a href="{{ route('mahasiswa.pendaftaran.edit', $pendaftaran->id) }}"
                       class="px-3 py-1.5 bg-amber-50 text-amber-700 rounded-lg hover:bg-amber-100 transition text-sm font-medium">
                        Edit
                    </a>
                    @endif

                    @if($pendaftaran->status === 'draft')
                    <button wire:click="deletePendaftaran({{ $pendaftaran->id }})"
                            wire:confirm="Hapus pendaftaran ini?"
                            class="px-3 py-1.5 bg-red-50 text-red-700 rounded-lg hover:bg-red-100 transition text-sm font-medium">
                        Hapus
                    </button>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center">
            <div class="w-20 h-20 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-10 h-10 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Belum Ada Pendaftaran</h3>
            <p class="text-gray-500 mb-4">Anda belum mendaftarkan ujian apapun.</p>
            <a href="{{ route('mahasiswa.pendaftaran.create') }}"
               class="inline-flex px-5 py-2.5 bg-blue-700 text-white rounded-xl hover:bg-blue-800 transition font-medium">
                Daftar Sekarang
            </a>
        </div>
        @endforelse
    </div>
</div>

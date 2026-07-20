<div>
    @section('title', 'Pendaftaran Ujian')

    @if (session()->has('success'))
    <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-800 rounded-xl flex items-center" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)">
        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
        {{ session('success') }}
    </div>
    @endif

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Daftar Pendaftaran</h1>
            @if($isSuperAdmin)
                <p class="text-xs text-green-700 font-medium mt-0.5">Mode Super Admin: Menampilkan seluruh pendaftaran mahasiswa</p>
            @endif
        </div>
        <a href="{{ route('mahasiswa.pendaftaran.create') }}" class="px-5 py-2.5 bg-green-700 text-white rounded-xl hover:bg-green-800 transition font-medium flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah Pendaftaran Baru
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

                    {{-- Informasi Mahasiswa (Diperlukan jika diakses Super Admin atau untuk memperjelas pemilik pendaftaran) --}}
                    @if($pendaftaran->mahasiswa)
                    <div class="mb-2 p-2.5 bg-gray-50 rounded-xl border border-gray-100 inline-block w-full sm:w-auto">
                        <p class="text-xs text-gray-500 font-medium">Pemohon / Mahasiswa:</p>
                        <p class="text-sm font-semibold text-gray-800">
                            {{ $pendaftaran->mahasiswa->name }}
                            <span class="text-xs font-normal text-gray-500">({{ $pendaftaran->mahasiswa->nim ?? 'NIM -' }})</span>
                        </p>
                        @if($pendaftaran->mahasiswa->jurusan)
                            <p class="text-xs text-gray-500">{{ $pendaftaran->mahasiswa->jurusan->nama_jurusan }}</p>
                        @endif
                    </div>
                    @endif

                    {{-- Judul --}}
                    <h3 class="font-semibold text-gray-900 text-lg mt-1">{{ $pendaftaran->judul_penelitian }}</h3>

                    {{-- Jenis Ujian --}}
                    <p class="text-sm text-gray-500 mt-1">
                        Jenis: {{ ucwords(str_replace('_', ' ', $pendaftaran->jenis_ujian)) }}
                    </p>

                    {{-- Bidang Keahlian --}}
                    @if($pendaftaran->bidangKeahlians && $pendaftaran->bidangKeahlians->count() > 0)
                    <div class="flex flex-wrap gap-1 mt-2">
                        @foreach($pendaftaran->bidangKeahlians as $bk)
                        <span class="px-2 py-0.5 bg-green-100 text-green-800 rounded-full text-xs font-medium">
                            {{ $bk->nama_bidang }}
                        </span>
                        @endforeach
                    </div>
                    @endif

                    {{-- Info Tambahan --}}
                    @if($pendaftaran->tanggal_ujian)
                    <p class="text-sm text-green-600 mt-2 inline-flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        Ujian: {{ $pendaftaran->tanggal_ujian->format('d M Y, H:i') }}
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
                    @if($isSuperAdmin || in_array($pendaftaran->status, ['draft', 'revisi']))
                    <a href="{{ route('mahasiswa.pendaftaran.edit', $pendaftaran->id) }}"
                       class="px-3 py-1.5 bg-amber-50 text-amber-700 rounded-lg hover:bg-amber-100 transition text-sm font-medium">
                        Edit
                    </a>
                    @endif

                    @if($isSuperAdmin || $pendaftaran->status === 'draft')
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
            <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Belum Ada Pendaftaran</h3>
            <p class="text-gray-500 mb-4">
                {{ $isSuperAdmin ? 'Belum ada pendaftaran mahasiswa yang tersimpan di sistem.' : 'Anda belum mendaftarkan ujian apapun.' }}
            </p>
            <a href="{{ route('mahasiswa.pendaftaran.create') }}"
               class="inline-flex px-5 py-2.5 bg-green-700 text-white rounded-xl hover:bg-green-800 transition font-medium">
                Daftar Sekarang
            </a>
        </div>
        @endforelse
    </div>
</div>

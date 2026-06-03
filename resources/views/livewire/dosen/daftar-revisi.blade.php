<div>
    @section('title', 'Daftar Revisi')
    @section('page-title', 'Kelola Revisi Mahasiswa')

    @if (session()->has('success'))
    <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-800 rounded-xl" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)">{{ session('success') }}</div>
    @endif

    <!-- Tab: Belum Revisi | Sudah Revisi -->
    <div class="flex border-b border-gray-200 mb-6" x-data="{ tab: 'pending' }">
        <button @click="tab = 'pending'" :class="tab === 'pending' ? 'border-amber-600 text-amber-700' : 'border-transparent text-gray-500'" class="px-6 py-3 text-sm font-medium border-b-2">Perlu Direvisi</button>
        <button @click="tab = 'history'" :class="tab === 'history' ? 'border-amber-600 text-amber-700' : 'border-transparent text-gray-500'" class="px-6 py-3 text-sm font-medium border-b-2">Riwayat Revisi</button>
    </div>

    <!-- Perlu Direvisi -->
    <div x-data="{ tab: 'pending' }" x-show="tab === 'pending'">
        @if($ujianSelesai->isEmpty())
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center text-gray-500">Tidak ada ujian selesai.</div>
        @else
        <div class="space-y-4">
            @foreach($ujianSelesai as $ujian)
            @php
                $revisiSaya = $ujian->pendaftaran->revisis->where('dosen_id', Auth::id());
                $sudahDirevisi = $revisiSaya->count() > 0;
            @endphp
            <div class="bg-white rounded-2xl shadow-sm border {{ $sudahDirevisi ? 'border-green-200' : 'border-amber-200' }} p-6">
                <div class="flex justify-between items-start">
                    <div>
                        <span class="px-2 py-1 bg-{{ $sudahDirevisi ? 'green' : 'amber' }}-100 text-{{ $sudahDirevisi ? 'green' : 'amber' }}-800 rounded-full text-xs">
                            {{ $sudahDirevisi ? 'Sudah Direvisi' : 'Belum Direvisi' }}
                        </span>
                        <p class="font-semibold mt-2">{{ $ujian->pendaftaran->mahasiswa->name }} ({{ $ujian->pendaftaran->mahasiswa->nim }})</p>
                        <p class="text-sm text-gray-500">{{ Str::limit($ujian->pendaftaran->judul_penelitian, 60) }}</p>
                        <p class="text-xs text-gray-400">Peran: {{ str_replace('_', ' ', $ujian->peran) }}</p>
                    </div>
                    <a href="{{ route('dosen.revisi.berikan', $ujian->pendaftaran_id) }}" class="px-4 py-2 bg-amber-700 text-white rounded-xl hover:bg-amber-800 text-sm">
                        {{ $sudahDirevisi ? 'Edit Revisi' : 'Beri Revisi' }}
                    </a>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>

    <!-- Riwayat Revisi -->
    <div x-data="{ tab: 'pending' }" x-show="tab === 'history'">
        @if($revisiSaya->isEmpty())
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center text-gray-500">Belum ada riwayat revisi.</div>
        @else
        <div class="space-y-3">
            @foreach($revisiSaya as $rev)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4">
                <div class="flex justify-between">
                    <div>
                        <span class="px-2 py-0.5 bg-{{ $rev->status === 'selesai' ? 'green' : ($rev->status === 'ditolak' ? 'red' : 'amber') }}-100 text-{{ $rev->status === 'selesai' ? 'green' : ($rev->status === 'ditolak' ? 'red' : 'amber') }}-800 rounded-full text-xs">{{ ucfirst($rev->status) }}</span>
                        <span class="px-2 py-0.5 bg-{{ $rev->kategoriColor }}-100 text-{{ $rev->kategoriColor }}-800 rounded-full text-xs ml-1">{{ $rev->kategoriLabel }}</span>
                    </div>
                    <span class="text-xs text-gray-400">{{ $rev->created_at->format('d M Y') }}</span>
                </div>
                <p class="text-sm mt-2">{{ Str::limit($rev->isi_revisi, 100) }}</p>
                <p class="text-xs text-gray-400">{{ $rev->pendaftaran->mahasiswa->name }}</p>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</div>

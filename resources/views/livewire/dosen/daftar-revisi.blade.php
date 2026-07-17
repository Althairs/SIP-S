<div>
    @section('title', 'Daftar Revisi')
    @section('page-title', 'Kelola Revisi Mahasiswa')

    @if (session()->has('success'))
    <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-800 rounded-xl" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)">{{ session('success') }}</div>
    @endif

    <!-- Breadcrumb -->
    <nav class="flex items-center text-sm text-gray-500 mb-6">
        <a href="{{ route('dosen.revisi.index') }}" class="hover:text-green-700 transition">Revisi</a>
        <svg class="w-4 h-4 mx-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        @if($showDetail)
            <a wire:click="closeDetail" class="hover:text-green-700 transition cursor-pointer">Daftar Revisi</a>
            <svg class="w-4 h-4 mx-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            <span class="text-green-700 font-medium">Detail</span>
        @else
            <span class="text-green-700 font-medium">Daftar Revisi</span>
        @endif
    </nav>

    @if(!$showDetail)
    <div x-data="{ tab: 'pending' }">
        <!-- Tab Navigation -->
        <div class="flex border-b border-gray-200 mb-6 space-x-2 overflow-x-auto">
            <button @click="tab = 'pending'" :class="tab === 'pending' ? 'border-green-600 text-green-700 bg-green-50 rounded-t-lg' : 'border-transparent text-gray-500 hover:bg-gray-50'" class="px-6 py-3 text-sm font-medium border-b-2 transition whitespace-nowrap">Perlu Direvisi (Sebagai Penguji)</button>
            <button @click="tab = 'history'" :class="tab === 'history' ? 'border-green-600 text-green-700 bg-green-50 rounded-t-lg' : 'border-transparent text-gray-500 hover:bg-gray-50'" class="px-6 py-3 text-sm font-medium border-b-2 transition whitespace-nowrap">Riwayat Revisi (Sebagai Penguji)</button>
            <button @click="tab = 'bimbingan'" :class="tab === 'bimbingan' ? 'border-green-600 text-green-700 bg-green-50 rounded-t-lg' : 'border-transparent text-gray-500 hover:bg-gray-50'" class="px-6 py-3 text-sm font-medium border-b-2 transition whitespace-nowrap">Revisi Bimbingan</button>
        </div>

        <!-- Perlu Direvisi -->
        <div x-show="tab === 'pending'" x-cloak>
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
                        <a href="{{ route('dosen.revisi.berikan', $ujian->pendaftaran_id) }}" class="px-4 py-2 bg-green-700 text-white rounded-xl hover:bg-green-800 text-sm">
                            {{ $sudahDirevisi ? 'Edit Revisi' : 'Beri Revisi' }}
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>

        <!-- Riwayat Revisi -->
        <div x-show="tab === 'history'" x-cloak>
    @if($revisiSaya->isEmpty())
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center text-gray-500">
        Belum ada riwayat revisi yang disetujui.
    </div>
    @else
    <div class="space-y-3">
        @foreach($revisiSaya as $rev)
        <div class="bg-white rounded-2xl shadow-sm border border-green-200 p-4 bg-green-50/20">
            <div class="flex justify-between items-center">
                <div class="flex items-center gap-2">
                    <span class="px-2.5 py-0.5 bg-green-100 text-green-800 rounded-full text-xs font-semibold">Disetujui / Selesai</span>
                    <span class="px-2 py-0.5 bg-gray-100 text-gray-700 rounded-full text-xs">{{ strtoupper($rev->kategori) }}</span>
                </div>
                <span class="text-xs text-gray-500">Disetujui pada: {{ $rev->approved_at ? \Carbon\Carbon::parse($rev->approved_at)->format('d M Y, H:i') : '-' }}</span>
            </div>
            <p class="text-sm font-medium text-gray-800 mt-2">{{ $rev->isi_revisi }}</p>
            <div class="mt-2 flex justify-between items-center text-xs text-gray-500 border-t pt-2">
                <span>Mahasiswa: <strong>{{ $rev->pendaftaran->mahasiswa->name }}</strong></span>
                @if($rev->file_revisi_mahasiswa)
                <a href="{{ asset('storage/' . $rev->file_revisi_mahasiswa) }}" target="_blank" class="text-green-600 hover:underline">Lihat Arsip File</a>
                @endif
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>

        <!-- Revisi Bimbingan -->
        <div x-show="tab === 'bimbingan'" x-cloak>
            @if($bimbinganUjian->isEmpty())
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center text-gray-500">Belum ada mahasiswa bimbingan yang memiliki revisi ujian.</div>
            @else
            <div class="space-y-4">
                @foreach($bimbinganUjian as $ujian)
                <div class="bg-white rounded-2xl shadow-sm border border-green-200 p-6">
                    <div class="flex justify-between items-start">
                        <div class="w-full">
                            <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs font-medium">
                                Mahasiswa Bimbingan
                            </span>
                            <p class="font-semibold mt-2">{{ $ujian->mahasiswa->name }} ({{ $ujian->mahasiswa->nim }})</p>
                            <p class="text-sm text-gray-500 mb-2">{{ Str::limit($ujian->judul_penelitian, 60) }}</p>

                            <div class="space-y-3 mt-4">
                                <p class="text-xs font-semibold text-gray-700 uppercase tracking-wider">Catatan Revisi dari Penguji:</p>
                                @if($ujian->revisis->isEmpty())
                                <p class="text-sm text-gray-400 italic">Tidak ada catatan revisi.</p>
                                @else
                                    @foreach($ujian->revisis as $revisi)
                                    <div class="bg-gray-50 rounded-xl p-4 border border-gray-100 relative">
                                        <div class="flex justify-between">
                                            <p class="text-sm font-medium text-gray-900">{{ $revisi->dosen->name }}</p>
                                            <span class="px-2 py-0.5 rounded-full text-xs font-medium
                                                {{ $revisi->status == 'pending' ? 'bg-gray-200 text-gray-700' :
                                                  ($revisi->status == 'diperiksa' ? 'bg-green-200 text-green-700' : 'bg-green-200 text-green-700') }}">
                                                {{ ucfirst($revisi->status) }}
                                            </span>
                                        </div>
                                        <p class="text-xs text-gray-500 mb-2">{{ ucwords(str_replace('_', ' ', $revisi->peran_pemberi)) }}</p>
                                        <p class="text-sm text-gray-700 mb-2">{{ $revisi->isi_revisi }}</p>

                                        @if($revisi->file_revisi_mahasiswa)
                                        <div class="mt-2 border-t pt-2">
                                            <a href="{{ asset('storage/' . $revisi->file_revisi_mahasiswa) }}" target="_blank" class="inline-flex items-center text-xs text-green-600 hover:text-green-800">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                                                Lihat File Revisi
                                            </a>
                                            @if($revisi->status == 'diperiksa')
                                            <button wire:click="selectRevisi({{ $revisi->id }})" class="ml-3 inline-flex items-center text-xs text-green-600 hover:text-green-800">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                Review & Setujui
                                            </button>
                                            @endif
                                        </div>
                                        @endif
                                    </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>
    </div>
    @endif

    <!-- Detail Review & Approval (Inline Card) -->
    @if($showDetail && $selectedRevisiId)
        @php
            $selectedRevisi = $revisiSaya->firstWhere('id', $selectedRevisiId) ??
                             ($bimbinganUjian->pluck('revisis')->flatten()->firstWhere('id', $selectedRevisiId));
        @endphp
        @if($selectedRevisi)
        <div class="bg-white rounded-2xl shadow-sm border border-green-200 overflow-hidden">
            <div class="bg-gradient-to-r from-green-600 to-green-800 px-6 py-4 flex justify-between items-center">
                <h3 class="text-lg leading-6 font-semibold text-white">Review & Persetujuan Revisi</h3>
                <button wire:click="closeDetail" class="text-white hover:text-gray-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <div class="px-6 py-5">
                <div class="mb-5 bg-gray-50 p-4 rounded-xl border border-gray-100">
                    <p class="text-xs text-gray-500 font-medium mb-1">Catatan Revisi dari Dosen</p>
                    <p class="text-gray-800 text-sm whitespace-pre-line">{{ $selectedRevisi->isi_revisi }}</p>
                </div>

                @if($selectedRevisi->file_revisi_mahasiswa)
                <div class="mb-5 bg-green-50 p-4 rounded-xl border border-green-100">
                    <p class="text-xs text-green-600 font-medium mb-2">File Revisi Mahasiswa</p>
                    <a href="{{ asset('storage/' . $selectedRevisi->file_revisi_mahasiswa) }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 text-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                        Download & Review File
                    </a>
                    <p class="text-xs text-gray-500 mt-2">Diunggah: {{ $selectedRevisi->uploaded_at ? $selectedRevisi->uploaded_at->format('d M Y, H:i') : '-' }}</p>
                </div>
                @endif

                @if($selectedRevisi->catatan_mahasiswa)
                <div class="mb-5 bg-amber-50 p-4 rounded-xl border border-amber-100">
                    <p class="text-xs text-amber-600 font-medium mb-1">Catatan Mahasiswa</p>
                    <p class="text-gray-800 text-sm whitespace-pre-line">{{ $selectedRevisi->catatan_mahasiswa }}</p>
                </div>
                @endif

                <div class="mt-6 border-t border-gray-200 pt-5">
                    <h4 class="text-sm font-semibold text-gray-900 mb-4">Tindakan Review</h4>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Catatan Review (Opsional)</label>
                        <textarea wire:model="catatan_dosen" rows="3" class="w-full border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 p-3 text-sm" placeholder="Tambahkan catatan review..."></textarea>
                        @error('catatan_dosen') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex gap-3">
                        <button wire:click="approveRevisi" class="flex-1 px-5 py-2 bg-green-600 text-white rounded-xl hover:bg-green-700 transition shadow-sm font-medium text-sm flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Setujui Revisi
                        </button>
                        <button wire:click="requestRevisiBaru" class="flex-1 px-5 py-2 bg-amber-600 text-white rounded-xl hover:bg-amber-700 transition shadow-sm font-medium text-sm flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                            Minta Revisi Baru
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @endif
    @endif
</div>

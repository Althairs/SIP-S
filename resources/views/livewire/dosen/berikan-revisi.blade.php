<div>
    @section('title', 'Berikan Revisi')
    @section('page-title', 'Berikan Revisi')

    @if (session()->has('success'))
    <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-800 rounded-xl" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)">{{ session('success') }}</div>
    @endif

    <!-- Breadcrumb -->
    <nav class="flex items-center text-sm text-gray-500 mb-6">
        <a href="{{ route('dosen.revisi.index') }}" class="hover:text-green-700 transition">Revisi</a>
        <svg class="w-4 h-4 mx-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <a href="{{ route('dosen.revisi.berikan', $pendaftaran->id) }}" class="hover:text-green-700 transition cursor-pointer" wire:navigate>Berikan Revisi</a>
        @if($modalMode)
            <svg class="w-4 h-4 mx-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            <span class="text-green-700 font-medium">{{ $modalMode === 'input' ? 'Input' : 'Review' }}</span>
        @endif
    </nav>

    @if(!$modalMode)
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
        <div class="grid md:grid-cols-2 gap-4">
            <div>
                <p class="text-sm text-gray-500">Mahasiswa</p>
                <p class="font-bold text-lg">{{ $pendaftaran->mahasiswa->name }} ({{ $pendaftaran->mahasiswa->nim }})</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Jenis Ujian</p>
                <p class="font-bold">{{ ucwords(str_replace('_', ' ', $pendaftaran->jenis_ujian)) }}</p>
            </div>
            <div class="col-span-2">
                <p class="text-sm text-gray-500">Judul</p>
                <p class="font-medium">{{ $pendaftaran->judul_penelitian }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Peran Anda</p>
                <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs font-medium">{{ ucwords(str_replace('_', ' ', $peranDosen)) }}</span>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold">Revisi Saya</h3>
            <button wire:click="openForm" class="px-4 py-2 bg-green-700 text-white rounded-xl hover:bg-green-800 text-sm font-medium">+ Tambah Revisi</button>
        </div>

        @if($existingRevisis->isEmpty())
        <div class="text-center py-8 text-gray-500">Belum ada revisi. Klik "Tambah Revisi" untuk memulai.</div>
        @else
        <div class="space-y-4">
            @foreach($existingRevisis as $rev)
            @php
                $isDiperiksa = $rev->status === 'diperiksa';
                $isSelesai = in_array($rev->status, ['selesai', 'disetujui']);
            @endphp
            <div class="border {{ $isSelesai ? 'border-green-300 bg-green-50/50' : ($isDiperiksa ? 'border-green-400 bg-green-50/50 shadow-sm' : 'border-amber-200 bg-amber-50/30') }} rounded-xl p-4 transition">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="px-2.5 py-0.5 bg-{{ $rev->kategoriColor ?? 'gray' }}-100 text-{{ $rev->kategoriColor ?? 'gray' }}-800 rounded-full text-xs font-semibold">{{ strtoupper($rev->kategori) }}</span>

                            @if($isDiperiksa)
                                <span class="px-2.5 py-0.5 bg-green-600 text-white rounded-full text-xs font-semibold animate-pulse">Perlu Diperiksa</span>
                            @elseif($isSelesai)
                                <span class="px-2.5 py-0.5 bg-green-100 text-green-800 rounded-full text-xs font-semibold">Selesai / Disetujui</span>
                            @else
                                <span class="px-2.5 py-0.5 bg-amber-100 text-amber-800 rounded-full text-xs font-semibold">Menunggu Mahasiswa</span>
                            @endif
                        </div>
                        <p class="text-sm font-medium text-gray-800">{{ $rev->isi_revisi }}</p>
                        <p class="text-xs text-gray-500 mt-1">Deadline: {{ $rev->deadline?->format('d M Y') ?? '-' }}</p>

                        @if($rev->file_revisi_mahasiswa)
                        <div class="mt-3 p-3 bg-white rounded-xl border border-gray-200 text-sm">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-semibold text-green-600 flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    File Hasil Revisi Mahasiswa:
                                </span>
                                <a href="{{ asset('storage/' . $rev->file_revisi_mahasiswa) }}" target="_blank" class="px-3 py-1 bg-green-50 text-green-600 hover:bg-green-100 rounded-lg text-xs font-medium transition">Lihat & Download File</a>
                            </div>
                            @if($rev->catatan_mahasiswa)
                            <p class="text-xs text-gray-600 mt-2 italic bg-gray-50 p-2 rounded-lg">"{{ $rev->catatan_mahasiswa }}"</p>
                            @endif
                        </div>
                        @endif

                        @if($rev->catatan_dosen)
                        <div class="mt-2 text-xs text-gray-600 bg-white/60 p-2 rounded-lg border border-gray-200">
                            <strong>Catatan Review Anda:</strong> {{ $rev->catatan_dosen }}
                        </div>
                        @endif
                    </div>

                    <div class="flex flex-wrap gap-2 w-full md:w-auto justify-end">
                        @if($isDiperiksa)
                        <button wire:click="openReviewModal({{ $rev->id }})" class="px-4 py-2 bg-green-600 text-white rounded-xl hover:bg-green-700 text-xs font-semibold shadow-sm flex items-center gap-1.5 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                            Review Hasil
                        </button>
                        @endif

                        @if(!$isSelesai)
                        <button wire:click="openForm({{ $rev->id }})" class="px-3 py-1.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 text-xs font-medium">Edit</button>
                        @endif
                        <button wire:click="deleteRevisi({{ $rev->id }})" wire:confirm="Hapus revisi ini?" class="px-3 py-1.5 bg-red-50 text-red-700 rounded-lg hover:bg-red-100 text-xs font-medium">Hapus</button>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>
    @endif

    {{-- Form Tambah/Edit Revisi (Inline Card) --}}
    @if($modalMode === 'input')
    <div class="bg-white rounded-2xl shadow-sm border border-green-200 overflow-hidden">
        <div class="bg-gradient-to-r from-green-600 to-green-800 px-6 py-4 flex justify-between items-center">
            <h3 class="text-lg font-semibold text-white">{{ $editRevisiId ? 'Edit' : 'Tambah' }} Revisi</h3>
            <button wire:click="closeForm" class="text-white hover:text-gray-200">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <div class="p-6">
            <form wire:submit="saveRevisi">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kategori <span class="text-red-500">*</span></label>
                        <select wire:model="kategori" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500">
                            <option value="minor">Minor (Kesalahan kecil)</option>
                            <option value="major">Mayor (Perlu perbaikan signifikan)</option>
                            <option value="kritis">Kritis (Wajib diperbaiki)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Isi Revisi <span class="text-red-500">*</span></label>
                        <textarea wire:model="isiRevisi" rows="5" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 @error('isiRevisi') border-red-500 @enderror" placeholder="Tuliskan revisi..."></textarea>
                        @error('isiRevisi') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Deadline (Hari) <span class="text-red-500">*</span></label>
                        <input type="number" wire:model="deadlineDays" min="1" max="90" class="w-32 px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500">
                        <span class="text-sm text-gray-500 ml-2">hari dari sekarang</span>
                    </div>
                </div>
                <div class="mt-6 flex justify-end gap-3">
                    <button type="button" wire:click="closeForm" class="px-5 py-2.5 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 font-medium text-sm">Batal</button>
                    <button type="submit" class="px-5 py-2.5 bg-green-700 text-white rounded-xl hover:bg-green-800 font-medium text-sm">{{ $editRevisiId ? 'Perbarui' : 'Simpan' }} Revisi</button>
                </div>
            </form>
        </div>
    </div>
    @endif

    {{-- Review & Persetujuan File Mahasiswa (Inline Card) --}}
    @if($modalMode === 'review')
    <div class="bg-white rounded-2xl shadow-sm border border-green-200 overflow-hidden">
        <div class="bg-gradient-to-r from-green-600 to-green-800 px-6 py-4 flex justify-between items-center">
            <h3 class="text-lg font-semibold text-white">Review Revisi Mahasiswa</h3>
            <button wire:click="closeReviewModal" class="text-white hover:text-gray-200">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <div class="p-6 space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Catatan Review untuk Mahasiswa (Opsional jika disetujui, Wajib jika ditolak)</label>
                <textarea wire:model="catatanDosenReview" rows="4" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 text-sm" placeholder="Contoh: Bagian bab 4 sudah bagus, revisi disetujui... / Mohon perbaiki lagi bagian kesimpulan..."></textarea>
                @error('catatanDosenReview') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="pt-4 border-t border-gray-100 flex gap-3">
                <button wire:click="rejectRevisi" wire:confirm="Kembalikan revisi ini agar mahasiswa memperbaiki ulang?" class="flex-1 py-2.5 bg-red-600 hover:bg-red-700 text-white font-medium rounded-xl text-sm transition flex items-center justify-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    Tolak & Minta Ulang
                </button>
                <button wire:click="approveRevisi" wire:confirm="Setujui revisi ini? Revisi akan masuk ke riwayat." class="flex-1 py-2.5 bg-green-600 hover:bg-green-700 text-white font-medium rounded-xl text-sm transition flex items-center justify-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Setujui Revisi
                </button>
            </div>
        </div>
    </div>
    @endif
</div>

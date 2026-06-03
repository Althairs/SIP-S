<div>
    @section('title', 'Berikan Revisi')
    @section('page-title', 'Berikan Revisi')

    @if (session()->has('success'))
    <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-800 rounded-xl" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)">{{ session('success') }}</div>
    @endif

    <!-- Info Ujian -->
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
                <span class="px-2 py-1 bg-indigo-100 text-indigo-800 rounded-full text-xs font-medium">{{ ucwords(str_replace('_', ' ', $peranDosen)) }}</span>
            </div>
        </div>
    </div>

    <!-- Daftar Revisi Saya -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold">Revisi Saya</h3>
            <button wire:click="openForm" class="px-4 py-2 bg-amber-700 text-white rounded-xl hover:bg-amber-800 text-sm">+ Tambah Revisi</button>
        </div>

        @if($existingRevisis->isEmpty())
        <div class="text-center py-8 text-gray-500">Belum ada revisi. Klik "Tambah Revisi" untuk memulai.</div>
        @else
        <div class="space-y-4">
            @foreach($existingRevisis as $rev)
            <div class="border {{ $rev->status === 'selesai' ? 'border-green-200 bg-green-50' : ($rev->status === 'ditolak' ? 'border-red-200 bg-red-50' : 'border-amber-200 bg-amber-50') }} rounded-xl p-4">
                <div class="flex justify-between items-start">
                    <div class="flex-1">
                        <div class="flex gap-2 mb-2">
                            <span class="px-2 py-0.5 bg-{{ $rev->kategoriColor }}-100 text-{{ $rev->kategoriColor }}-800 rounded-full text-xs">{{ $rev->kategoriLabel }}</span>
                            <span class="px-2 py-0.5 bg-{{ $rev->status === 'selesai' ? 'green' : ($rev->status === 'ditolak' ? 'red' : 'amber') }}-100 text-{{ $rev->status === 'selesai' ? 'green' : ($rev->status === 'ditolak' ? 'red' : 'amber') }}-800 rounded-full text-xs">{{ ucfirst($rev->status) }}</span>
                        </div>
                        <p class="text-sm">{{ $rev->isi_revisi }}</p>
                        <p class="text-xs text-gray-500 mt-2">Deadline: {{ $rev->deadline?->format('d M Y') ?? '-' }}</p>

                        {{-- Upload Mahasiswa --}}
                        @if($rev->file_revisi_mahasiswa)
                        <div class="mt-2 p-2 bg-white rounded-lg text-sm">
                            <p class="text-xs text-gray-500">File Revisi Mahasiswa:</p>
                            <a href="{{ asset('storage/' . $rev->file_revisi_mahasiswa) }}" target="_blank" class="text-blue-600 hover:underline">Lihat File</a>
                            <p class="text-xs text-gray-500 mt-1">{{ $rev->catatan_mahasiswa }}</p>
                        </div>
                        @endif
                    </div>

                    <div class="flex gap-2 ml-4">
                        @if($rev->status === 'pending' && $rev->file_revisi_mahasiswa)
                        <button wire:click="approveRevisi({{ $rev->id }})" wire:confirm="Setujui revisi ini?" class="px-3 py-1.5 bg-green-700 text-white rounded-lg hover:bg-green-800 text-xs">Setujui</button>
                        @endif
                        <button wire:click="openForm({{ $rev->id }})" class="px-3 py-1.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 text-xs">Edit</button>
                        <button wire:click="deleteRevisi({{ $rev->id }})" wire:confirm="Hapus revisi?" class="px-3 py-1.5 bg-red-50 text-red-700 rounded-lg hover:bg-red-100 text-xs">Hapus</button>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>

    {{-- Form Revisi Modal --}}
    @if($showForm)
    <div class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-black/50" wire:click="closeForm"></div>
            <div class="relative bg-white rounded-2xl shadow-xl max-w-lg w-full p-6">
                <h3 class="text-lg font-bold mb-4">{{ $editRevisiId ? 'Edit' : 'Tambah' }} Revisi</h3>
                <form wire:submit="saveRevisi">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Kategori <span class="text-red-500">*</span></label>
                            <select wire:model="kategori" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-amber-500">
                                <option value="minor">Minor (Kesalahan kecil)</option>
                                <option value="major">Mayor (Perlu perbaikan signifikan)</option>
                                <option value="kritis">Kritis (Wajib diperbaiki)</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Isi Revisi <span class="text-red-500">*</span></label>
                            <textarea wire:model="isiRevisi" rows="5" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-amber-500 @error('isiRevisi') border-red-500 @enderror" placeholder="Tuliskan revisi..."></textarea>
                            @error('isiRevisi') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Deadline (Hari) <span class="text-red-500">*</span></label>
                            <input type="number" wire:model="deadlineDays" min="1" max="90" class="w-32 px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-amber-500">
                            <span class="text-sm text-gray-500 ml-2">hari dari sekarang</span>
                        </div>
                    </div>
                    <div class="mt-6 flex justify-end gap-4">
                        <button type="button" wire:click="closeForm" class="px-6 py-2.5 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 font-medium">Batal</button>
                        <button type="submit" class="px-6 py-2.5 bg-amber-700 text-white rounded-xl hover:bg-amber-800 font-medium">{{ $editRevisiId ? 'Perbarui' : 'Simpan' }} Revisi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>

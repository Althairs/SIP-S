<div>
    @section('title', $editMode ? 'Edit Pendaftaran' : 'Pendaftaran Ujian')

    <div class="max-w-4xl mx-auto">
        <!-- Breadcrumb -->
        <nav class="flex mb-6">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li><a href="{{ route('mahasiswa.dashboard') }}" class="text-gray-500 hover:text-gray-700">Dashboard</a></li>
                <li class="flex items-center">
                    <svg class="w-4 h-4 text-gray-400 mx-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/></svg>
                    <a href="{{ route('mahasiswa.pendaftaran.index') }}" class="text-gray-500 hover:text-gray-700">Pendaftaran</a>
                </li>
                <li class="flex items-center">
                    <svg class="w-4 h-4 text-gray-400 mx-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/></svg>
                    <span class="text-blue-700 font-medium">{{ $editMode ? 'Edit' : 'Daftar Baru' }}</span>
                </li>
            </ol>
        </nav>

        <form wire:submit="save" enctype="multipart/form-data">
            <!-- Data Mahasiswa (Read Only) -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Data Mahasiswa</h2>
                <div class="grid md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs text-gray-500">Nama</label>
                        <p class="font-medium">{{ $nama_mahasiswa }}</p>
                    </div>
                    <div>
                        <label class="block text-xs text-gray-500">NIM</label>
                        <p class="font-medium">{{ $nim }}</p>
                    </div>
                    <div>
                        <label class="block text-xs text-gray-500">Nomor HP</label>
                        <p class="font-medium">{{ $nomor_hp ?? '-' }}</p>
                    </div>
                    <div>
                        <label class="block text-xs text-gray-500">Email</label>
                        <p class="font-medium">{{ $email }}</p>
                    </div>
                    <div>
                        <label class="block text-xs text-gray-500">Jurusan</label>
                        <p class="font-medium">{{ $jurusan }}</p>
                    </div>
                </div>
            </div>

            <!-- Data Ujian -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Data Ujian</h2>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Ujian <span class="text-red-500">*</span></label>
                        <select wire:model="jenis_ujian" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 @error('jenis_ujian') border-red-500 @enderror" {{ $editMode ? 'disabled' : '' }}>
                            <option value="">Pilih Jenis Ujian</option>
                            <option value="seminar_proposal">Seminar Proposal</option>
                            <option value="seminar_hasil">Seminar Hasil</option>
                            <option value="sidang_skripsi">Sidang Skripsi</option>
                        </select>
                        @error('jenis_ujian') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Judul Penelitian <span class="text-red-500">*</span></label>
                        <input type="text" wire:model="judul_penelitian" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 @error('judul_penelitian') border-red-500 @enderror" placeholder="Masukkan judul penelitian">
                        @error('judul_penelitian') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <!-- BIDANG KEAHLIAN - VERSI CHECKBOX -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Bidang Keahlian <span class="text-red-500">*</span>
                            <span class="text-xs text-gray-400 font-normal ml-2">(Pilih minimal satu)</span>
                        </label>

                        @if($listBidangKeahlian->isEmpty())
                        <div class="bg-amber-50 border border-amber-200 rounded-xl p-4">
                            <p class="text-sm text-amber-700">
                                <svg class="w-5 h-5 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
                                Belum ada bidang keahlian tersedia untuk jurusan Anda. Silakan hubungi Kajur.
                            </p>
                        </div>
                        @else
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 max-h-60 overflow-y-auto p-2 border border-gray-200 rounded-xl">
                            @foreach($listBidangKeahlian as $bidang)
                            <label class="flex items-center space-x-3 p-3 border border-gray-200 rounded-xl cursor-pointer hover:bg-blue-50 hover:border-blue-300 transition {{ in_array($bidang->id, $selectedBidangKeahlian) ? 'bg-blue-50 border-blue-400 ring-1 ring-blue-400' : '' }}">
                                <input type="checkbox"
                                       wire:model="selectedBidangKeahlian"
                                       value="{{ $bidang->id }}"
                                       class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-900">{{ $bidang->nama_bidang }}</p>
                                    <p class="text-xs text-gray-500">{{ $bidang->kode }}</p>
                                    @if($bidang->deskripsi)
                                    <p class="text-xs text-gray-400 mt-0.5">{{ Str::limit($bidang->deskripsi, 60) }}</p>
                                    @endif
                                </div>
                                @if(in_array($bidang->id, $selectedBidangKeahlian))
                                <svg class="w-5 h-5 text-blue-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                @endif
                            </label>
                            @endforeach
                        </div>
                        @endif

                        @error('selectedBidangKeahlian') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror

                        <!-- Selected Count -->
                        @if(count($selectedBidangKeahlian) > 0)
                        <p class="mt-2 text-xs text-blue-600 font-medium">
                            {{ count($selectedBidangKeahlian) }} bidang keahlian dipilih
                        </p>
                        @endif
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Abstrak</label>
                        <textarea wire:model="abstrak" rows="4" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500" placeholder="Abstrak penelitian..."></textarea>
                    </div>
                </div>
            </div>

            <!-- Dosen Pembimbing -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Dosen Pembimbing</h2>
                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Pembimbing 1 <span class="text-red-500">*</span></label>
                        <select wire:model="dosen_pembimbing_1" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 @error('dosen_pembimbing_1') border-red-500 @enderror">
                            <option value="">Pilih Dosen</option>
                            @foreach($dosens as $dosen)
                            <option value="{{ $dosen->id }}" {{ $dosen->id == $dosen_pembimbing_2 ? 'disabled' : '' }}>{{ $dosen->name }} - {{ $dosen->nip }}</option>
                            @endforeach
                        </select>
                        @error('dosen_pembimbing_1') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Pembimbing 2</label>
                        <select wire:model="dosen_pembimbing_2" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 @error('dosen_pembimbing_2') border-red-500 @enderror">
                            <option value="">Pilih Dosen (Opsional)</option>
                            @foreach($dosens as $dosen)
                            <option value="{{ $dosen->id }}" {{ $dosen->id == $dosen_pembimbing_1 ? 'disabled' : '' }}>{{ $dosen->name }} - {{ $dosen->nip }}</option>
                            @endforeach
                        </select>
                        @error('dosen_pembimbing_2') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <!-- Upload Berkas -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Upload Berkas</h2>
                <p class="text-xs text-gray-500 mb-4">Format: PDF, Maks: 10MB (Proposal) / 20MB (Skripsi)</p>

                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">File Proposal/Skripsi</label>
                        <input type="file" wire:model="file_proposal" class="w-full text-sm border border-gray-300 rounded-xl file:mr-4 file:py-2 file:px-4 file:bg-blue-50 file:text-blue-700 file:border-0 file:rounded-lg hover:file:bg-blue-100">
                        @if($editMode && $existingFiles['file_proposal'] ?? false)
                            <p class="text-xs text-gray-500 mt-1">File existing: {{ basename($existingFiles['file_proposal']) }}</p>
                        @endif
                        @error('file_proposal') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    @if($jenis_ujian != 'seminar_proposal')
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">File Skripsi Lengkap</label>
                        <input type="file" wire:model="file_skripsi" class="w-full text-sm border border-gray-300 rounded-xl file:mr-4 file:py-2 file:px-4 file:bg-blue-50 file:text-blue-700 file:border-0 file:rounded-lg hover:file:bg-blue-100">
                        @error('file_skripsi') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    @endif

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Surat Persetujuan Pembimbing</label>
                        <input type="file" wire:model="file_persetujuan" class="w-full text-sm border border-gray-300 rounded-xl file:mr-4 file:py-2 file:px-4 file:bg-blue-50 file:text-blue-700 file:border-0 file:rounded-lg hover:file:bg-blue-100">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">KRS Terbaru</label>
                        <input type="file" wire:model="file_krs" class="w-full text-sm border border-gray-300 rounded-xl file:mr-4 file:py-2 file:px-4 file:bg-blue-50 file:text-blue-700 file:border-0 file:rounded-lg hover:file:bg-blue-100">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Transkrip Nilai</label>
                        <input type="file" wire:model="file_transkrip" class="w-full text-sm border border-gray-300 rounded-xl file:mr-4 file:py-2 file:px-4 file:bg-blue-50 file:text-blue-700 file:border-0 file:rounded-lg hover:file:bg-blue-100">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Bukti Bimbingan</label>
                        <input type="file" wire:model="file_bukti_bimbingan" class="w-full text-sm border border-gray-300 rounded-xl file:mr-4 file:py-2 file:px-4 file:bg-blue-50 file:text-blue-700 file:border-0 file:rounded-lg hover:file:bg-blue-100">
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end gap-4">
                <a href="{{ route('mahasiswa.pendaftaran.index') }}" class="px-6 py-2.5 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition font-medium">Batal</a>
                <button type="submit" class="px-6 py-2.5 bg-blue-700 text-white rounded-xl hover:bg-blue-800 transition font-medium" wire:loading.attr="disabled">
                    <span wire:loading.remove>{{ $editMode ? 'Perbarui' : 'Simpan' }} Pendaftaran</span>
                    <span wire:loading>Menyimpan...</span>
                </button>
            </div>
        </form>
    </div>
</div>

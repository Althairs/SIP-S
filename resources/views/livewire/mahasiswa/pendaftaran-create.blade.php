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
                    <span class="text-green-700 font-medium">{{ $editMode ? 'Edit' : 'Daftar Baru' }}</span>
                </li>
            </ol>
        </nav>

        <!-- Warning: Duplicate Registration -->
        @if(!$editMode && $hasExistingRegistration)
        <div class="mb-6 p-4 bg-amber-50 border border-amber-200 rounded-xl">
            <div class="flex items-start">
                <svg class="w-5 h-5 text-amber-600 mr-3 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
                <div>
                    <h4 class="text-sm font-semibold text-amber-800">Peringatan: Pendaftaran Aktif Ditemukan</h4>
                    <p class="text-sm text-amber-700 mt-1">
                        Anda sudah memiliki pendaftaran <strong>{{ $existingRegistrationType }}</strong>
                        dengan status <strong>{{ $existingRegistrationStatus }}</strong>.
                    </p>
                    <p class="text-sm text-amber-700 mt-1">
                        @if(in_array($existingRegistrationStatus, ['pending', 'disetujui_panitia', 'disetujui_sekjur', 'disetujui_kajur', 'dijadwalkan']))
                            Mohon tunggu proses verifikasi selesai atau hubungi panitia.
                        @elseif($existingRegistrationStatus === 'revisi')
                            Silakan perbaiki revisi yang diminta sebelum mendaftar ulang.
                        @elseif($existingRegistrationStatus === 'draft')
                            Silakan lanjutkan atau selesaikan pendaftaran yang sedang dalam draft.
                        @endif
                    </p>
                    <div class="mt-3">
                        <a href="{{ route('mahasiswa.pendaftaran.index') }}"
                           class="inline-flex items-center px-4 py-2 bg-amber-600 text-white text-sm font-medium rounded-lg hover:bg-amber-700 transition">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            Kembali ke Daftar Pendaftaran
                        </a>
                        @if($existingRegistrationStatus === 'draft')
                        <a href="{{ route('mahasiswa.pendaftaran.edit', $existingRegistrationId) }}"
                           class="inline-flex items-center px-4 py-2 ml-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Lanjutkan Draft
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endif

        @if($isSuperAdmin)
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-2">Akses Super Admin: Pilih Mahasiswa</h2>
        <p class="text-xs text-gray-500 mb-4">Silakan pilih mahasiswa untuk mendaftarkan ujian atau melihat bidang keahlian terkait.</p>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Mahasiswa <span class="text-red-500">*</span></label>
            <select wire:model.live="selectedMahasiswaId"
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500"
                    {{ $editMode ? 'disabled' : '' }}>
                <option value="">-- Pilih Mahasiswa --</option>
                @foreach($listMahasiswa as $mhs)
                    <option value="{{ $mhs->id }}">{{ $mhs->name }} (NIM: {{ $mhs->nim }})</option>
                @endforeach
            </select>
            @error('form.mahasiswa_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>
    </div>
    @endif

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
                        <select wire:model.live="form.jenis_ujian"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 @error('form.jenis_ujian') border-red-500 @enderror"
                                {{ $editMode ? 'disabled' : '' }}
                                {{ !$editMode && $hasExistingRegistration ? 'disabled' : '' }}>
                            <option value="">Pilih Jenis Ujian</option>
                            <option value="seminar_proposal">Seminar Proposal</option>
                            <option value="seminar_hasil">Seminar Hasil</option>
                            <option value="sidang_skripsi">Sidang Skripsi</option>
                        </select>
                        @error('form.jenis_ujian') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        @if(!$editMode && $hasExistingRegistration)
                        <p class="mt-1 text-sm text-amber-600">
                            <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            Jenis ujian ini tidak dapat dipilih karena Anda memiliki pendaftaran aktif.
                        </p>
                        @endif
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Judul Penelitian <span class="text-red-500">*</span></label>
                        <input type="text" wire:model="form.judul_penelitian"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 @error('form.judul_penelitian') border-red-500 @enderror"
                               placeholder="Masukkan judul penelitian"
                               {{ !$editMode && $hasExistingRegistration ? 'disabled' : '' }}>
                        @error('form.judul_penelitian') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
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
                            @if($isSuperAdmin && !$jurusan_id)
                                Belum ada bidang keahlian aktif secara global. Silakan pilih mahasiswa terlebih dahulu untuk memfilter bidang berdasarkan Jurusan.
                            @else
                                Belum ada bidang keahlian tersedia untuk jurusan ini. Silakan hubungi Kajur.
                            @endif
                        </p>
                    </div>
                    @else
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 max-h-60 overflow-y-auto p-2 border border-gray-200 rounded-xl">
                        @foreach($listBidangKeahlian as $bidang)
                        <label class="flex items-center space-x-3 p-3 border border-gray-200 rounded-xl cursor-pointer hover:bg-green-50 hover:border-green-300 transition {{ in_array($bidang->id, $form->selectedBidangKeahlian) ? 'bg-green-50 border-green-400 ring-1 ring-green-400' : '' }}">
                            <input type="checkbox"
                                   wire:model="form.selectedBidangKeahlian"
                                   value="{{ $bidang->id }}"
                                   class="w-4 h-4 text-green-600 border-gray-300 rounded focus:ring-green-500">
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-900">{{ $bidang->nama_bidang }}</p>
                                <p class="text-xs text-gray-500">{{ $bidang->kode }} @if($isSuperAdmin) <span class="text-gray-400">({{ $bidang->jurusan?->nama_jurusan ?? 'Global' }})</span> @endif</p>
                            </div>
                        </label>
                        @endforeach
                    </div>
                    @endif
                    @error('form.selectedBidangKeahlian') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Abstrak</label>
                        <textarea wire:model="form.abstrak" rows="4"
                                  class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500"
                                  placeholder="Abstrak penelitian..."
                                  {{ !$editMode && $hasExistingRegistration ? 'disabled' : '' }}></textarea>
                    </div>
                </div>
            </div>

            <!-- Dosen Pembimbing -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Dosen Pembimbing</h2>
                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Pembimbing 1 <span class="text-red-500">*</span></label>
                        <select wire:model.live="form.dosen_pembimbing_1"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 @error('form.dosen_pembimbing_1') border-red-500 @enderror"
                                {{ !$editMode && $hasExistingRegistration ? 'disabled' : '' }}>
                            <option value="">Pilih Dosen</option>
                            @foreach($dosens as $dosen)
                            <option value="{{ $dosen->id }}" {{ $dosen->id == $form->dosen_pembimbing_2 ? 'disabled' : '' }}>{{ $dosen->name }} - {{ $dosen->nip }}</option>
                            @endforeach
                        </select>
                        @error('form.dosen_pembimbing_1') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Pembimbing 2</label>
                        <select wire:model.live="form.dosen_pembimbing_2"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 @error('form.dosen_pembimbing_2') border-red-500 @enderror"
                                {{ !$editMode && $hasExistingRegistration ? 'disabled' : '' }}>
                            <option value="">Pilih Dosen (Opsional)</option>
                            @foreach($dosens as $dosen)
                            <option value="{{ $dosen->id }}" {{ $dosen->id == $form->dosen_pembimbing_1 ? 'disabled' : '' }}>{{ $dosen->name }} - {{ $dosen->nip }}</option>
                            @endforeach
                        </select>
                        @error('form.dosen_pembimbing_2') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
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
                        <input type="file" wire:model="form.file_proposal"
                               class="w-full text-sm border border-gray-300 rounded-xl file:mr-4 file:py-2 file:px-4 file:bg-green-50 file:text-green-700 file:border-0 file:rounded-lg hover:file:bg-green-100"
                               {{ !$editMode && $hasExistingRegistration ? 'disabled' : '' }}>
                        @if($editMode && $form->existingFiles['file_proposal'] ?? false)
                            <p class="text-xs text-gray-500 mt-1">File existing: {{ basename($form->existingFiles['file_proposal']) }}</p>
                        @endif
                        @error('form.file_proposal') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    @if($form->jenis_ujian != 'seminar_proposal')
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">File Skripsi Lengkap</label>
                        <input type="file" wire:model="form.file_skripsi"
                               class="w-full text-sm border border-gray-300 rounded-xl file:mr-4 file:py-2 file:px-4 file:bg-green-50 file:text-green-700 file:border-0 file:rounded-lg hover:file:bg-green-100"
                               {{ !$editMode && $hasExistingRegistration ? 'disabled' : '' }}>
                        @error('form.file_skripsi') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    @endif

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Surat Persetujuan Pembimbing</label>
                        <input type="file" wire:model="form.file_persetujuan"
                               class="w-full text-sm border border-gray-300 rounded-xl file:mr-4 file:py-2 file:px-4 file:bg-green-50 file:text-green-700 file:border-0 file:rounded-lg hover:file:bg-green-100"
                               {{ !$editMode && $hasExistingRegistration ? 'disabled' : '' }}>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">KRS Terbaru</label>
                        <input type="file" wire:model="form.file_krs"
                               class="w-full text-sm border border-gray-300 rounded-xl file:mr-4 file:py-2 file:px-4 file:bg-green-50 file:text-green-700 file:border-0 file:rounded-lg hover:file:bg-green-100"
                               {{ !$editMode && $hasExistingRegistration ? 'disabled' : '' }}>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Transkrip Nilai</label>
                        <input type="file" wire:model="form.file_transkrip"
                               class="w-full text-sm border border-gray-300 rounded-xl file:mr-4 file:py-2 file:px-4 file:bg-green-50 file:text-green-700 file:border-0 file:rounded-lg hover:file:bg-green-100"
                               {{ !$editMode && $hasExistingRegistration ? 'disabled' : '' }}>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Bukti Bimbingan</label>
                        <input type="file" wire:model="form.file_bukti_bimbingan"
                               class="w-full text-sm border border-gray-300 rounded-xl file:mr-4 file:py-2 file:px-4 file:bg-green-50 file:text-green-700 file:border-0 file:rounded-lg hover:file:bg-green-100"
                               {{ !$editMode && $hasExistingRegistration ? 'disabled' : '' }}>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end gap-4">
                <a href="{{ route('mahasiswa.pendaftaran.index') }}" class="px-6 py-2.5 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition font-medium">Batal</a>
                <button type="submit"
                        class="px-6 py-2.5 bg-green-700 text-white rounded-xl hover:bg-green-800 transition font-medium disabled:opacity-50 disabled:cursor-not-allowed"
                        wire:loading.attr="disabled"
                        {{ !$editMode && $hasExistingRegistration ? 'disabled' : '' }}>
                    <span wire:loading.remove>{{ $editMode ? 'Perbarui' : 'Simpan' }} Pendaftaran</span>
                    <span wire:loading>Menyimpan...</span>
                </button>
            </div>
        </form>
    </div>
</div>

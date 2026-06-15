<div>
    @section('title', 'Data Dosen')
    @section('page-title', 'Data Dosen')

    @if (session()->has('success'))
        <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-800 rounded-xl flex items-center"
            x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd"
                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                    clip-rule="evenodd" />
            </svg>
            {{ session('success') }}
        </div>
    @endif

    @if (session()->has('info'))
        <div class="mb-4 p-4 bg-blue-50 border border-blue-200 text-blue-800 rounded-xl flex items-center"
            x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd"
                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                    clip-rule="evenodd" />
            </svg>
            {{ session('info') }}
        </div>
    @endif

    <!-- Action Bar -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div class="flex-1 flex flex-col md:flex-row gap-3">
                <div class="relative flex-1">
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari dosen..."
                        class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                    <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <select wire:model.change="prodiFilter"
                    class="px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                    <option value="">Semua Prodi</option>
                    @foreach($prodis as $prodi)
                        <option value="{{ $prodi->id }}">{{ $prodi->nama_prodi }}</option>
                    @endforeach
                </select>
                <select wire:model.change="statusFilter"
                    class="px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                    <option value="">Semua Status</option>
                    <option value="1">Aktif</option>
                    <option value="0">Nonaktif</option>
                </select>
            </div>
            <div class="flex items-center gap-3">
                @if($canCreate)
                    <button wire:click="openCreateModal"
                        class="px-5 py-2.5 bg-emerald-700 text-white rounded-xl hover:bg-emerald-800 transition font-medium flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Tambah Dosen
                    </button>
                @endif
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-4 text-left text-xs font-semibold text-gray-500 uppercase">No</th>
                        <th class="px-4 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Dosen</th>
                        <th class="px-4 py-4 text-left text-xs font-semibold text-gray-500 uppercase">NIP</th>
                        <th class="px-4 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Prodi</th>
                        <th class="px-4 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Kepakaran</th>
                        <th class="px-4 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Bidang Keahlian
                        </th>
                        <th class="px-4 py-4 text-center text-xs font-semibold text-gray-500 uppercase">Status</th>
                        <th class="px-4 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($dosens as $index => $dosen)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-4 text-sm text-gray-700">{{ $dosens->firstItem() + $index }}</td>
                            <td class="px-4 py-4">
                                <div class="flex items-center space-x-3">
                                    <img class="w-10 h-10 rounded-full"
                                        src="https://ui-avatars.com/api/?name={{ urlencode($dosen->name) }}&background=059669&color=fff"
                                        alt="">
                                    <div>
                                        <p class="font-medium text-gray-900 text-sm">{{ $dosen->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $dosen->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-4 text-sm text-gray-700">{{ $dosen->nip ?? '-' }}</td>
                            <td class="px-4 py-4">
                                <span
                                    class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-medium whitespace-nowrap">{{ $dosen->prodi?->nama_prodi ?? '-' }}</span>
                            </td>
                            <td class="px-4 py-4">
                                @if($dosen->kepakaran)
                                    <span
                                        class="px-2 py-1 bg-purple-100 text-purple-800 rounded-full text-xs font-medium whitespace-nowrap">{{ $dosen->kepakaran->nama_kepakaran }}</span>
                                    <span class="block text-xs text-gray-400 mt-0.5 ">Level
                                        {{ $dosen->kepakaran->hierarki_level }}</span>
                                @else
                                    <span class="text-xs text-gray-400">Belum diatur</span>
                                @endif
                            </td>
                            <td class="px-4 py-4">
                                @if($dosen->bidangKeahlians->count() > 0)
                                    <div class="flex flex-wrap gap-1 max-w-[180px]">
                                        @foreach($dosen->bidangKeahlians->take(2) as $bk)
                                            <span
                                                class="px-2 py-0.5 bg-teal-100 text-teal-800 rounded-full text-xs font-medium whitespace-nowrap">{{ $bk->nama_bidang }}</span>
                                        @endforeach
                                        @if($dosen->bidangKeahlians->count() > 2)
                                            <span
                                                class="px-2 py-0.5 bg-gray-100 text-gray-600 rounded-full text-xs font-medium whitespace-nowrap">+{{ $dosen->bidangKeahlians->count() - 2 }}</span>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-xs text-gray-400">Belum diatur</span>
                                @endif
                            </td>
                            <td class="px-4 py-4 text-center">
                                <button wire:click="toggleStatus({{ $dosen->id }})"
                                    class="px-3 py-1 rounded-full text-xs font-medium {{ $dosen->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">{{ $dosen->is_active ? 'Aktif' : 'Nonaktif' }}</button>
                            </td>
                            <td class="px-4 py-4">
                                <div class="flex items-center gap-2">
                                    @if($canEdit)
                                        <button wire:click="openEditModal({{ $dosen->id }})"
                                            class="px-3 py-1.5 bg-amber-50 text-amber-700 rounded-lg hover:bg-amber-100 text-sm font-medium">Edit</button>
                                    @endif
                                    @if ($canDelete)
                                        <button wire:click="deleteDosen({{ $dosen->id }})" wire:confirm="Hapus dosen ini?"
                                            class="px-3 py-1.5 bg-red-50 text-red-700 rounded-lg hover:bg-red-100 text-sm font-medium">Hapus</button>
                                    @endif
                                    @if(!$canEdit && !$canDelete)
                                        <span class="text-xs text-gray-400">Read only</span>
                                    @endif

                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-8 text-center text-gray-500">Tidak ada data dosen</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-100">{{ $dosens->links() }}</div>
    </div>

    <!-- Modal Create/Edit -->
    @if($showModal)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4 py-8">
                <div class="fixed inset-0 bg-black/50" wire:click="closeModal"></div>
                <div class="relative bg-white rounded-3xl shadow-2xl max-w-2xl w-full p-6 max-h-[90vh] overflow-y-auto">
                    <div class="flex items-center justify-between mb-4 sticky top-0 bg-white z-10 pb-4 border-b">
                        <h3 class="text-xl font-bold text-gray-900">{{ $editMode ? 'Edit Dosen' : 'Tambah Dosen Baru' }}
                        </h3>
                        <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600"><svg class="w-6 h-6"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg></button>
                    </div>

                    <form wire:submit="save">
                        <div class="space-y-6">
                            <!-- Data Dasar -->
                            <div>
                                <h4 class="text-sm font-semibold text-gray-500 uppercase mb-3">Data Dasar</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama <span
                                                class="text-red-500">*</span></label>
                                        <input type="text" wire:model="name"
                                            class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 @error('name') border-red-500 @enderror">
                                        @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Email <span
                                                class="text-red-500">*</span></label>
                                        <input type="email" wire:model="email"
                                            class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 @error('email') border-red-500 @enderror">
                                        @error('email') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">NIP <span
                                                class="text-red-500">*</span></label>
                                        <input type="text" wire:model="nip"
                                            class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 @error('nip') border-red-500 @enderror">
                                        @error('nip') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Program Studi</label>
                                        <select wire:model="prodi_id"
                                            class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500">
                                            <option value="">Pilih Prodi</option>
                                            @foreach($prodis as $prodi)
                                                <option value="{{ $prodi->id }}">{{ $prodi->nama_prodi }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                @if(!$editMode)
                                    <div class="grid grid-cols-2 gap-4 mt-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Password <span
                                                    class="text-red-500">*</span></label>
                                            <input type="password" wire:model="password"
                                                class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 @error('password') border-red-500 @enderror">
                                            @error('password') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi
                                                Password</label>
                                            <input type="password" wire:model="password_confirmation"
                                                class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500">
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <!-- Kepakaran & Bidang Keahlian -->
                            <div class="border-t pt-6">
                                <h4 class="text-sm font-semibold text-gray-500 uppercase mb-3">Kepakaran & Bidang Keahlian
                                </h4>

                                <!-- Kepakaran -->
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Kepakaran (Hierarki
                                        Dosen)</label>
                                    <select wire:model="kepakaran_id"
                                        class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 ">
                                        <option value="">Pilih Kepakaran</option>
                                        @foreach($listKepakaran as $k)
                                            <option value="{{ $k->id }}">{{ $k->nama_kepakaran }} (Level
                                                {{ $k->hierarki_level }})
                                            </option>
                                        @endforeach
                                    </select>
                                    <p class="text-xs text-gray-400 mt-1">
                                        <a href="{{ route('kajur.data-master.kepakaran') }}" target="_blank"
                                            class="text-purple-600 hover:underline">+ Kelola Kepakaran</a>
                                    </p>
                                </div>

                                <!-- Bidang Keahlian -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Bidang Keahlian <span class="text-xs text-gray-400 font-normal">(Pilih yang
                                            sesuai)</span>
                                    </label>
                                    @if($listBidangKeahlian->isEmpty())
                                        <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 text-sm text-amber-700">
                                            Belum ada bidang keahlian. <a
                                                href="{{ route('kajur.data-master.bidang-keahlian') }}" target="_blank"
                                                class="text-amber-800 underline">Tambah bidang keahlian</a>
                                        </div>
                                    @else
                                        <div
                                            class="grid grid-cols-1 md:grid-cols-2 gap-2 max-h-48 overflow-y-auto p-2 border border-gray-200 rounded-xl">
                                            @foreach($listBidangKeahlian as $bidang)
                                                <label
                                                    class="flex items-center space-x-2 p-2 border border-gray-200 rounded-lg cursor-pointer hover:bg-teal-50 hover:border-teal-300 transition {{ in_array($bidang->id, $selectedBidangKeahlian) ? 'bg-teal-50 border-teal-400' : '' }}">
                                                    <input type="checkbox" wire:model="selectedBidangKeahlian"
                                                        value="{{ $bidang->id }}"
                                                        class="w-4 h-4 text-teal-600 border-gray-300 rounded focus:ring-teal-500">
                                                    <span
                                                        class="text-sm {{ in_array($bidang->id, $selectedBidangKeahlian) ? 'font-medium text-teal-800' : 'text-gray-700' }}">{{ $bidang->nama_bidang }}</span>
                                                </label>
                                            @endforeach
                                        </div>
                                    @endif
                                    <p class="text-xs text-gray-400 mt-1">
                                        <a href="{{ route('kajur.data-master.bidang-keahlian') }}" target="_blank"
                                            class="text-teal-600 hover:underline">+ Kelola Bidang Keahlian</a>
                                    </p>
                                    @error('selectedBidangKeahlian') <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Kontak -->
                            <div class="border-t pt-6">
                                <h4 class="text-sm font-semibold text-gray-500 uppercase mb-3">Kontak & Lainnya</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Nomor HP</label>
                                        <input type="text" wire:model="nomor_hp"
                                            class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500">
                                    </div>
                                    <div class="flex items-center pt-6">
                                        <input type="checkbox" wire:model="is_active"
                                            class="w-4 h-4 text-emerald-700 border-gray-300 rounded focus:ring-emerald-500">
                                        <label class="ml-2 text-sm text-gray-700">Akun Aktif</label>
                                    </div>
                                </div>
                                <div class="mt-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                                    <textarea wire:model="alamat" rows="2"
                                        class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500"></textarea>
                                </div>
                            </div>

                            @if($editMode)
                                <div class="border-t pt-6">
                                    <h4 class="text-sm font-semibold text-gray-500 uppercase mb-3">Ubah Password (Opsional)</h4>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Password Baru</label>
                                            <input type="password" wire:model="password"
                                                class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500"
                                                placeholder="Kosongkan jika tidak diubah">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi
                                                Password</label>
                                            <input type="password" wire:model="password_confirmation"
                                                class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500">
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="mt-8 flex items-center justify-end gap-4 border-t pt-4">
                            <button type="button" wire:click="closeModal"
                                class="px-6 py-2.5 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 font-medium">Batal</button>
                            <button type="submit"
                                class="px-6 py-2.5 bg-emerald-700 text-white rounded-xl hover:bg-emerald-800 font-medium">{{ $editMode ? 'Perbarui' : 'Simpan' }}
                                Dosen</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>

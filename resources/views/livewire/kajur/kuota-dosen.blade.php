<div>
    @section('title', 'Kuota Dosen')
    @section('page-title', 'Pengaturan Kuota Dosen')

    @if (session()->has('success'))
    <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-800 rounded-xl flex items-center" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)">
        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
        {{ session('success') }}
    </div>
    @endif

    <!-- Breadcrumb -->
    <nav class="mb-6 text-sm text-gray-500">
        <ol class="flex items-center space-x-2">
            <li><a href="{{ route('kajur.data-master.kuota-dosen') }}" class="hover:text-rose-600 transition">Data Master</a></li>
            <li><span class="mx-1">/</span></li>
            @if($showForm)
            <li><a href="{{ route('kajur.data-master.kuota-dosen') }}" class="hover:text-rose-600 transition">Kuota Dosen</a></li>
            <li><span class="mx-1">/</span></li>
            <li class="text-rose-700 font-medium">Edit</li>
            @else
            <li class="text-rose-700 font-medium">Kuota Dosen</li>
            @endif
        </ol>
    </nav>

    @if($showForm)
    <!-- Inline Edit Form -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-lg font-bold text-gray-900">Edit Kuota Dosen</h3>
                <p class="text-sm text-gray-500 mt-1">Perbarui kuota pembimbing dan penguji untuk dosen ini</p>
            </div>
            <button wire:click="closeForm" class="text-gray-400 hover:text-gray-600 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <div class="mb-6 p-4 bg-rose-50 border border-rose-100 rounded-xl">
            <p class="font-medium text-gray-900">{{ $editNama }}</p>
            <p class="text-xs text-gray-500 mt-0.5">NIP: {{ $editNip }}</p>
        </div>

        <form wire:submit="saveKuota">
            <div class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kuota Pembimbing</label>
                        <input type="number" wire:model="editKuotaPembimbing" min="1" max="50"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-rose-500 @error('editKuotaPembimbing') border-red-500 @enderror">
                        @error('editKuotaPembimbing') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kuota Penguji</label>
                        <input type="number" wire:model="editKuotaPenguji" min="1" max="50"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-rose-500 @error('editKuotaPenguji') border-red-500 @enderror">
                        @error('editKuotaPenguji') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="bg-gray-50 rounded-xl p-3">
                    <p class="text-xs text-gray-500">Terpakai Saat Ini:</p>
                    <div class="grid grid-cols-2 gap-4 mt-1">
                        <p class="text-sm">Pembimbing: <span class="font-bold text-rose-700">{{ $editTerpakaiPembimbing }}</span></p>
                        <p class="text-sm">Penguji: <span class="font-bold text-rose-700">{{ $editTerpakaiPenguji }}</span></p>
                    </div>
                </div>
            </div>

            <div class="mt-6 flex justify-end gap-4">
                <button type="button" wire:click="closeForm" class="px-6 py-2.5 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 font-medium">Batal</button>
                <button type="submit" class="px-6 py-2.5 bg-rose-700 text-white rounded-xl hover:bg-rose-800 font-medium">Simpan Kuota</button>
            </div>
        </form>
    </div>

    @else
    <!-- List Mode -->
    <!-- Info Card -->
    <div class="bg-gradient-to-r from-rose-600 to-rose-800 rounded-2xl p-6 mb-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold">Pengaturan Kuota Dosen</h2>
                <p class="text-rose-100 mt-1">Kelola kuota pembimbing dan penguji untuk setiap dosen</p>
            </div>
            <div class="hidden md:block">
                <svg class="w-16 h-16 text-rose-300 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7h12m0 0-4-4m4 4-4 4m0 6H4m0 0 4 4m-4-4 4-4"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- Default Kuota Bulanan -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
        <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-4">
            <div>
                <h3 class="text-lg font-semibold text-gray-900">Default Kuota Bulanan</h3>
                <p class="text-sm text-gray-500 mt-1">Kuota otomatis direset setiap awal bulan. Default saat ini: 20/bulan (dapat diubah).</p>
                @if($jurusan?->kuota_last_reset_at)
                    <p class="text-xs text-gray-400 mt-1">Reset terakhir: {{ $jurusan->kuota_last_reset_at->translatedFormat('d F Y H:i') }}</p>
                @endif
            </div>
            <form wire:submit="saveDefaultKuota" class="flex flex-col sm:flex-row gap-3 items-end">
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Default Pembimbing</label>
                    <input type="number" wire:model="defaultKuotaPembimbing" min="1" max="50"
                           class="w-full sm:w-28 px-3 py-2 border border-gray-300 rounded-xl text-sm">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Default Penguji</label>
                    <input type="number" wire:model="defaultKuotaPenguji" min="1" max="50"
                           class="w-full sm:w-28 px-3 py-2 border border-gray-300 rounded-xl text-sm">
                </div>
                <button type="submit" class="px-4 py-2 bg-rose-700 text-white rounded-xl hover:bg-rose-800 text-sm font-medium whitespace-nowrap">Simpan Default</button>
            </form>
        </div>
    </div>

    <!-- Filter -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 mb-6">
        <div class="flex flex-col md:flex-row gap-3">
            <div class="relative flex-1">
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari dosen..."
                       class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-rose-500">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
            <select wire:model.live="prodiFilter" class="px-4 py-2.5 border border-gray-300 rounded-xl">
                <option value="">Semua Prodi</option>
                @foreach($prodis as $prodi)
                <option value="{{ $prodi->id }}">{{ $prodi->nama_prodi }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- Legend -->
    <div class="flex flex-wrap gap-4 mb-4 text-sm">
        <span class="flex items-center"><span class="w-3 h-3 bg-green-500 rounded-full mr-1"></span> Sisa Kuota Aman (≥ 50%)</span>
        <span class="flex items-center"><span class="w-3 h-3 bg-amber-500 rounded-full mr-1"></span> Sisa Kuota Terbatas (< 50%)</span>
        <span class="flex items-center"><span class="w-3 h-3 bg-red-500 rounded-full mr-1"></span> Kuota Penuh/Overload</span>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-4 text-left text-xs font-semibold text-gray-500 uppercase">No</th>
                        <th class="px-4 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Dosen</th>
                        <th class="px-4 py-4 text-center text-xs font-semibold text-gray-500 uppercase" colspan="3">Pembimbing</th>
                        <th class="px-4 py-4 text-center text-xs font-semibold text-gray-500 uppercase" colspan="3">Penguji</th>
                        <th class="px-4 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Aksi</th>
                    </tr>
                    <tr class="bg-gray-50">
                        <th></th>
                        <th></th>
                        <th class="px-2 py-2 text-center text-xs text-gray-400">Kuota</th>
                        <th class="px-2 py-2 text-center text-xs text-gray-400">Terpakai</th>
                        <th class="px-2 py-2 text-center text-xs text-gray-400">Sisa</th>
                        <th class="px-2 py-2 text-center text-xs text-gray-400">Kuota</th>
                        <th class="px-2 py-2 text-center text-xs text-gray-400">Terpakai</th>
                        <th class="px-2 py-2 text-center text-xs text-gray-400">Sisa</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($dosens as $index => $dosen)
                    @php
                        $kuota = $dosen->kuota;
                        $sisaPembimbing = $kuota ? $kuota->sisa_pembimbing : ($jurusan?->default_kuota_pembimbing ?? 20);
                        $sisaPenguji = $kuota ? $kuota->sisa_penguji : ($jurusan?->default_kuota_penguji ?? 20);

                        $pembimbingClass = $sisaPembimbing >= 2.5 ? 'green' : ($sisaPembimbing > 0 ? 'amber' : 'red');
                        $pengujiClass = $sisaPenguji >= 5 ? 'green' : ($sisaPenguji > 0 ? 'amber' : 'red');
                    @endphp
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-4 text-sm">{{ $dosens->firstItem() + $index }}</td>
                        <td class="px-4 py-4">
                            <div class="flex items-center space-x-3">
                                <img class="w-10 h-10 rounded-full" src="https://ui-avatars.com/api/?name={{ urlencode($dosen->name) }}&background=e11d48&color=fff" alt="">
                                <div>
                                    <p class="font-medium text-gray-900 text-sm">{{ $dosen->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $dosen->nip }}</p>
                                </div>
                            </div>
                        </td>
                        <!-- Pembimbing -->
                        <td class="px-2 py-4 text-center text-sm font-medium">{{ $kuota?->kuota_pembimbing ?? ($jurusan?->default_kuota_pembimbing ?? 20) }}</td>
                        <td class="px-2 py-4 text-center text-sm">{{ $kuota?->terpakai_pembimbing ?? 0 }}</td>
                        <td class="px-2 py-4 text-center">
                            <span class="inline-flex items-center justify-center w-10 h-6 bg-{{ $pembimbingClass }}-100 text-{{ $pembimbingClass }}-800 rounded-full text-xs font-bold">
                                {{ $sisaPembimbing }}
                            </span>
                        </td>
                        <!-- Penguji -->
                        <td class="px-2 py-4 text-center text-sm font-medium">{{ $kuota?->kuota_penguji ?? ($jurusan?->default_kuota_penguji ?? 20) }}</td>
                        <td class="px-2 py-4 text-center text-sm">{{ $kuota?->terpakai_penguji ?? 0 }}</td>
                        <td class="px-2 py-4 text-center">
                            <span class="inline-flex items-center justify-center w-10 h-6 bg-{{ $pengujiClass }}-100 text-{{ $pengujiClass }}-800 rounded-full text-xs font-bold">
                                {{ $sisaPenguji }}
                            </span>
                        </td>
                        <td class="px-4 py-4">
                            <div class="flex items-center gap-2">
                                <button wire:click="openEdit({{ $dosen->id }})" class="px-3 py-1.5 bg-rose-50 text-rose-700 rounded-lg hover:bg-rose-100 text-sm font-medium">Edit Kuota</button>
                                <button wire:click="resetKuota({{ $dosen->id }})" wire:confirm="Reset kuota ke default?" class="px-3 py-1.5 bg-gray-50 text-gray-600 rounded-lg hover:bg-gray-100 text-sm">Reset</button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="9" class="px-6 py-8 text-center text-gray-500">Tidak ada data dosen</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t">{{ $dosens->links() }}</div>
    </div>
    @endif
</div>

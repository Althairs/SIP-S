<div>
    @section('title', 'Atur Kepakaran & Bidang Keahlian Dosen')
    @section('page-title', 'Atur Kepakaran & Bidang Keahlian Dosen')

    @if (session()->has('success'))
    <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-800 rounded-xl flex items-center" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)">
        <svg class="w-5 h-5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
        <span>{!! session('success') !!}</span>
    </div>
    @endif

    @if (session()->has('error'))
    <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-800 rounded-xl flex items-center" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)">
        <svg class="w-5 h-5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
        {{ session('error') }}
    </div>
    @endif

    <!-- Stats Cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100">
            <p class="text-xs text-gray-500">Total Dosen</p>
            <p class="text-2xl font-bold text-gray-900">{{ $totalDosen }}</p>
        </div>
        <div class="bg-white rounded-2xl p-4 shadow-sm border border-purple-100">
            <p class="text-xs text-gray-500">Punya Kepakaran</p>
            <p class="text-2xl font-bold text-purple-700">{{ $dosenDenganKepakaran }}</p>
        </div>
        <div class="bg-white rounded-2xl p-4 shadow-sm border border-teal-100">
            <p class="text-xs text-gray-500">Punya Bidang</p>
            <p class="text-2xl font-bold text-teal-700">{{ $dosenDenganBidang }}</p>
        </div>
        <div class="bg-white rounded-2xl p-4 shadow-sm border {{ $dosenBelumDiatur > 0 ? 'border-amber-100' : 'border-green-100' }}">
            <p class="text-xs text-gray-500">Belum Diatur</p>
            <p class="text-2xl font-bold {{ $dosenBelumDiatur > 0 ? 'text-amber-700' : 'text-green-700' }}">{{ $dosenBelumDiatur }}</p>
        </div>
    </div>

    <!-- Quick Assign Panel -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 mb-6">
        <div class="flex items-center justify-between mb-3">
            <h3 class="text-sm font-semibold text-gray-700">Quick Assign (Massal)</h3>
            <button wire:click="$toggle('quickMode')" class="text-sm {{ $quickMode ? 'text-amber-700' : 'text-cyan-600' }} hover:underline font-medium">
                {{ $quickMode ? 'Tutup Panel' : 'Buka Panel' }}
            </button>
        </div>

        @if($quickMode)
        <div class="border-t pt-4">
            <p class="text-xs text-gray-500 mb-3">Pilih dosen dengan centang di bawah, lalu pilih kepakaran/bidang untuk diterapkan massal.</p>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Kepakaran</label>
                    <select wire:model="quickKepakaran" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                        <option value="">-- Pilih Kepakaran --</option>
                        @foreach($listKepakaran as $k)
                        <option value="{{ $k->id }}">{{ $k->nama_kepakaran }} (Lv.{{ $k->hierarki_level }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Bidang Keahlian</label>
                    <div class="max-h-32 overflow-y-auto border border-gray-300 rounded-lg p-2">
                        @foreach($listBidangKeahlian as $bk)
                        <label class="flex items-center space-x-2 py-1 text-sm">
                            <input type="checkbox" wire:model="quickBidangKeahlian" value="{{ $bk->id }}" class="w-3.5 h-3.5 text-teal-600 rounded">
                            <span>{{ $bk->nama_bidang }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>
                <div class="flex items-end">
                    <button wire:click="applyQuickAssign" class="w-full px-4 py-2 bg-cyan-700 text-white rounded-lg hover:bg-cyan-800 text-sm font-medium {{ count($selectedDosenIds) == 0 ? 'opacity-50 cursor-not-allowed' : '' }}" {{ count($selectedDosenIds) == 0 ? 'disabled' : '' }}>
                        Terapkan ke {{ count($selectedDosenIds) }} Dosen
                    </button>
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Filter -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
            <div class="relative">
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari nama atau NIP..."
                       class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
            <select wire:model.change="kepakaranFilter" class="px-4 py-2.5 pr-10 border border-gray-300 rounded-xl appearance-none cursor-pointer bg-white">
                <option value="">Semua Kepakaran</option>
                @foreach($listKepakaran as $k)
                <option value="{{ $k->id }}">{{ $k->nama_kepakaran }}</option>
                @endforeach
                <option value="null">Belum Diatur</option>
            </select>
            <select wire:model.change="bidangFilter" class="px-4 py-2.5 pr-10 border border-gray-300 rounded-xl appearance-none cursor-pointer bg-white">
                <option value="">Semua Bidang Keahlian</option>
                @foreach($listBidangKeahlian as $bk)
                <option value="{{ $bk->id }}">{{ $bk->nama_bidang }}</option>
                @endforeach
                <option value="null">Belum Diatur</option>
            </select>
        </div>
    </div>

    <!-- Batch Select Bar -->
    @if($quickMode)
    <div class="bg-cyan-50 border border-cyan-200 rounded-xl p-3 mb-4 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <input type="checkbox" wire:model.live="selectAll" class="w-4 h-4 text-cyan-600 border-gray-300 rounded focus:ring-cyan-500">
            <span class="text-sm text-cyan-800">Pilih Semua ({{ $totalDosen }})</span>
            @if(count($selectedDosenIds) > 0)
            <span class="text-sm font-medium text-cyan-700">{{ count($selectedDosenIds) }} terpilih</span>
            @endif
        </div>
    </div>
    @endif

    <!-- Dosen Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
        @forelse($dosens as $dosen)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition">
            <!-- Header -->
            <div class="flex items-start justify-between mb-4">
                <div class="flex items-center space-x-3">
                    @if($quickMode)
                    <input type="checkbox" wire:model.live="selectedDosenIds" value="{{ $dosen->id }}" class="w-4 h-4 text-cyan-600 border-gray-300 rounded focus:ring-cyan-500 flex-shrink-0">
                    @endif
                    <img class="w-12 h-12 rounded-full flex-shrink-0" src="https://ui-avatars.com/api/?name={{ urlencode($dosen->name) }}&background=7c3aed&color=fff" alt="">
                    <div class="min-w-0">
                        <p class="font-semibold text-gray-900 text-sm truncate">{{ $dosen->name }}</p>
                        <p class="text-xs text-gray-500">{{ $dosen->nip ?? 'No NIP' }}</p>
                        @if($dosen->prodi)
                        <span class="px-2 py-0.5 bg-blue-100 text-blue-800 rounded-full text-xs mt-1 inline-block">{{ $dosen->prodi->nama_prodi }}</span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Kepakaran -->
            <div class="mb-3">
                <p class="text-xs text-gray-400 mb-1">Kepakaran</p>
                @if($dosen->kepakaran)
                <span class="px-2 py-1 bg-purple-100 text-purple-800 rounded-full text-xs font-medium">
                    {{ $dosen->kepakaran->nama_kepakaran }}
                    <span class="text-purple-500 ml-1">Lv.{{ $dosen->kepakaran->hierarki_level }}</span>
                </span>
                @else
                <span class="px-2 py-1 bg-red-50 text-red-600 rounded-full text-xs border border-red-200">Belum diatur</span>
                @endif
            </div>

            <!-- Bidang Keahlian -->
            <div class="mb-4">
                <p class="text-xs text-gray-400 mb-1">Bidang Keahlian</p>
                @if($dosen->bidangKeahlians->count() > 0)
                <div class="flex flex-wrap gap-1">
                    @foreach($dosen->bidangKeahlians as $bk)
                    <span class="px-2 py-0.5 bg-teal-100 text-teal-800 rounded-full text-xs">{{ $bk->nama_bidang }}</span>
                    @endforeach
                </div>
                @else
                <span class="px-2 py-1 bg-red-50 text-red-600 rounded-full text-xs border border-red-200">Belum diatur</span>
                @endif
            </div>

            <!-- Actions -->
            <div class="flex items-center gap-2 pt-3 border-t">
                <button wire:click="openEditModal({{ $dosen->id }})" class="flex-1 px-3 py-2 bg-purple-700 text-white rounded-lg hover:bg-purple-800 text-xs font-medium text-center transition">
                    <svg class="w-3.5 h-3.5 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    Atur Atribut
                </button>
                <button wire:click="resetAtribut({{ $dosen->id }})" wire:confirm="Reset atribut dosen ini?" class="px-3 py-2 bg-gray-50 text-gray-600 rounded-lg hover:bg-gray-100 text-xs font-medium transition">
                    Reset
                </button>
            </div>
        </div>
        @empty
        <div class="col-span-full bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center">
            <div class="w-20 h-20 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-10 h-10 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </div>
            <p class="text-gray-500">Tidak ada dosen ditemukan.</p>
        </div>
        @endforelse
    </div>

    <div class="mt-4">{{ $dosens->links() }}</div>

    {{-- ============= MODAL: ATUR ATRIBUT ============= --}}
    @if($showModal)
    <div class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 py-8">
            <div class="fixed inset-0 bg-black/50" wire:click="closeModal"></div>
            <div class="relative bg-white rounded-3xl shadow-2xl max-w-lg w-full p-6">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-xl font-bold text-gray-900">Atur Atribut Dosen</h3>
                        <p class="text-sm text-gray-500 mt-1">{{ $editDosenNama }} ({{ $editDosenNip }})</p>
                    </div>
                    <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600 bg-gray-50 rounded-full p-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <form wire:submit="saveAtribut">
                    <!-- Kepakaran (Dropdown) -->
                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <svg class="w-4 h-4 inline mr-1 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                            Kepakaran
                        </label>
                        <select wire:model="selectedKepakaran" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 text-sm">
                            <option value="">-- Belum Ditentukan --</option>
                            @foreach($listKepakaran as $k)
                            <option value="{{ $k->id }}">{{ $k->nama_kepakaran }} (Level {{ $k->hierarki_level }})</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Bidang Keahlian (Custom Checkbox) -->
                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <svg class="w-4 h-4 inline mr-1 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                            Bidang Keahlian
                            <span class="text-xs text-gray-400 font-normal">(pilih yang sesuai)</span>
                        </label>
                        @if($listBidangKeahlian->isEmpty())
                        <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 text-sm text-amber-700">
                            Belum ada bidang keahlian. <a href="{{ route('kajur.data-master.bidang-keahlian') }}" target="_blank" class="text-amber-800 underline">Tambah sekarang</a>
                        </div>
                        @else
                        <div class="grid grid-cols-2 gap-2 max-h-52 overflow-y-auto p-1">
                            @foreach($listBidangKeahlian as $bk)
                            <label class="flex items-center space-x-2 p-2.5 border rounded-xl cursor-pointer transition {{ in_array($bk->id, $selectedBidangKeahlian) ? 'border-teal-500 bg-teal-50' : 'border-gray-200 hover:border-gray-300' }}"
                                   wire:click="toggleBidang({{ $bk->id }})">
                                <div class="w-4 h-4 rounded border-2 flex items-center justify-center flex-shrink-0 {{ in_array($bk->id, $selectedBidangKeahlian) ? 'bg-teal-500 border-teal-500' : 'border-gray-300' }}">
                                    @if(in_array($bk->id, $selectedBidangKeahlian))
                                    <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                    @endif
                                </div>
                                <div class="text-sm">
                                    <p class="{{ in_array($bk->id, $selectedBidangKeahlian) ? 'font-medium text-teal-800' : 'text-gray-700' }}">{{ $bk->nama_bidang }}</p>
                                    <p class="text-xs text-gray-400">{{ $bk->kode }}</p>
                                </div>
                            </label>
                            @endforeach
                        </div>
                        @endif
                        <p class="text-xs text-gray-400 mt-1">
                            <a href="{{ route('kajur.data-master.bidang-keahlian') }}" target="_blank" class="text-teal-600 hover:underline">+ Kelola Bidang Keahlian</a>
                        </p>
                    </div>

                    <div class="flex justify-end gap-3 pt-4 border-t">
                        <button type="button" wire:click="closeModal" class="px-6 py-2.5 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 font-medium">Batal</button>
                        <button type="submit" class="px-6 py-2.5 bg-purple-700 text-white rounded-xl hover:bg-purple-800 font-medium shadow-sm shadow-purple-200">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Simpan Atribut
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>

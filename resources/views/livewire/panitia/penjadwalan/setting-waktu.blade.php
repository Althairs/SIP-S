<div>
    @section('title', 'Pengaturan Waktu & Sesi')
    @section('page-title', 'Pengaturan Waktu & Sesi Ujian')

    @if (session()->has('success'))
    <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-800 rounded-xl flex items-center" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)">
        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
        {{ session('success') }}
    </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-lg font-semibold text-gray-900">Konfigurasi Sesi Ujian</h2>
                <p class="text-sm text-gray-500">Atur jam mulai dan selesai untuk setiap sesi ujian</p>
            </div>
            <div class="flex gap-3">
                <button wire:click="resetToDefault" wire:confirm="Reset ke default?" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 text-sm font-medium">Reset Default</button>
                <button wire:click="save" class="px-4 py-2 bg-green-700 text-white rounded-xl hover:bg-green-800 text-sm font-medium">Simpan</button>
            </div>
        </div>

        <form wire:submit="save">
            <div class="space-y-4">
                @foreach($jamMulai as $index => $value)
                <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-xl">
                    <span class="text-sm font-bold text-gray-500 w-6">{{ $index + 1 }}</span>

                    <div class="flex-1">
                        <label class="block text-xs text-gray-500 mb-1">Label Sesi</label>
                        <input type="text" wire:model="labelSesi.{{ $index }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-green-500" placeholder="Sesi {{ $index + 1 }}">
                    </div>

                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Jam Mulai</label>
                        <input type="time" wire:model="jamMulai.{{ $index }}" class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-green-500">
                    </div>

                    <span class="text-gray-400">s/d</span>

                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Jam Selesai</label>
                        <input type="time" wire:model="jamSelesai.{{ $index }}" class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-green-500">
                    </div>

                    <button type="button" wire:click="removeSesi({{ $index }})" class="text-red-500 hover:text-red-700 p-2" title="Hapus sesi">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    </button>
                </div>
                @endforeach

                @error('jamMulai.*') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                @error('jamSelesai.*') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <button type="button" wire:click="addSesi" class="mt-4 px-4 py-2 bg-green-50 text-green-700 border border-green-200 rounded-xl hover:bg-green-100 text-sm font-medium flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Tambah Sesi
            </button>
        </form>

        <!-- Preview -->
        <div class="mt-6 p-4 bg-green-50 rounded-xl">
            <h3 class="text-sm font-semibold text-green-800 mb-2">Preview Jadwal</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
                @foreach($jamMulai as $index => $value)
                <div class="bg-white rounded-lg p-2 text-center text-xs">
                    <p class="font-medium text-green-700">{{ $labelSesi[$index] ?? 'Sesi '.($index+1) }}</p>
                    <p class="text-gray-500">{{ $jamMulai[$index] ?? '-' }} - {{ $jamSelesai[$index] ?? '-' }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

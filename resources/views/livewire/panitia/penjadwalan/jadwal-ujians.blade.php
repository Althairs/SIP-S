{{-- Schedule Modal dengan Auto Generate --}}
@if($showScheduleModal && $selectedPendaftaran)
<div class="fixed inset-0 z-50 overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-black/50" wire:click="closeScheduleModal"></div>
        <div class="relative bg-white rounded-2xl shadow-xl max-w-lg w-full p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-gray-900">Atur Jadwal Ujian</h3>
                <button wire:click="closeScheduleModal" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <div class="mb-4 p-3 bg-gray-50 rounded-xl">
                <p class="text-sm font-medium">{{ $selectedPendaftaran->mahasiswa->name }}</p>
                <p class="text-xs text-gray-500">{{ Str::limit($selectedPendaftaran->judul_penelitian, 60) }}</p>
            </div>

            <!-- Mode: Auto Generate atau Manual -->
            <div class="flex gap-3 mb-4">
                <button type="button" wire:click="autoGenerateJadwal"
                        class="px-4 py-2 {{ $scheduleMode === 'auto' ? 'bg-cyan-700 text-white' : 'bg-gray-100 text-gray-700' }} rounded-xl text-sm font-medium transition">
                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0..."/></svg>
                    Auto Random (1-2 Minggu)
                </button>
                <span class="text-xs text-gray-400 self-center">atau atur manual di bawah</span>
            </div>

            <form wire:submit="scheduleUjian">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal & Waktu <span class="text-red-500">*</span></label>
                        <input type="datetime-local" wire:model="tanggal_ujian"
                               min="{{ Carbon\Carbon::now()->addWeek()->format('Y-m-d\T00:00') }}"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-cyan-500 @error('tanggal_ujian') border-red-500 @enderror">
                        <p class="text-xs text-gray-400 mt-1">Minimal 1 minggu dari sekarang</p>
                        @error('tanggal_ujian') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Ruangan <span class="text-red-500">*</span></label>
                        <select wire:model="ruangan" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-cyan-500 @error('ruangan') border-red-500 @enderror">
                            <option value="">Pilih Ruangan</option>
                            @foreach($ruanganOptions as $r)
                            <option value="{{ $r }}">{{ $r }}</option>
                            @endforeach
                        </select>
                        @error('ruangan') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Sesi <span class="text-red-500">*</span></label>
                        <select wire:model="sesi" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-cyan-500">
                            <option value="1">Sesi 1 (08:00 - 10:00)</option>
                            <option value="2">Sesi 2 (10:00 - 12:00)</option>
                            <option value="3">Sesi 3 (13:00 - 15:00)</option>
                            <option value="4">Sesi 4 (15:00 - 17:00)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Catatan</label>
                        <textarea wire:model="catatan" rows="2" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-cyan-500" placeholder="Catatan tambahan..."></textarea>
                    </div>
                </div>

                <div class="mt-6 flex justify-end gap-4">
                    <button type="button" wire:click="closeScheduleModal" class="px-6 py-2.5 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 font-medium">Batal</button>
                    <button type="submit" class="px-6 py-2.5 bg-cyan-700 text-white rounded-xl hover:bg-cyan-800 font-medium">Simpan Jadwal</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

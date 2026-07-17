<div>
    @section('title', 'Pengaturan Reminder')
    @section('page-title', 'Pengaturan Reminder Mahasiswa')

    @if (session()->has('success'))
    <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-800 rounded-xl flex items-center" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)">
        <svg class="w-5 h-5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
        {{ session('success') }}
    </div>
    @endif

    <!-- Tab Jenis Ujian -->
    <div class="flex border-b border-gray-200 mb-6 gap-1 overflow-x-auto">
        @foreach($jenisUjianOptions as $key => $label)
        <button wire:click="$set('jenisUjian', '{{ $key }}')"
                class="px-6 py-3 text-sm font-medium border-b-2 transition whitespace-nowrap {{ $jenisUjian === $key ? 'border-green-600 text-green-700' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
            {{ $label }}
            @if(isset($allSettings[$key]) && $allSettings[$key]->is_active)
            <span class="ml-1 text-green-500">●</span>
            @endif
        </button>
        @endforeach
    </div>

    <div class="grid lg:grid-cols-3 gap-6">
        <!-- Panel Kiri: Konfigurasi -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-bold text-gray-900">Konfigurasi: {{ $jenisUjianOptions[$jenisUjian] }}</h3>
                    <label class="flex items-center cursor-pointer">
                        <input type="checkbox" wire:model="isActive" class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:ring-2 peer-focus:ring-green-300 rounded-full peer peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-600 relative"></div>
                        <span class="ml-3 text-sm text-gray-600">{{ $isActive ? 'Aktif' : 'Nonaktif' }}</span>
                    </label>
                </div>

                <form wire:submit="save">
                    <!-- Deadline -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Deadline (Hari setelah ujian)</label>
                        <div class="flex items-center gap-3">
                            <input type="number" wire:model="deadlineDays" min="7" max="365"
                                   class="w-32 px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 @error('deadlineDays') border-red-500 @enderror">
                            <span class="text-sm text-gray-900">hari</span>
                        </div>
                        @error('deadlineDays') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <!-- Template Pesan -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Template Pesan</label>
                        <textarea wire:model="pesanTemplate" rows="3"
                                  class="w-full px-4 py-2.5 border border-gray-900 rounded-xl focus:ring-2 focus:ring-green-500 text-sm text-gray-900/100"
                                  placeholder="Template pesan..."></textarea>
                        <p class="text-xs text-gray-900 mt-1">
                            Variabel: <code>{jenis_ujian}</code> <code>{judul}</code> <code>{deadline}</code>
                        </p>
                    </div>

                    <!-- Daftar Reminder -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-3">Frekuensi Reminder</label>

                        @if(count($reminders) > 0)
                        <div class="space-y-2 mb-3">
                            @foreach($reminders as $index => $reminder)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl">
                                <div class="flex items-center gap-3">
                                    <span class="w-8 h-8 {{ $reminder['type'] === 'daily' ? 'bg-green-100 text-green-700' : 'bg-amber-100 text-amber-700' }} rounded-full flex items-center justify-center">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    </span>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ $reminder['label'] }}</p>
                                        <p class="text-xs text-gray-500">
                                            @if($reminder['type'] === 'daily')
                                            Setiap {{ $reminder['interval'] }} hari sekali
                                            @else
                                            {{ $reminder['days'] }} hari sebelum deadline
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                <button wire:click="removeReminder({{ $index }})" class="text-red-500 hover:text-red-700 p-1">✕</button>
                            </div>
                            @endforeach
                        </div>
                        @endif

                        @error('reminders') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror

                        <!-- Tambah Reminder -->
                        <div class="p-4 border-2 border-dashed border-gray-300 rounded-xl">
                            <p class="text-sm font-medium text-gray-700 mb-3">Tambah Reminder Baru</p>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                <div>
                                    <label class="block text-xs text-gray-500 mb-1">Tipe</label>
                                    <select wire:model="newReminderType" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                                        <option value="daily">Berkala (per X hari)</option>
                                        <option value="before_deadline">Menjelang Deadline</option>
                                    </select>
                                </div>

                                @if($newReminderType === 'daily')
                                <div>
                                    <label class="block text-xs text-gray-500 mb-1">Interval (hari)</label>
                                    <input type="number" wire:model="newReminderInterval" min="1" max="30" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                                </div>
                                @else
                                <div>
                                    <label class="block text-xs text-gray-500 mb-1">Hari Sebelum Deadline</label>
                                    <select wire:model="newReminderDays" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                                        <option value="14">H-14</option>
                                        <option value="7">H-7</option>
                                        <option value="5">H-5</option>
                                        <option value="3">H-3</option>
                                        <option value="1">H-1</option>
                                    </select>
                                </div>
                                @endif

                                <div class="flex items-end">
                                    <button type="button" wire:click="addReminder" class="w-full px-4 py-2 bg-green-700 text-white rounded-lg hover:bg-green-800 text-sm font-medium">
                                        + Tambah
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 pt-4 border-t">
                        <button type="button" wire:click="generateReminders" class="px-4 py-2.5 bg-amber-50 text-amber-700 border border-amber-200 rounded-xl hover:bg-amber-100 text-sm font-medium">
                            Generate Reminder Sekarang
                        </button>
                        <button type="submit" class="px-6 py-2.5 bg-green-700 text-white rounded-xl hover:bg-green-800 font-medium">
                            Simpan Pengaturan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Panel Kanan: Preview & Info -->
        <div>
            <!-- Preview -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
                <h3 class="text-sm font-semibold text-gray-700 mb-4 flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                    Preview Timeline
                </h3>
                <div class="space-y-3">
                    <div class="flex items-start space-x-3">
                        <div class="w-2 h-2 bg-green-500 rounded-full mt-2"></div>
                        <div>
                            <p class="text-sm font-medium">Ujian Selesai</p>
                            <p class="text-xs text-gray-500">Hari ke-0</p>
                        </div>
                    </div>
                    @foreach($reminders as $reminder)
                    <div class="flex items-start space-x-3">
                        <div class="w-2 h-2 {{ $reminder['type'] === 'daily' ? 'bg-green-500' : 'bg-amber-500' }} rounded-full mt-2"></div>
                        <div>
                            <p class="text-sm font-medium">{{ $reminder['label'] }}</p>
                            <p class="text-xs text-gray-500">
                                @if($reminder['type'] === 'daily')
                                Setiap {{ $reminder['interval'] }} hari
                                @else
                                {{ $deadlineDays - $reminder['days'] }} hari setelah ujian
                                @endif
                            </p>
                        </div>
                    </div>
                    @endforeach
                    <div class="flex items-start space-x-3">
                        <div class="w-2 h-2 bg-red-500 rounded-full mt-2"></div>
                        <div>
                            <p class="text-sm font-medium text-red-700">Deadline</p>
                            <p class="text-xs text-gray-500">Hari ke-{{ $deadlineDays }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistik -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-sm font-semibold text-gray-700 mb-4 flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                    Statistik
                </h3>
                @php
                    $jurusanId = auth()->user()->jurusan_id;
                    $totalReminders = \App\Models\Reminder::whereHas('pendaftaran', function($q) use ($jurusanId) {
                        $q->where('jurusan_id', $jurusanId);
                    })->count();
                    $unreadReminders = \App\Models\Reminder::whereHas('pendaftaran', function($q) use ($jurusanId) {
                        $q->where('jurusan_id', $jurusanId);
                    })->unread()->active()->count();
                @endphp
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-500">Total Reminder</span>
                        <span class="text-sm font-bold">{{ $totalReminders }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-500">Belum Dibaca</span>
                        <span class="text-sm font-bold text-amber-600">{{ $unreadReminders }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

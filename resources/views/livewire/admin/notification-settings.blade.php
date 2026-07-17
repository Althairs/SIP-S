<div>
    @section('title', 'Pengaturan Notifikasi WhatsApp')
    @section('page-title', 'Pengaturan Notifikasi WhatsApp')

    <div class="max-w-4xl mx-auto">
        @if (session()->has('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-800 rounded-xl flex items-center">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
            </svg>
            {{ session('success') }}
        </div>
        @endif

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Pengaturan Gateway</h2>
            
            <form wire:submit="save">
                <div class="space-y-6">
                    <!-- Enable/Disable -->
                    <div class="flex items-center">
                        <input type="checkbox" id="whatsapp_enabled" wire:model="whatsapp_enabled" class="w-5 h-5 text-green-600 rounded border-gray-300 focus:ring-green-500">
                        <label for="whatsapp_enabled" class="ml-3 block text-sm font-medium text-gray-700">Aktifkan Notifikasi WhatsApp Sistem</label>
                    </div>

                    <!-- Provider Selection -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Provider Aktif</label>
                        <div class="flex space-x-4">
                            <label class="flex items-center">
                                <input type="radio" wire:model.live="whatsapp_provider" value="fonnte" class="text-green-600 focus:ring-green-500">
                                <span class="ml-2 text-sm text-gray-700">Fonnte</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" wire:model.live="whatsapp_provider" value="netflie" class="text-green-600 focus:ring-green-500">
                                <span class="ml-2 text-sm text-gray-700">Netflie (Meta Cloud API)</span>
                            </label>
                        </div>
                    </div>

                    <!-- Fonnte Config -->
                    @if($whatsapp_provider === 'fonnte')
                    <div class="p-4 bg-gray-50 rounded-xl border border-gray-200">
                        <h3 class="text-sm font-semibold text-gray-800 mb-3">Konfigurasi Fonnte</h3>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">API Token</label>
                            <input type="password" wire:model="whatsapp_fonnte_token" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500 text-sm">
                            <p class="text-xs text-gray-500 mt-1">Dapatkan token ini dari dashboard Fonnte.</p>
                        </div>
                    </div>
                    @endif

                    <!-- Netflie Config -->
                    @if($whatsapp_provider === 'netflie')
                    <div class="p-4 bg-gray-50 rounded-xl border border-gray-200 space-y-4">
                        <h3 class="text-sm font-semibold text-gray-800 mb-2">Konfigurasi Netflie (Meta Cloud API)</h3>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Access Token</label>
                            <input type="password" wire:model="whatsapp_netflie_token" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500 text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Phone Number ID</label>
                            <input type="text" wire:model="whatsapp_netflie_phone_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500 text-sm">
                        </div>
                    </div>
                    @endif

                    <div class="pt-4 border-t border-gray-100 flex justify-end">
                        <button type="submit" class="px-6 py-2.5 bg-green-700 text-white rounded-xl hover:bg-green-800 font-medium text-sm transition shadow-sm shadow-green-200">
                            Simpan Pengaturan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

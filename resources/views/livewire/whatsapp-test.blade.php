<div>
    <!-- Test WhatsApp Notification Card -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <h4 class="text-lg font-semibold text-gray-900 inline-flex items-center gap-2">
                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                Test WhatsApp Notification
            </h4>
        </div>
        <div class="p-6">
            <!-- Status Konfigurasi -->
            <div class="p-4 mb-4 rounded-lg {{ $settings['enabled'] == '1' ? 'bg-green-50 border border-green-200 text-green-800' : 'bg-red-50 border border-red-200 text-red-800' }}">
                <strong>Status: </strong>
                @if($settings['enabled'] == '1')
                    <span class="inline-flex items-center gap-1"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> WhatsApp Enabled (Provider: {{ $settings['provider'] }})</span>
                @else
                    <span class="inline-flex items-center gap-1"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg> WhatsApp Disabled</span>
                @endif
            </div>

            @if($settings['enabled'] == '1' && $settings['provider'] == 'fonnte' && empty($settings['fonnte_token']))
                <div class="p-4 mb-4 rounded-lg bg-yellow-50 border border-yellow-200 text-yellow-800 flex items-start gap-2">
                    <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
                    Token Fonnte belum dikonfigurasi. Silakan set di Settings.
                </div>
            @endif

            @if($settings['enabled'] == '1' && $settings['provider'] == 'netflie' && (empty($settings['netflie_token']) || empty($settings['netflie_phone_id'])))
                <div class="p-4 mb-4 rounded-lg bg-yellow-50 border border-yellow-200 text-yellow-800 flex items-start gap-2">
                    <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
                    Kredensial Netflie belum lengkap. Silakan set di Settings.
                </div>
            @endif

            <form wire:submit.prevent="sendMessage">
                <div class="grid grid-cols-1 gap-6">
                    <!-- Template Selection -->
                    <div class="col-span-full mb-3">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Template Pesan</label>
                        <div class="flex rounded-md shadow-sm">
                            <select class="flex-1 min-w-0 block w-full px-3 py-2 rounded-l-md border border-gray-300 bg-white text-gray-900 text-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                    wire:model="selectedTemplate"
                                    wire:change="selectTemplate($event.target.value)">
                                @foreach($templates as $template)
                                    <option value="{{ $template['name'] }}">{{ $template['name'] }}</option>
                                @endforeach
                            </select>
                            <button type="button"
                                    class="inline-flex items-center px-4 py-2 border border-l-0 border-gray-300 bg-gray-50 text-gray-700 text-sm font-medium hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-green-500"
                                    wire:click="applyTemplateToMessage">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/>
                                </svg>
                                Terapkan
                            </button>
                            <button type="button"
                                    class="inline-flex items-center px-4 py-2 rounded-r-md border border-l-0 border-gray-300 bg-green-600 text-white text-sm font-medium hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500"
                                    wire:click="saveTemplate">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M7.707 10.293a1 1 0 10-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 11.586V6h5a2 2 0 012 2v7a2 2 0 01-2 2H4a2 2 0 01-2-2V8a2 2 0 012-2h5v5.586l-1.293-1.293zM9 4a1 1 0 012 0v2H9V4z"/>
                                </svg>
                                Simpan
                            </button>
                        </div>
                    </div>

                    <!-- Phone Input & Quick Test -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">
                                Nomor Telepon <span class="text-red-500">*</span>
                            </label>
                            <div class="flex rounded-md shadow-sm">
                                <span class="inline-flex items-center px-3 py-2 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">
                                    +62
                                </span>
                                <input type="text"
                                       class="flex-1 min-w-0 block w-full px-3 py-2 rounded-r-md border border-gray-300 text-gray-900 text-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('phone') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror"
                                       id="phone"
                                       wire:model="phone"
                                       placeholder="8123456789"
                                       autocomplete="off">
                            </div>
                            @error('phone')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <small class="text-gray-500 text-xs mt-1">Format: 8123456789 (tanpa 0)</small>
                        </div>

                        <div class="flex items-end">
                            <button type="button"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-md text-gray-700 bg-white text-sm font-medium hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors"
                                    wire:click="sendTestNotification">
                                <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd"/>
                                </svg>
                                Isi Test Data
                            </button>
                        </div>
                    </div>

                    <!-- Message Input -->
                    <div>
                        <label for="message" class="block text-sm font-medium text-gray-700 mb-1">
                            Pesan <span class="text-red-500">*</span>
                        </label>
                        <textarea class="block w-full px-3 py-2 border border-gray-300 rounded-md text-gray-900 text-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 resize-none @error('message') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror"
                                  id="message"
                                  wire:model="message"
                                  rows="10"
                                  placeholder="Tulis pesan di sini..."></textarea>
                        @error('message')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <div class="flex justify-between mt-2">
                            <small class="text-gray-500">
                                <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                </svg>
                                Karakter: {{ strlen($message) }} |
                                Word: {{ str_word_count($message) }}
                            </small>
                            <small class="text-gray-500">
                                Template variables: {name}, {jenis_ujian}, {judul}, {tanggal}, {ruangan}, {sesi}
                            </small>
                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="flex gap-3">
                        <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                                wire:loading.attr="disabled"
                                wire:target="sendMessage">
                            <span wire:loading.remove wire:target="sendMessage">
                                <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z"/>
                                </svg>
                                Kirim Pesan
                            </span>
                            <span wire:loading wire:target="sendMessage" class="flex items-center">
                                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Mengirim...
                            </span>
                        </button>

                        <button type="reset"
                                class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-gray-700 hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors"
                                wire:click="$set('message', '')">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd"/>
                            </svg>
                            Reset
                        </button>
                    </div>
                </div>
            </form>

            <!-- Response Area -->
            @if($response)
                <div class="mt-6">
                    <div class="p-4 rounded-lg border {{ $success ? 'bg-green-50 border-green-200 text-green-800' : 'bg-red-50 border-red-200 text-red-800' }}">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                @if($success)
                                    <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                @else
                                    <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                    </svg>
                                @endif
                            </div>
                            <div class="ml-3 flex-1">
                                <h5 class="text-sm font-medium mb-1">
                                    {{ $success ? 'Sukses!' : 'Gagal!' }}
                                </h5>
                                <pre class="text-sm whitespace-pre-wrap break-words bg-transparent border-none p-0 max-h-48 overflow-y-auto font-mono">{{ $response }}</pre>
                            </div>
                            <button type="button"
                                    class="ml-auto flex-shrink-0 -mx-1.5 -my-1.5 rounded-lg p-1.5 inline-flex h-8 w-8 hover:bg-green-200 focus:outline-none"
                                    wire:click="$set('response', '')">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Template Manager Card -->
    <div class="mt-6 bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <h5 class="text-lg font-semibold text-gray-900 inline-flex items-center gap-2">
                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Template Manager
            </h5>
        </div>
        <div class="p-6">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Template</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Preview</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($templates as $index => $template)
                            <tr class="hover:bg-gray-50 transition-colors" x-data="{ open: false }">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $loop->iteration }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $template['name'] }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <button class="inline-flex items-center px-3 py-1.5 border border-green-300 rounded-md text-sm font-medium text-green-700 bg-white hover:bg-green-50 focus:outline-none focus:ring-2 focus:ring-green-500 transition-colors"
                                            @click="open = !open">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                                            <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                                        </svg>
                                        Preview
                                    </button>
                                    <div x-show="open"
                                         x-transition:enter="transition ease-out duration-200"
                                         x-transition:enter-start="opacity-0 transform scale-95"
                                         x-transition:enter-end="opacity-100 transform scale-100"
                                         class="mt-2">
                                        <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                                            <pre class="text-xs whitespace-pre-wrap break-words m-0 text-gray-700">{{ $template['message'] }}</pre>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <button class="inline-flex items-center px-3 py-1.5 border border-green-300 rounded-md text-sm font-medium text-green-700 bg-white hover:bg-green-50 focus:outline-none focus:ring-2 focus:ring-green-500 transition-colors"
                                            wire:click="selectTemplate('{{ $template['name'] }}')">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                        </svg>
                                        Pilih
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Konfigurasi WhatsApp Card -->
    <div class="mt-6 bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <h5 class="text-lg font-semibold text-gray-900 inline-flex items-center gap-2">
                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                Konfigurasi WhatsApp
            </h5>
        </div>
        <div class="p-6">
            <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-4 gap-y-3">
                <dt class="text-sm font-medium text-gray-500">Enabled</dt>
                <dd class="text-sm text-gray-900">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $settings['enabled'] == '1' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $settings['enabled'] == '1' ? 'Yes' : 'No' }}
                    </span>
                </dd>

                <dt class="text-sm font-medium text-gray-500">Provider</dt>
                <dd class="text-sm text-gray-900">{{ $settings['provider'] ?? 'Not set' }}</dd>

                @if($settings['provider'] == 'fonnte')
                    <dt class="text-sm font-medium text-gray-500">Fonnte Token</dt>
                    <dd class="text-sm">
                        @if(!empty($settings['fonnte_token']))
                            <span class="text-green-600">✓ Configured</span>
                        @else
                            <span class="text-red-600">✗ Not configured</span>
                        @endif
                    </dd>
                @endif

                @if($settings['provider'] == 'netflie')
                    <dt class="text-sm font-medium text-gray-500">Netflie Token</dt>
                    <dd class="text-sm">
                        @if(!empty($settings['netflie_token']))
                            <span class="text-green-600">✓ Configured</span>
                        @else
                            <span class="text-red-600">✗ Not configured</span>
                        @endif
                    </dd>
                    <dt class="text-sm font-medium text-gray-500">Phone ID</dt>
                    <dd class="text-sm">
                        @if(!empty($settings['netflie_phone_id']))
                            <span class="text-green-600">✓ Configured</span>
                        @else
                            <span class="text-red-600">✗ Not configured</span>
                        @endif
                    </dd>
                @endif
            </dl>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('livewire:load', function () {
            Livewire.on('alert', (data) => {
                Swal.fire({
                    icon: data.type,
                    title: data.type === 'success' ? 'Sukses!' : 'Gagal!',
                    text: data.message,
                    confirmButtonColor: data.type === 'success' ? '#16a34a' : '#dc2626',
                });
            });
        });
    </script>
    @endpush
</div>

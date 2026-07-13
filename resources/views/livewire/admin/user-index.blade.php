<div>
    @section('title', $onlyKajurSekjur ? 'Manajemen Kajur & Sekjur' : 'Manajemen Users')
    @section('page-title', $onlyKajurSekjur ? 'Daftar Kajur & Sekjur' : 'Daftar Semua Users')

    <!-- Alert Messages -->
    @if (session()->has('success'))
    <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-800 rounded-xl flex items-center shadow-sm">
        <svg class="w-5 h-5 mr-2 text-green-600" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
        </svg>
        <span class="font-medium">{!! session('success') !!}</span>
    </div>
    @endif

    @if (session()->has('error'))
    <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-800 rounded-xl flex items-center shadow-sm">
        <svg class="w-5 h-5 mr-2 text-red-600" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
        </svg>
        <span class="font-medium">{{ session('error') }}</span>
    </div>
    @endif

    <!-- Action Bar -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 mb-6">
        <div class="flex flex-col gap-4">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-3">
                <div class="relative flex-1">
                    <input type="text"
                           wire:model.live.debounce.300ms="search"
                           placeholder="{{ $onlyKajurSekjur ? 'Cari berdasarkan nama, email, atau NIP...' : 'Cari user...' }}"
                           class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-xl placeholder-gray-400 focus:ring-2 {{ $onlyKajurSekjur ? 'focus:ring-purple-500 focus:border-purple-500' : 'focus:ring-green-500 focus:border-green-500' }}">
                    <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <a href="{{ route('admin.users.create') }}" class="px-5 py-2.5 text-white rounded-xl transition font-medium flex items-center justify-center gap-2 whitespace-nowrap {{ $onlyKajurSekjur ? 'bg-purple-700 hover:bg-purple-800' : 'bg-green-700 hover:bg-green-800' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    {{ $onlyKajurSekjur ? 'Tambah Kajur/Sekjur' : 'Tambah User' }}
                </a>
            </div>

            @if(!$onlyKajurSekjur)
            <div class="flex flex-wrap gap-2 mb-4">
                <button type="button" wire:click="$set('roleFilter', '')" class="px-3 py-1.5 rounded-full border text-sm font-medium transition {{ $roleFilter === '' ? 'bg-green-100 border-green-300 text-green-700' : 'bg-white border-gray-200 text-gray-700 hover:bg-gray-50' }}">
                    Semua
                </button>
                <button type="button" wire:click="$set('roleFilter', 'kajur')" class="px-3 py-1.5 rounded-full border text-sm font-medium transition {{ $roleFilter === 'kajur' ? 'bg-purple-100 border-purple-300 text-purple-700' : 'bg-white border-gray-200 text-gray-700 hover:bg-gray-50' }}">
                    Kajur
                </button>
                <button type="button" wire:click="$set('roleFilter', 'sekjur')" class="px-3 py-1.5 rounded-full border text-sm font-medium transition {{ $roleFilter === 'sekjur' ? 'bg-purple-100 border-purple-300 text-purple-700' : 'bg-white border-gray-200 text-gray-700 hover:bg-gray-50' }}">
                    Sekjur
                </button>
            </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-3">
                <!-- Jurusan Filter -->
                <select wire:model.change="jurusanFilter" @class(['px-4 py-2.5 pr-10 border border-gray-300 rounded-xl focus:ring-2 appearance-none cursor-pointer bg-white', $onlyKajurSekjur ? 'focus:ring-purple-500 focus:border-purple-500' : 'focus:ring-green-500 focus:border-green-500', 'text-gray-900' => !$jurusanFilter, 'text-gray-700' => $jurusanFilter])>
                    <option value="">Semua Jurusan</option>
                    @foreach($jurusans as $jurusan)
                    <option value="{{ $jurusan->id }}">{{ $jurusan->nama_jurusan }}</option>
                    @endforeach
                </select>

                <!-- Prodi Filter (Hanya tampil di normal mode) -->
                @if(!$onlyKajurSekjur)
                <select wire:model.change="prodiFilter" @class(['px-4 py-2.5 pr-10 border border-gray-300 rounded-xl focus:ring-2 appearance-none cursor-pointer bg-white focus:ring-green-500 focus:border-green-500', 'text-gray-900' => !$prodiFilter, 'text-gray-700' => $prodiFilter])>
                    <option value="">Semua Program Studi</option>
                    @foreach($prodis as $prodi)
                    <option value="{{ $prodi->id }}">{{ $prodi->nama_prodi }}</option>
                    @endforeach
                </select>
                @endif

                <!-- Role Filter -->
                <select wire:model.change="roleFilter" @class(['px-4 py-2.5 pr-10 border border-gray-300 rounded-xl focus:ring-2 appearance-none cursor-pointer bg-white', $onlyKajurSekjur ? 'focus:ring-purple-500 focus:border-purple-500' : 'focus:ring-green-500 focus:border-green-500', 'text-gray-900' => !$roleFilter, 'text-gray-700' => $roleFilter])>
                    <option value="">{{ $onlyKajurSekjur ? 'Semua Role (Kajur/Sekjur)' : 'Semua Role' }}</option>
                    @foreach($roles as $role)
                    <option value="{{ $role->name }}">{{ ucfirst($role->name) }}</option>
                    @endforeach
                </select>

                <!-- Status Filter -->
                <select wire:model.change="statusFilter" @class(['px-4 py-2.5 pr-10 border border-gray-300 rounded-xl focus:ring-2 appearance-none cursor-pointer bg-white', $onlyKajurSekjur ? 'focus:ring-purple-500 focus:border-purple-500' : 'focus:ring-green-500 focus:border-green-500', 'text-gray-900' => $statusFilter === '', 'text-gray-700' => $statusFilter !== ''])>
                    <option value="">Semua Status</option>
                    <option value="1">Aktif</option>
                    <option value="0">Nonaktif</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">User</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">{{ $onlyKajurSekjur ? 'NIP' : 'NIP/NIM' }}</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Role</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">{{ $onlyKajurSekjur ? 'Jurusan' : 'Jurusan/Prodi' }}</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($users as $user)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="flex items-center space-x-3">
                                <img class="w-10 h-10 rounded-full"
                                     src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background={{ $onlyKajurSekjur ? '7c3aed' : '16a34a' }}&color=fff"
                                     alt="{{ $user->name }}">
                                <div>
                                    <p class="font-medium text-gray-900">{{ $user->name }}</p>
                                    <p class="text-sm text-gray-500">{{ $user->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-700">
                            {{ $user->nip ?? $user->nim ?? '-' }}
                        </td>
                        <td class="px-6 py-4">
                            @foreach($user->getRoleNames() as $role)
                            <span class="px-3 py-1 rounded-full text-xs font-medium capitalize {{ $onlyKajurSekjur ? 'bg-purple-100 text-purple-800' : 'bg-green-100 text-green-800' }}">
                                {{ $role }}
                            </span>
                            @endforeach
                        </td>
                        <td class="px-6 py-4">
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $user->jurusan->nama_jurusan ?? '-' }}</p>
                                @if(!$onlyKajurSekjur && $user->prodi)
                                <p class="text-xs text-gray-500">{{ $user->prodi->nama_prodi }}</p>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <button wire:click="toggleStatus({{ $user->id }})"
                                    class="px-3 py-1 rounded-full text-xs font-medium {{ $user->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                            </button>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.users.create') }}?edit={{ $user->id }}"
                                   class="px-3 py-1.5 bg-amber-50 text-amber-700 rounded-lg hover:bg-amber-100 transition text-sm font-medium">
                                    Edit
                                </a>
                                @unless($user->hasRole('super_admin'))
                                <button wire:click="deleteUser({{ $user->id }})"
                                        wire:confirm="Yakin ingin menghapus user ini?"
                                        class="px-3 py-1.5 bg-red-50 text-red-700 rounded-lg hover:bg-red-100 transition text-sm font-medium">
                                    Hapus
                                </button>
                                @endunless
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                            Tidak ada data {{ $onlyKajurSekjur ? 'kajur/sekjur' : 'user' }}
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $users->links() }}
        </div>
    </div>
</div>

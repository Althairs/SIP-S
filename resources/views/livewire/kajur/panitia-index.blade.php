<div>
    @section('title', 'Data Panitia')
    @section('page-title', 'Data Panitia')

    @if (session()->has('success'))
    <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-800 rounded-xl flex items-center" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)">
        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
        </svg>
        {{ session('success') }}
    </div>
    @endif

    @if($showForm)
    <!-- Breadcrumb (Form Mode) -->
    <nav class="mb-6 text-sm text-gray-500">
        <ol class="flex items-center gap-1.5">
            <li><a href="{{ route('kajur.data-master.panitia') }}" class="hover:text-green-700 transition">Data Master</a></li>
            <li><span class="mx-1">/</span></li>
            <li><a href="{{ route('kajur.data-master.panitia') }}" class="hover:text-green-700 transition">Data Panitia</a></li>
            <li><span class="mx-1">/</span></li>
            <li class="text-green-700 font-medium">{{ $editMode ? 'Edit' : 'Tambah' }}</li>
        </ol>
    </nav>

    <!-- Inline Form Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-semibold text-gray-900">{{ $editMode ? 'Edit Panitia' : 'Tambah Panitia' }}</h3>
            <button wire:click="closeForm" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        <form wire:submit="save">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama <span class="text-red-500">*</span></label>
                    <input type="text" wire:model="name" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('name') border-red-500 @enderror">
                    @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email <span class="text-red-500">*</span></label>
                    <input type="email" wire:model="email" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('email') border-red-500 @enderror">
                    @error('email') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Password {{ $editMode ? '' : '*' }}</label>
                        <input type="password" wire:model="password" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500" placeholder="{{ $editMode ? 'Kosongkan jika tidak diubah' : '' }}">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password</label>
                        <input type="password" wire:model="password_confirmation" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">NIP <span class="text-red-500">*</span></label>
                    <input type="text" wire:model="nip" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 placeholder-gray-400 @error('nip') border-red-500 @enderror" placeholder="Masukkan NIP">
                    @error('nip') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Role Panitia <span class="text-red-500">*</span></label>
                    <select wire:model="role" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 placeholder-gray-900 @error('role') border-red-500 @enderror {{ !$role ? 'text-gray-900' : 'text-gray-900' }}">
                        <option value="">Pilih Role Panitia</option>
                        <option value="panitia_verifikasi">Panitia Verifikasi</option>
                        <option value="panitia_penjadwalan">Panitia Penjadwalan</option>
                        <option value="panitia_administrasi">Panitia Administrasi</option>
                    </select>
                    @error('role') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nomor HP</label>
                    <input type="text" wire:model="nomor_hp" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 placeholder-gray-400" placeholder="Contoh: 081234567890">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                    <textarea wire:model="alamat" rows="2" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 placeholder-gray-400" placeholder="Alamat lengkap..."></textarea>
                </div>
                <div class="flex items-center">
                    <input type="checkbox" wire:model="is_active" class="w-4 h-4 text-green-700 border-gray-300 rounded focus:ring-green-500">
                    <label class="ml-2 text-sm text-gray-700">Akun Aktif</label>
                </div>
            </div>

            <div class="mt-6 flex items-center justify-end gap-4">
                <button type="button" wire:click="closeForm" class="px-6 py-2.5 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition font-medium">Batal</button>
                <button type="submit" class="px-6 py-2.5 bg-green-700 text-white rounded-xl hover:bg-green-800 transition font-medium">{{ $editMode ? 'Perbarui' : 'Simpan' }}</button>
            </div>
        </form>
    </div>

    @else

    <!-- Breadcrumb (List Mode) -->
    <nav class="mb-6 text-sm text-gray-500">
        <ol class="flex items-center gap-1.5">
            <li><a href="{{ route('kajur.data-master.panitia') }}" class="hover:text-green-700 transition">Data Master</a></li>
            <li><span class="mx-1">/</span></li>
            <li class="text-green-700 font-medium">Data Panitia</li>
        </ol>
    </nav>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
            <p class="text-sm text-gray-500">Total Panitia</p>
            <p class="text-3xl font-semibold text-gray-900 mt-3">{{ $summary['total'] ?? 0 }}</p>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
            <p class="text-sm text-gray-500">Verifikasi</p>
            <p class="text-3xl font-semibold text-amber-700 mt-3">{{ $summary['verifikasi'] ?? 0 }}</p>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
            <p class="text-sm text-gray-500">Penjadwalan</p>
            <p class="text-3xl font-semibold text-green-700 mt-3">{{ $summary['penjadwalan'] ?? 0 }}</p>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
            <p class="text-sm text-gray-500">Administrasi</p>
            <p class="text-3xl font-semibold text-green-700 mt-3">{{ $summary['administrasi'] ?? 0 }}</p>
        </div>
    </div>

    <!-- Action Bar -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div class="flex-1 flex flex-col md:flex-row gap-3">
                <div class="relative flex-1">
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari panitia..."
                           class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500">
                    <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <select wire:model.change="roleFilter" class="px-4 py-2.5 pr-10 border border-gray-300 rounded-xl focus:ring-2 appearance-none cursor-pointer bg-white focus:ring-green-500 focus:border-green-500">
                    <option value="">Semua Role</option>
                    <option value="panitia_verifikasi">Panitia Verifikasi</option>
                    <option value="panitia_penjadwalan">Panitia Penjadwalan</option>
                    <option value="panitia_administrasi">Panitia Administrasi</option>
                </select>
                <select wire:model.change="prodiFilter" class="px-4 py-2.5 pr-10 border border-gray-300 rounded-xl focus:ring-2 appearance-none cursor-pointer bg-white focus:ring-green-500 focus:border-green-500">
                    <option value="">Semua Prodi</option>
                    @foreach($prodis as $prodi)
                        <option value="{{ $prodi->id }}">{{ $prodi->nama_prodi }}</option>
                    @endforeach
                </select>
                <select wire:model.change="statusFilter" class="px-4 py-2.5 pr-10 border border-gray-300 rounded-xl focus:ring-2 appearance-none cursor-pointer bg-white focus:ring-green-500 focus:border-green-500">
                    <option value="">Semua Status</option>
                    <option value="1">Aktif</option>
                    <option value="0">Nonaktif</option>
                </select>
            </div>
            <button wire:click="openCreate" class="px-5 py-2.5 bg-green-700 text-white rounded-xl hover:bg-green-800 transition font-medium flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Tambah Panitia
            </button>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">No</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Panitia</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">NIP</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Role</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Kontak</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($panitias as $index => $panitia)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 text-sm text-gray-700">{{ $panitias->firstItem() + $index }}</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center space-x-3">
                                <img class="w-10 h-10 rounded-full" src="https://ui-avatars.com/api/?name={{ urlencode($panitia->name) }}&background=d97706&color=fff" alt="">
                                <div>
                                    <p class="font-medium text-gray-900">{{ $panitia->name }}</p>
                                    <p class="text-sm text-gray-500">{{ $panitia->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900">{{ $panitia->nip ?? '-' }}</td>
                        <td class="px-6 py-4">
                            @php
                                $roleName = $panitia->getRoleNames()->first();
                                $roleBadge = [
                                    'panitia_verifikasi' => 'bg-amber-100 text-amber-800',
                                    'panitia_penjadwalan' => 'bg-green-100 text-green-800',
                                    'panitia_administrasi' => 'bg-emerald-100 text-emerald-800',
                                ][$roleName] ?? 'bg-gray-900 text-gray-900';
                            @endphp
                            <span class="px-2 py-1 rounded-full text-xs font-medium {{ $roleBadge }} capitalize">
                                {{ str_replace('_', ' ', $roleName ?? '-') }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-700">{{ $panitia->nomor_hp ?? '-' }}</td>
                        <td class="px-6 py-4">
                            <button wire:click="toggleStatus({{ $panitia->id }})"
                                    class="px-3 py-1 rounded-full text-xs font-medium {{ $panitia->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $panitia->is_active ? 'Aktif' : 'Nonaktif' }}
                            </button>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <button wire:click="openEdit({{ $panitia->id }})"
                                        class="px-3 py-1.5 bg-amber-50 text-amber-700 rounded-lg hover:bg-amber-100 transition text-sm font-medium">
                                    Edit
                                </button>
                                <button wire:click="deletePanitia({{ $panitia->id }})"
                                        wire:confirm="Yakin ingin menghapus panitia ini?"
                                        class="px-3 py-1.5 bg-red-50 text-red-700 rounded-lg hover:bg-red-100 transition text-sm font-medium">
                                    Hapus
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-8 text-center text-gray-500">Tidak ada data panitia</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $panitias->links() }}
        </div>
    </div>

    @endif
</div>

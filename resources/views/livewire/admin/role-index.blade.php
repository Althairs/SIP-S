<div>
    @section('title', 'Manajemen Role & Akses')
    @section('page-title', 'Daftar Role & Permissions')

    <!-- Alert Messages -->
    @if (session()->has('success'))
    <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-800 rounded-xl flex items-center">
        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
        </svg>
        {{ session('success') }}
    </div>
    @endif

    @if (session()->has('error'))
    <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-800 rounded-xl flex items-center">
        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
        </svg>
        {{ session('error') }}
    </div>
    @endif

    <!-- Action Bar -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div class="relative flex-1">
                <input type="text"
                       wire:model.live.debounce.300ms="search"
                       placeholder="Cari role..."
                       class="w-full md:w-96 pl-10 pr-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-rose-500 focus:border-rose-500">
                <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
            <a href="{{ route('admin.roles.create') }}" class="px-5 py-2.5 bg-rose-700 text-white rounded-xl hover:bg-rose-800 transition font-medium flex items-center gap-2 whitespace-nowrap">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Tambah Role
            </a>
        </div>
    </div>

    <!-- Info Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Total Role</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $roles->total() }}</p>
                </div>
                <div class="w-12 h-12 bg-rose-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-rose-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Total Permissions</p>
                    <p class="text-3xl font-bold text-gray-900">{{ \Spatie\Permission\Models\Permission::count() }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Users Terassign</p>
                    <p class="text-3xl font-bold text-gray-900">{{ \App\Models\User::has('roles')->count() }}</p>
                </div>
                <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-amber-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">No</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Role</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Permissions</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Users</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Guard</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($roles as $index => $role)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 text-sm text-gray-700">{{ $roles->firstItem() + $index }}</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center space-x-2">
                                <span class="px-3 py-1 bg-rose-100 text-rose-800 rounded-full text-xs font-medium capitalize">
                                    {{ str_replace('_', ' ', $role->name) }}
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-wrap gap-1">
                                @forelse($role->permissions->take(3) as $permission)
                                <span class="px-2 py-0.5 bg-green-100 text-green-800 rounded-full text-xs">
                                    {{ str_replace('_', ' ', $permission->name) }}
                                </span>
                                @empty
                                <span class="text-xs text-gray-400">Tidak ada permission</span>
                                @endforelse
                                @if($role->permissions->count() > 3)
                                <span class="px-2 py-0.5 bg-gray-100 text-gray-600 rounded-full text-xs">
                                    +{{ $role->permissions->count() - 3 }} lagi
                                </span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 bg-amber-100 text-amber-800 rounded-full text-xs font-medium">
                                {{ $role->users_count }} user
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $role->guard_name }}</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.roles.edit', $role->id) }}"
                                   class="px-3 py-1.5 bg-amber-50 text-amber-700 rounded-lg hover:bg-amber-100 transition text-sm font-medium">
                                    Edit
                                </a>
                                @if($role->name !== 'super_admin')
                                <button wire:click="deleteRole({{ $role->id }})"
                                        wire:confirm="Yakin ingin menghapus role ini?"
                                        class="px-3 py-1.5 bg-red-50 text-red-700 rounded-lg hover:bg-red-100 transition text-sm font-medium">
                                    Hapus
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                            Tidak ada data role
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $roles->links() }}
        </div>
    </div>

    <!-- Permissions List -->
    <div class="mt-6 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <h2 class="text-lg font-semibold text-gray-900">Daftar Permissions Tersedia</h2>
        </div>
        <div class="p-6">
            @php
                $permissionsGrouped = \Spatie\Permission\Models\Permission::all()->groupBy(function($permission) {
                    return explode('_', $permission->name)[1] ?? $permission->name;
                });
            @endphp

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($permissionsGrouped as $group => $permissions)
                <div class="border border-gray-200 rounded-xl p-4">
                    <h3 class="text-sm font-semibold text-gray-500 uppercase mb-3">{{ ucfirst($group) }}</h3>
                    <div class="space-y-2">
                        @foreach($permissions as $permission)
                        <div class="flex items-center text-sm text-gray-700">
                            <svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            {{ str_replace('_', ' ', $permission->name) }}
                        </div>
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

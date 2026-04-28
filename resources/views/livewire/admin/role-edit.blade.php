<div>
    @section('title', 'Edit Role')
    @section('page-title', 'Edit Role: ' . str_replace('_', ' ', $role->name))

    <div class="max-w-4xl mx-auto">
        <!-- Breadcrumb -->
        <nav class="flex mb-6" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('admin.dashboard') }}" class="text-gray-500 hover:text-gray-700">Dashboard</a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-4 h-4 text-gray-400 mx-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <a href="{{ route('admin.roles.index') }}" class="text-gray-500 hover:text-gray-700">Role & Akses</a>
                    </div>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-4 h-4 text-gray-400 mx-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="text-rose-700 font-medium">Edit</span>
                    </div>
                </li>
            </ol>
        </nav>

        <!-- Info -->
        @if($roleUsersCount > 0)
        <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 mb-6">
            <p class="text-sm text-amber-800">
                <strong>{{ $roleUsersCount }} user</strong> menggunakan role ini.
                Perubahan permissions akan langsung berlaku untuk semua user tersebut.
            </p>
        </div>
        @endif

        <form wire:submit="update">
            <!-- Role Name -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Informasi Role</h2>
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                        Nama Role <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                           id="name"
                           wire:model="name"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-rose-500 focus:border-rose-500 @error('name') border-red-500 @enderror"
                           {{ $role->name === 'super_admin' ? 'readonly' : '' }}>
                    @if($role->name === 'super_admin')
                        <p class="mt-1 text-xs text-amber-600">Nama role Super Admin tidak dapat diubah.</p>
                    @endif
                    @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>

            <!-- Permissions -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Permissions</h2>
                <p class="text-sm text-gray-500 mb-6">Pilih permissions yang akan diberikan ke role ini.</p>

                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($allPermissions as $subject => $permissions)
                    <div class="border border-gray-200 rounded-xl p-4">
                        <h3 class="text-sm font-semibold text-gray-500 uppercase mb-3">
                            {{ ucfirst(str_replace('_', ' ', $subject)) }}
                        </h3>
                        <div class="space-y-2">
                            @foreach($permissions as $permission)
                            <label class="flex items-center space-x-2 cursor-pointer">
                                <input type="checkbox"
                                       wire:model="selectedPermissions"
                                       value="{{ $permission['name'] }}"
                                       class="w-4 h-4 text-rose-600 border-gray-300 rounded focus:ring-rose-500">
                                <span class="text-sm text-gray-700">
                                    {{ ucfirst(str_replace('_', ' ', $permission['name'])) }}
                                </span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="mt-6 flex items-center justify-end gap-4">
                <a href="{{ route('admin.roles.index') }}"
                   class="px-6 py-2.5 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition font-medium">
                    Batal
                </a>
                <button type="submit"
                        class="px-6 py-2.5 bg-rose-700 text-white rounded-xl hover:bg-rose-800 transition font-medium">
                    Perbarui Role
                </button>
            </div>
        </form>
    </div>
</div>

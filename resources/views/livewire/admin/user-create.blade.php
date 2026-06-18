<div>
    @section('title', $editMode ? 'Edit User' : 'Tambah User')
    @section('page-title', $editMode ? 'Edit User' : 'Tambah User Baru')

    <div class="max-w-3xl mx-auto">
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
                        <a href="{{ route('admin.users.index') }}" class="text-gray-500 hover:text-gray-700">Users</a>
                    </div>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-4 h-4 text-gray-400 mx-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="text-green-700 font-medium">{{ $editMode ? 'Edit' : 'Tambah' }}</span>
                    </div>
                </li>
            </ol>
        </nav>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <form wire:submit="save">
                <div class="space-y-6">
                    <!-- Role Selection -->
                    <div>
                        <label for="role" class="block text-sm font-medium text-gray-700 mb-1">
                            Role <span class="text-red-500">*</span>
                        </label>
                        <select id="role"
                                wire:model.live="role"
                                @class([
                                    'w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500',
                                    'text-gray-400' => !$role,
                                    'text-gray-900' => $role,
                                    'border-red-500' => $errors->has('role'),
                                ])>
                            <option value="">Pilih Role</option>
                            @foreach($roles as $r)
                            <option value="{{ $r->name }}">{{ ucfirst($r->name) }}</option>
                            @endforeach
                        </select>
                        @error('role') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <!-- Basic Info -->
                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                                Nama Lengkap <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="name" wire:model="name"
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 placeholder-gray-400 @error('name') border-red-500 @enderror"
                                   placeholder="Masukkan nama lengkap">
                            @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                                Email <span class="text-red-500">*</span>
                            </label>
                            <input type="email" id="email" wire:model="email"
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 placeholder-gray-400 @error('email') border-red-500 @enderror"
                                   placeholder="Masukkan email">
                            @error('email') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <!-- Password -->
                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                                Password {{ $editMode ? '' : '*' }}
                            </label>
                            <input type="password" id="password" wire:model="password"
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 placeholder-gray-400 @error('password') border-red-500 @enderror"
                                   placeholder="{{ $editMode ? 'Kosongkan jika tidak diubah' : 'Masukkan password' }}">
                            @error('password') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">
                                Konfirmasi Password {{ $editMode ? '' : '*' }}
                            </label>
                            <input type="password" id="password_confirmation" wire:model="password_confirmation"
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 placeholder-gray-400"
                                   placeholder="Konfirmasi password">
                        </div>
                    </div>

                    <!-- NIP/NIM based on role -->
                    @if(in_array($role, ['kajur', 'sekjur', 'panitia', 'dosen']))
                    <div>
                        <label for="nip" class="block text-sm font-medium text-gray-700 mb-1">
                            NIP <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="nip" wire:model="nip"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('nip') border-red-500 @enderror"
                               placeholder="Masukkan NIP">
                        @error('nip') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    @endif

                    @if($role === 'mahasiswa')
                    <div>
                        <label for="nim" class="block text-sm font-medium text-gray-700 mb-1">
                            NIM <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="nim" wire:model="nim"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('nim') border-red-500 @enderror"
                               placeholder="Masukkan NIM">
                        @error('nim') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    @endif

                    <!-- Jurusan & Prodi -->
                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <label for="jurusan_id" class="block text-sm font-medium text-gray-700 mb-1">
                                Jurusan
                            </label>
                            <select id="jurusan_id" wire:model="jurusan_id"
                                    @class(["w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500", 'text-gray-400' => !$jurusan_id, 'text-gray-900' => $jurusan_id])>
                                <option value="">Pilih Jurusan</option>
                                @foreach($jurusans as $jurusan)
                                <option value="{{ $jurusan->id }}">{{ $jurusan->nama_jurusan }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="prodi_id" class="block text-sm font-medium text-gray-700 mb-1">
                                Program Studi
                            </label>
                            <select id="prodi_id" wire:model="prodi_id"
                                    @class(["w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500", 'text-gray-400' => !$prodi_id, 'text-gray-900' => $prodi_id])>
                                <option value="">Pilih Prodi</option>
                                @foreach($prodis as $prodi)
                                <option value="{{ $prodi->id }}">{{ $prodi->nama_prodi }} ({{ $prodi->jurusan->nama_jurusan ?? '' }})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Contact -->
                    <div>
                        <label for="nomor_hp" class="block text-sm font-medium text-gray-700 mb-1">
                            Nomor HP
                        </label>
                        <input type="text" id="nomor_hp" wire:model="nomor_hp"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 placeholder-gray-400"
                               placeholder="Contoh: 081234567890">
                    </div>

                    <div>
                        <label for="alamat" class="block text-sm font-medium text-gray-700 mb-1">
                            Alamat
                        </label>
                        <textarea id="alamat" wire:model="alamat" rows="2"
                                  class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 placeholder-gray-400"
                                  placeholder="Alamat lengkap..."></textarea>
                    </div>

                    <!-- Status -->
                    <div class="flex items-center">
                        <input type="checkbox" id="is_active" wire:model="is_active"
                               class="w-4 h-4 text-green-700 border-gray-300 rounded focus:ring-green-500">
                        <label for="is_active" class="ml-2 text-sm text-gray-700">
                            Akun Aktif
                        </label>
                    </div>
                </div>

                <div class="mt-8 flex items-center justify-end gap-4">
                    <a href="{{ route('admin.users.index') }}"
                       class="px-6 py-2.5 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition font-medium">
                        Batal
                    </a>
                    <button type="submit"
                            class="px-6 py-2.5 bg-green-700 text-white rounded-xl hover:bg-green-800 transition font-medium">
                        {{ $editMode ? 'Perbarui' : 'Simpan' }} User
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

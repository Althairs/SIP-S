<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Validation\Rule;

class RoleEdit extends Component
{
    public Role $role;
    public $name = '';
    public $selectedPermissions = [];
    public $allPermissions = [];
    public $roleUsersCount = 0;

    public function mount(Role $role)  // Laravel akan otomatis inject model Role
    {
        $this->role = $role;
        $this->name = $this->role->name;

        // Ambil nama permissions yang dimiliki role ini
        $this->selectedPermissions = $this->role->permissions->pluck('name')->toArray();

        // Hitung jumlah user dengan role ini
        $this->roleUsersCount = $this->role->users()->count();

        // Ambil semua permissions dan kelompokkan
        $allPerms = Permission::all();
        $this->allPermissions = $allPerms->groupBy(function($permission) {
            $parts = explode('_', $permission->name);
            return implode('_', array_slice($parts, 1));
        })->toArray();
    }

    protected function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255', Rule::unique('roles', 'name')->ignore($this->role->id)],
            'selectedPermissions' => ['nullable', 'array'],
            'selectedPermissions.*' => ['string', 'exists:permissions,name'],
        ];
    }

    protected $messages = [
        'name.required' => 'Nama role wajib diisi.',
        'name.unique' => 'Nama role sudah digunakan.',
        'selectedPermissions.*.exists' => 'Permission yang dipilih tidak valid.',
    ];

    public function update()
    {
        $validated = $this->validate();

        // Cegah ubah nama super_admin
        if ($this->role->name === 'super_admin' && $this->name !== 'super_admin') {
            session()->flash('error', 'Nama role Super Admin tidak dapat diubah.');
            return;
        }

        // Update nama role
        $this->role->update([
            'name' => $this->name,
        ]);

        // Sync permissions menggunakan nama
        if (!empty($this->selectedPermissions)) {
            $permissions = Permission::whereIn('name', $this->selectedPermissions)->get();
            $this->role->syncPermissions($permissions);
        } else {
            // Hapus semua permissions jika tidak ada yang dipilih
            $this->role->syncPermissions([]);
        }

        // Reset cache
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        session()->flash('success', "Role '{$this->role->name}' berhasil diperbarui.");
        return $this->redirect(route('admin.roles.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.admin.role-edit', [
            'allPermissions' => $this->allPermissions,
        ])->layout('components.layouts.app-auth');
    }
}

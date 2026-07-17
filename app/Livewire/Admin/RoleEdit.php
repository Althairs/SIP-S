<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Validation\Rule;
use App\Services\PermissionService;

class RoleEdit extends Component
{
    public Role $role;
    public $name = '';
    public $selectedPermissions = [];
    public $permissionGroups = [];
    public $roleUsersCount = 0;

    public function mount(Role $role)
    {
        $this->role = $role;
        $this->name = $this->role->name;

        $this->selectedPermissions = $this->role->permissions->pluck('name')->toArray();
        $this->roleUsersCount = $this->role->users()->count();
        $this->permissionGroups = PermissionService::permissionGroups();
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

        if ($this->role->name === 'super_admin' && $this->name !== 'super_admin') {
            session()->flash('error', 'Nama role Super Admin tidak dapat diubah.');
            return;
        }

        $this->role->update(['name' => $this->name]);

        if (!empty($this->selectedPermissions)) {
            $permissions = Permission::whereIn('name', $this->selectedPermissions)->get();
            $this->role->syncPermissions($permissions);
        } else {
            $this->role->syncPermissions([]);
        }

        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        session()->flash('success', "Role '{$this->role->name}' berhasil diperbarui.");
        return $this->redirect(route('admin.roles.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.admin.role-edit', [
            'permissionGroups' => $this->permissionGroups,
        ])->layout('components.layouts.app-auth');
    }
}

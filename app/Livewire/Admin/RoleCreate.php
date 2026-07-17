<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Validation\Rule;
use App\Services\PermissionService;

class RoleCreate extends Component
{
    public $name = '';
    public $selectedPermissions = [];
    public $permissionGroups = [];

    public function mount()
    {
        $this->permissionGroups = PermissionService::permissionGroups();
    }

    protected function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255', Rule::unique('roles', 'name')],
            'selectedPermissions' => ['nullable', 'array'],
            'selectedPermissions.*' => ['string', 'exists:permissions,name'],
        ];
    }

    protected $messages = [
        'name.required' => 'Nama role wajib diisi.',
        'name.unique' => 'Nama role sudah digunakan.',
        'selectedPermissions.*.exists' => 'Permission yang dipilih tidak valid.',
    ];

    public function save()
    {
        $validated = $this->validate();

        $role = Role::create([
            'name' => $this->name,
            'guard_name' => 'web',
        ]);

        if (!empty($this->selectedPermissions)) {
            $permissions = Permission::whereIn('name', $this->selectedPermissions)->get();
            $role->syncPermissions($permissions);
        }

        session()->flash('success', "Role '{$role->name}' berhasil ditambahkan.");
        return $this->redirect(route('admin.roles.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.admin.role-create', [
            'permissionGroups' => $this->permissionGroups,
        ])->layout('components.layouts.app-auth');
    }
}

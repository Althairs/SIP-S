<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Validation\Rule;

class RoleCreate extends Component
{
    public $name = '';
    public $selectedPermissions = [];
    public $allPermissions = [];

    public function mount()
    {
        // Ambil semua permissions sebagai array dengan key 'id' dan 'name'
        $allPerms = Permission::all();

        // Kelompokkan permissions berdasarkan subject
        $this->allPermissions = $allPerms->groupBy(function($permission) {
            $parts = explode('_', $permission->name);
            // Ambil semua kata setelah action (kata pertama)
            return implode('_', array_slice($parts, 1));
        })->toArray();
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

        // Buat role baru
        $role = Role::create([
            'name' => $this->name,
            'guard_name' => 'web',
        ]);

        // Assign permissions menggunakan nama permission (bukan ID)
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
            'allPermissions' => $this->allPermissions,
        ])->layout('components.layouts.app-auth');
    }
}

<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Jurusan;
use Illuminate\Validation\Rule;

class JurusanCreate extends Component
{
    public $kode_jurusan = '';
    public $nama_jurusan = '';
    public $deskripsi = '';
    public $is_active = true;

    protected function rules()
    {
        return [
            'kode_jurusan' => ['required', 'string', 'max:10', Rule::unique('jurusans', 'kode_jurusan')],
            'nama_jurusan' => ['required', 'string', 'max:255'],
            'deskripsi' => ['nullable', 'string'],
            'is_active' => ['boolean'],
        ];
    }

    protected $messages = [
        'kode_jurusan.required' => 'Kode jurusan wajib diisi.',
        'kode_jurusan.unique' => 'Kode jurusan sudah digunakan.',
        'nama_jurusan.required' => 'Nama jurusan wajib diisi.',
    ];

    public function save()
    {
        $validated = $this->validate();

        Jurusan::create($validated);

        session()->flash('success', 'Jurusan berhasil ditambahkan.');
        return $this->redirect(route('admin.jurusans.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.admin.jurusan-create')
            ->layout('components.layouts.app-auth');
    }
}

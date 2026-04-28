<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Prodi;
use App\Models\Jurusan;
use Illuminate\Validation\Rule;

class ProdiCreate extends Component
{
    public $jurusan_id = '';
    public $kode_prodi = '';
    public $nama_prodi = '';
    public $deskripsi = '';
    public $is_active = true;

    protected function rules()
    {
        return [
            'jurusan_id' => ['required', 'exists:jurusans,id'],
            'kode_prodi' => ['required', 'string', 'max:10', Rule::unique('prodis', 'kode_prodi')],
            'nama_prodi' => ['required', 'string', 'max:255'],
            'deskripsi' => ['nullable', 'string'],
            'is_active' => ['boolean'],
        ];
    }

    protected $messages = [
        'jurusan_id.required' => 'Jurusan wajib dipilih.',
        'kode_prodi.required' => 'Kode prodi wajib diisi.',
        'kode_prodi.unique' => 'Kode prodi sudah digunakan.',
        'nama_prodi.required' => 'Nama prodi wajib diisi.',
    ];

    public function save()
    {
        $validated = $this->validate();

        Prodi::create($validated);

        session()->flash('success', 'Program studi berhasil ditambahkan.');
        return $this->redirect(route('admin.prodis.index'), navigate: true);
    }

    public function render()
    {
        $jurusans = Jurusan::active()->get();

        return view('livewire.admin.prodi-create', [
            'jurusans' => $jurusans,
        ])->layout('components.layouts.app-auth');
    }
}

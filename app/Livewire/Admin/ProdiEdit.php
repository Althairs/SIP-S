<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Prodi;
use App\Models\Jurusan;
use Illuminate\Validation\Rule;

class ProdiEdit extends Component
{
    public Prodi $prodi;
    public $jurusan_id = '';
    public $kode_prodi = '';
    public $nama_prodi = '';
    public $deskripsi = '';
    public $is_active = true;

    public function mount(Prodi $prodi)
    {
        $this->prodi = $prodi;
        $this->jurusan_id = $prodi->jurusan_id;
        $this->kode_prodi = $prodi->kode_prodi;
        $this->nama_prodi = $prodi->nama_prodi;
        $this->deskripsi = $prodi->deskripsi;
        $this->is_active = $prodi->is_active;
    }

    protected function rules()
    {
        return [
            'jurusan_id' => ['required', 'exists:jurusans,id'],
            'kode_prodi' => ['required', 'string', 'max:10', Rule::unique('prodis', 'kode_prodi')->ignore($this->prodi->id)],
            'nama_prodi' => ['required', 'string', 'max:255'],
            'deskripsi' => ['nullable', 'string'],
            'is_active' => ['boolean'],
        ];
    }

    public function update()
    {
        $validated = $this->validate();

        $this->prodi->update($validated);

        session()->flash('success', 'Program studi berhasil diperbarui.');
        return $this->redirect(route('admin.prodis.index'), navigate: true);
    }

    public function render()
    {
        $jurusans = Jurusan::active()->get();

        return view('livewire.admin.prodi-edit', [
            'jurusans' => $jurusans,
        ])->layout('components.layouts.app-auth');
    }
}

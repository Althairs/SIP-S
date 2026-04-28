<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Jurusan;
use Illuminate\Validation\Rule;

class JurusanEdit extends Component
{
    public Jurusan $jurusan;
    public $kode_jurusan = '';
    public $nama_jurusan = '';
    public $deskripsi = '';
    public $is_active = true;

    public function mount(Jurusan $jurusan)
    {
        $this->jurusan = $jurusan;
        $this->kode_jurusan = $jurusan->kode_jurusan;
        $this->nama_jurusan = $jurusan->nama_jurusan;
        $this->deskripsi = $jurusan->deskripsi;
        $this->is_active = $jurusan->is_active;
    }

    protected function rules()
    {
        return [
            'kode_jurusan' => ['required', 'string', 'max:10', Rule::unique('jurusans', 'kode_jurusan')->ignore($this->jurusan->id)],
            'nama_jurusan' => ['required', 'string', 'max:255'],
            'deskripsi' => ['nullable', 'string'],
            'is_active' => ['boolean'],
        ];
    }

    public function update()
    {
        $validated = $this->validate();

        $this->jurusan->update($validated);

        session()->flash('success', 'Jurusan berhasil diperbarui.');
        return $this->redirect(route('admin.jurusans.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.admin.jurusan-edit')
            ->layout('components.layouts.app-auth');
    }
}

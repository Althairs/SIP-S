<?php

namespace App\Livewire\Kajur;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use App\Models\Prodi;
use App\Models\BidangKeahlian;
use App\Models\Kepakaran;
use App\Models\KuotaDosen;

class DosenIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $prodiFilter = '';
    public $statusFilter = '';
    public $showModal = false;
    public $showImportModal = false;
    public $editMode = false;
    public $userId;

    // Form fields
    public $name = '';
    public $email = '';
    public $password = '';
    public $password_confirmation = '';
    public $nip = '';
    public $prodi_id = '';
    public $nomor_hp = '';
    public $alamat = '';
    public $is_active = true;

    // TAMBAHAN: Kepakaran & Bidang Keahlian
    public $kepakaran_id = '';
    public $selectedBidangKeahlian = [];
    public $listBidangKeahlian = [];
    public $listKepakaran = [];

    protected $queryString = ['search', 'prodiFilter', 'statusFilter'];

    public function mount()
    {
        $this->loadMasterData();
    }

    public function loadMasterData()
    {
        $jurusanId = auth()->user()->jurusan_id;
        $this->listBidangKeahlian = BidangKeahlian::active()->byJurusan($jurusanId)->orderBy('nama_bidang')->get();
        $this->listKepakaran = Kepakaran::active()->orderBy('hierarki_level')->get();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function openCreateModal()
    {
        $this->resetForm();
        $this->editMode = false;
        $this->showModal = true;
    }

    public function openEditModal($id)
    {
        $this->resetForm();
        $this->editMode = true;
        $this->userId = $id;

        $user = User::with(['bidangKeahlians', 'kepakaran', 'kuota'])->findOrFail($id);
        $this->name = $user->name;
        $this->email = $user->email;
        $this->nip = $user->nip;
        $this->prodi_id = $user->prodi_id;
        $this->nomor_hp = $user->nomor_hp;
        $this->alamat = $user->alamat;
        $this->is_active = $user->is_active;
        $this->kepakaran_id = $user->kepakaran_id;
        $this->selectedBidangKeahlian = $user->bidangKeahlians->pluck('id')->toArray();

        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->reset([
            'name', 'email', 'password', 'password_confirmation',
            'nip', 'prodi_id', 'nomor_hp', 'alamat', 'is_active',
            'userId', 'editMode', 'kepakaran_id', 'selectedBidangKeahlian'
        ]);
    }

    protected function rules()
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $this->userId],
            'nip' => ['required', 'string', 'max:20', 'unique:users,nip,' . $this->userId],
            'prodi_id' => ['nullable', 'exists:prodis,id'],
            'nomor_hp' => ['nullable', 'string', 'max:15'],
            'alamat' => ['nullable', 'string'],
            'is_active' => ['boolean'],
            'kepakaran_id' => ['nullable', 'exists:kepakarans,id'],
            'selectedBidangKeahlian' => ['nullable', 'array'],
            'selectedBidangKeahlian.*' => ['exists:bidang_keahlians,id'],
        ];

        if (!$this->editMode) {
            $rules['password'] = ['required', 'string', 'min:8', 'confirmed'];
        } else {
            $rules['password'] = ['nullable', 'string', 'min:8', 'confirmed'];
        }

        return $rules;
    }

    protected $messages = [
        'name.required' => 'Nama wajib diisi.',
        'email.unique' => 'Email sudah terdaftar.',
        'password.min' => 'Password minimal 8 karakter.',
        'password.confirmed' => 'Konfirmasi password tidak cocok.',
        'nip.required' => 'NIP wajib diisi.',
        'nip.unique' => 'NIP sudah terdaftar.',
    ];

    public function save()
    {
        $validated = $this->validate();

        $jurusanId = auth()->user()->jurusan_id;

        $data = [
            'name' => $this->name,
            'email' => $this->email,
            'nip' => $this->nip,
            'prodi_id' => $this->prodi_id ?: null,
            'jurusan_id' => $jurusanId,
            'nomor_hp' => $this->nomor_hp,
            'alamat' => $this->alamat,
            'is_active' => $this->is_active,
            'kepakaran_id' => $this->kepakaran_id ?: null,
        ];

        if ($this->password) {
            $data['password'] = bcrypt($this->password);
        }

        if ($this->editMode) {
            $user = User::findOrFail($this->userId);
            $user->update($data);

            // Sync bidang keahlian
            $user->bidangKeahlians()->sync($this->selectedBidangKeahlian);

            session()->flash('success', 'Dosen berhasil diperbarui.');
        } else {
            $user = User::create($data);
            $user->assignRole('dosen');

            // Attach bidang keahlian
            if (!empty($this->selectedBidangKeahlian)) {
                $user->bidangKeahlians()->attach($this->selectedBidangKeahlian);
            }

            // Buat kuota default
            KuotaDosen::create([
                'dosen_id' => $user->id,
                'jurusan_id' => $jurusanId,
                'kuota_pembimbing' => 5,
                'kuota_penguji' => 10,
                'terpakai_pembimbing' => 0,
                'terpakai_penguji' => 0,
            ]);

            session()->flash('success', 'Dosen berhasil ditambahkan.');
        }

        $this->closeModal();
    }

    public function toggleStatus($id)
    {
        $user = User::findOrFail($id);
        $user->update(['is_active' => !$user->is_active]);
        session()->flash('success', 'Status dosen berhasil diubah.');
    }

    public function deleteDosen($id)
    {
        User::findOrFail($id)->delete();
        session()->flash('success', 'Dosen berhasil dihapus.');
    }

    public function render()
    {
        $jurusanId = auth()->user()->jurusan_id;

        $dosens = User::role('dosen')
            ->where('jurusan_id', $jurusanId)
            ->with(['prodi', 'bidangKeahlians', 'kepakaran', 'kuota'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%')
                      ->orWhere('nip', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->prodiFilter, function ($query) {
                $query->where('prodi_id', $this->prodiFilter);
            })
            ->when($this->statusFilter !== '', function ($query) {
                $query->where('is_active', $this->statusFilter);
            })
            ->latest()
            ->paginate(10);

        $prodis = Prodi::where('jurusan_id', $jurusanId)->active()->get();

        return view('livewire.kajur.dosen-index', [
            'dosens' => $dosens,
            'prodis' => $prodis,
        ])->layout('components.layouts.app-auth');
    }
}

<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Jurusan;
use App\Models\BidangKeahlian;
use App\Livewire\Kajur\BidangKeahlians;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

uses(RefreshDatabase::class);

beforeEach(function () {
    $permNames = [
        'manage-bidang-keahlian', 'manage-kepakaran', 'manage-kuota',
        'manage-dosen', 'manage-mahasiswa', 'manage-panitia',
        'manage-atribut-dosen', 'manage-reminder',
        'verify-seminar-proposal', 'verify-seminar-hasil', 'verify-sidang-skripsi',
    ];
    foreach ($permNames as $p) {
        Permission::firstOrCreate(['name' => $p, 'guard_name' => 'web']);
    }
    $role = Role::firstOrCreate(['name' => 'kajur', 'guard_name' => 'web']);
    $role->syncPermissions($permNames);

    $jurusan = Jurusan::create(['kode_jurusan' => 'TST', 'nama_jurusan' => 'Test Jurusan', 'is_active' => true]);
    $this->user = User::factory()->create(['is_active' => true, 'jurusan_id' => $jurusan->id]);
    $this->user->assignRole('kajur');
    $this->actingAs($this->user);
});

it('openCreate sets showForm to true and resets data', function () {
    $component = Livewire::test(BidangKeahlians::class)
        ->call('openCreate');

    $component->assertSet('showForm', true)
        ->assertSet('editMode', false)
        ->assertSet('kode', '')
        ->assertSet('nama_bidang', '')
        ->assertSet('deskripsi', '')
        ->assertSet('is_active', true);
});

it('closeForm resets showForm and data', function () {
    $component = Livewire::test(BidangKeahlians::class)
        ->call('openCreate')
        ->assertSet('showForm', true)
        ->call('closeForm');

    $component->assertSet('showForm', false)
        ->assertSet('kode', '')
        ->assertSet('nama_bidang', '');
});

it('openEdit populates data and sets showForm', function () {
    $bidang = BidangKeahlian::create([
        'jurusan_id' => $this->user->jurusan_id,
        'kode' => 'BDT',
        'nama_bidang' => 'Budidaya Tanaman',
        'deskripsi' => 'Test deskripsi',
        'is_active' => true,
    ]);

    $component = Livewire::test(BidangKeahlians::class)
        ->call('openEdit', $bidang->id);

    $component->assertSet('showForm', true)
        ->assertSet('editMode', true)
        ->assertSet('kode', 'BDT')
        ->assertSet('nama_bidang', 'Budidaya Tanaman')
        ->assertSet('deskripsi', 'Test deskripsi')
        ->assertSet('is_active', true);
});

it('validation failure keeps form open with data intact', function () {
    $component = Livewire::test(BidangKeahlians::class)
        ->call('openCreate')
        ->set('kode', '')
        ->set('nama_bidang', '')
        ->call('save');

    // Form should still be open after validation failure
    $component->assertSet('showForm', true)
        ->assertHasErrors(['kode', 'nama_bidang']);
});

it('successful save closes form', function () {
    $component = Livewire::test(BidangKeahlians::class)
        ->call('openCreate')
        ->set('kode', 'NEW')
        ->set('nama_bidang', 'Bidang Baru')
        ->call('save');

    $component->assertSet('showForm', false)
        ->assertHasNoErrors();

    $this->assertDatabaseHas('bidang_keahlians', [
        'kode' => 'NEW',
        'nama_bidang' => 'Bidang Baru',
    ]);
});

it('closeForm after edit resets all data', function () {
    $bidang = BidangKeahlian::create([
        'jurusan_id' => $this->user->jurusan_id,
        'kode' => 'EDIT',
        'nama_bidang' => 'Edit Test',
        'deskripsi' => 'Edit deskripsi',
        'is_active' => false,
    ]);

    $component = Livewire::test(BidangKeahlians::class)
        ->call('openEdit', $bidang->id)
        ->assertSet('showForm', true)
        ->call('closeForm');

    $component->assertSet('showForm', false)
        ->assertSet('kode', '')
        ->assertSet('nama_bidang', '')
        ->assertSet('deskripsi', '')
        ->assertSet('is_active', true)
        ->assertSet('editMode', false)
        ->assertSet('bidangId', null);
});

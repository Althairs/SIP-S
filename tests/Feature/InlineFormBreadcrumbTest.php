<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Jurusan;
use App\Models\BidangKeahlian;
use Livewire\Livewire;
use App\Livewire\Kajur\BidangKeahlians;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

uses(RefreshDatabase::class);

beforeEach(function () {
    $permNames = [
        'manage-bidang-keahlian', 'manage-kepakaran', 'manage-kuota',
        'manage-dosen', 'manage-mahasiswa', 'manage-panitia',
        'manage-atribut-dosen', 'manage-reminder',
        'verify-seminar-proposal', 'verify-seminar-hasil', 'verify-sidang-skripsi',
        'manage-penjadwalan', 'manage-setting-waktu', 'manage-setting-ruangan',
    ];
    foreach ($permNames as $p) {
        Permission::firstOrCreate(['name' => $p, 'guard_name' => 'web']);
    }
    Role::firstOrCreate(['name' => 'kajur', 'guard_name' => 'web']);
    Role::firstOrCreate(['name' => 'panitia_penjadwalan', 'guard_name' => 'web']);

    $jurusan = Jurusan::create(['kode_jurusan' => 'TST', 'nama_jurusan' => 'Test Jurusan', 'is_active' => true]);
    $this->user = User::factory()->create(['is_active' => true, 'jurusan_id' => $jurusan->id]);
    $this->user->assignRole('kajur');
    $this->actingAs($this->user);
});

it('shows list breadcrumbs by default', function () {
    $component = Livewire::test(BidangKeahlians::class);
    $component->assertSee('Data Master');
});

it('shows create breadcrumbs when form is open', function () {
    $component = Livewire::test(BidangKeahlians::class)
        ->call('openCreate');
    $component->assertSee('Tambah');
});

it('shows edit breadcrumbs when editing', function () {
    $bidang = BidangKeahlian::create([
        'jurusan_id' => $this->user->jurusan_id,
        'kode' => 'TST',
        'nama_bidang' => 'Test',
        'is_active' => true,
    ]);

    $component = Livewire::test(BidangKeahlians::class)
        ->call('openEdit', $bidang->id);
    $component->assertSee('Edit');
});

it('hides table when form is open', function () {
    $component = Livewire::test(BidangKeahlians::class)
        ->call('openCreate');
    $component->assertSet('showForm', true);
});

it('shows table when form is closed', function () {
    $component = Livewire::test(BidangKeahlians::class);
    $component->assertSet('showForm', false);
});

it('inline form has save and cancel buttons', function () {
    $component = Livewire::test(BidangKeahlians::class)
        ->call('openCreate');
    $component->assertSee('Simpan');
    $component->assertSee('Batal');
});

it('dashboard penjadwalan shows Status Penjadwalan not Konteks Proses', function () {
    $user = User::factory()->create(['is_active' => true]);
    $user->assignRole('panitia_penjadwalan');
    $this->actingAs($user);

    $response = $this->get(route('panitia.penjadwalan.dashboard'));
    $response->assertStatus(200);

    $content = $response->getContent();
    expect($content)->toContain('Status Penjadwalan');
    expect($content)->not->toContain('Konteks Proses');
});

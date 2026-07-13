<?php

use App\Livewire\Mahasiswa\JadwalUjian;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\Support\TestHelpers;

uses(RefreshDatabase::class);

test('jadwal ujian memisahkan ujian akan datang dan riwayat dengan benar', function () {
    $mahasiswa = TestHelpers::createUser('mahasiswa');

    $upcoming = TestHelpers::createPendaftaran($mahasiswa, 'seminar_proposal', [
        'status' => 'dijadwalkan',
        'tanggal_ujian' => now()->addWeek(),
        'judul_penelitian' => 'Judul Ujian Mendatang',
    ]);

    $pastScheduled = TestHelpers::createPendaftaran($mahasiswa, 'seminar_hasil', [
        'status' => 'dijadwalkan',
        'tanggal_ujian' => now()->subWeek(),
        'judul_penelitian' => 'Judul Ujian Lewat',
    ]);

    $completed = TestHelpers::createPendaftaran($mahasiswa, 'sidang_skripsi', [
        'status' => 'selesai',
        'tanggal_ujian' => now()->subMonth(),
        'judul_penelitian' => 'Judul Ujian Selesai',
        'nilai_total' => 85,
        'grade' => 'A',
    ]);

    $this->actingAs($mahasiswa);

    Livewire::test(JadwalUjian::class)
        ->assertSet('upcomingUjian', fn ($collection) => $collection->count() === 1
            && $collection->first()->id === $upcoming->id)
        ->assertSet('riwayatUjian', fn ($collection) => $collection->count() === 2
            && $collection->pluck('id')->contains($pastScheduled->id)
            && $collection->pluck('id')->contains($completed->id))
        ->assertSee('Judul Ujian Mendatang')
        ->assertSee('Judul Ujian Lewat')
        ->assertSee('Judul Ujian Selesai')
        ->assertSee('Sudah Dijadwalkan')
        ->assertSee('Selesai');
});

test('mahasiswa dapat membuka detail ujian dari riwayat', function () {
    $mahasiswa = TestHelpers::createUser('mahasiswa');

    $completed = TestHelpers::createPendaftaran($mahasiswa, 'seminar_proposal', [
        'status' => 'selesai',
        'tanggal_ujian' => now()->subWeek(),
        'judul_penelitian' => 'Detail Ujian Test',
    ]);

    $this->actingAs($mahasiswa);

    Livewire::test(JadwalUjian::class)
        ->call('showDetailUjian', $completed->id)
        ->assertSet('showDetail', true)
        ->assertSet('selectedUjian.id', $completed->id)
        ->assertSee('Detail Ujian Test');
});

test('halaman jadwal ujian menampilkan kedua tab navigasi', function () {
    $mahasiswa = TestHelpers::createUser('mahasiswa');
    $this->actingAs($mahasiswa);

    Livewire::test(JadwalUjian::class)
        ->assertSee('Akan Datang')
        ->assertSee('Riwayat')
        ->assertSeeHtml('x-show="tab === \'upcoming\'"')
        ->assertSeeHtml('x-show="tab === \'history\'"');
});

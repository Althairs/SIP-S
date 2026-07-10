<?php

use App\Services\JadwalConflictService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Tests\Support\TestHelpers;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->service = new JadwalConflictService;
});

test('proposal dengan penguji dan pembimbing sama bisa digrup', function () {
    $penguji1 = TestHelpers::createUser('dosen');
    $penguji2 = TestHelpers::createUser('dosen');
    $pembimbing = TestHelpers::createUser('dosen');
    $mahasiswa1 = TestHelpers::createUser('mahasiswa', $penguji1->jurusan, $penguji1->prodi);
    $mahasiswa2 = TestHelpers::createUser('mahasiswa', $penguji1->jurusan, $penguji1->prodi);

    $p1 = TestHelpers::createPendaftaran($mahasiswa1, 'seminar_proposal');
    $p2 = TestHelpers::createPendaftaran($mahasiswa2, 'seminar_proposal');

    TestHelpers::attachPenguji($p1, $penguji1, $penguji2);
    TestHelpers::attachPenguji($p2, $penguji1, $penguji2);
    TestHelpers::attachPembimbing($p1, $pembimbing);
    TestHelpers::attachPembimbing($p2, $pembimbing);

    expect($this->service->canGroupProposal([$p1->id, $p2->id]))->toBeTrue();
});

test('proposal dengan penguji berbeda tidak bisa digrup', function () {
    $penguji1a = TestHelpers::createUser('dosen');
    $penguji2a = TestHelpers::createUser('dosen');
    $penguji1b = TestHelpers::createUser('dosen');
    $pembimbing = TestHelpers::createUser('dosen');
    $mahasiswa1 = TestHelpers::createUser('mahasiswa', $penguji1a->jurusan, $penguji1a->prodi);
    $mahasiswa2 = TestHelpers::createUser('mahasiswa', $penguji1a->jurusan, $penguji1a->prodi);

    $p1 = TestHelpers::createPendaftaran($mahasiswa1, 'seminar_proposal');
    $p2 = TestHelpers::createPendaftaran($mahasiswa2, 'seminar_proposal');

    TestHelpers::attachPenguji($p1, $penguji1a, $penguji2a);
    TestHelpers::attachPenguji($p2, $penguji1b, $penguji2a);
    TestHelpers::attachPembimbing($p1, $pembimbing);
    TestHelpers::attachPembimbing($p2, $pembimbing);

    expect($this->service->canGroupProposal([$p1->id, $p2->id]))->toBeFalse();
});

test('seminar hasil menolak slot ruangan sesi yang sudah terisi', function () {
    $mahasiswa1 = TestHelpers::createUser('mahasiswa');
    $mahasiswa2 = TestHelpers::createUser('mahasiswa', $mahasiswa1->jurusan, $mahasiswa1->prodi);

    $existing = TestHelpers::createPendaftaran($mahasiswa1, 'seminar_hasil', [
        'tanggal_ujian' => '2026-07-15 09:00:00',
        'ruangan' => 'Ruang Sidang 1',
        'sesi' => 1,
    ]);

    $candidate = TestHelpers::createPendaftaran($mahasiswa2, 'seminar_hasil');

    expect(fn () => $this->service->validateSchedule(
        $candidate,
        '2026-07-15',
        1,
        'Ruang Sidang 1'
    ))->toThrow(ValidationException::class);
});

test('seminar hasil boleh di sesi sama ruangan berbeda', function () {
    $mahasiswa1 = TestHelpers::createUser('mahasiswa');
    $mahasiswa2 = TestHelpers::createUser('mahasiswa', $mahasiswa1->jurusan, $mahasiswa1->prodi);

    TestHelpers::createPendaftaran($mahasiswa1, 'seminar_hasil', [
        'tanggal_ujian' => '2026-07-15 09:00:00',
        'ruangan' => 'Ruang Sidang 1',
        'sesi' => 1,
    ]);

    $candidate = TestHelpers::createPendaftaran($mahasiswa2, 'seminar_hasil');

    $this->service->validateSchedule($candidate, '2026-07-15', 1, 'Ruang Sidang 2');

    expect(true)->toBeTrue();
});

test('reschedule tidak bentrok dengan jadwal sendiri', function () {
    $mahasiswa = TestHelpers::createUser('mahasiswa');

    $pendaftaran = TestHelpers::createPendaftaran($mahasiswa, 'seminar_hasil', [
        'tanggal_ujian' => '2026-07-15 09:00:00',
        'ruangan' => 'Ruang Sidang 1',
        'sesi' => 1,
    ]);

    $this->service->validateSchedule($pendaftaran, '2026-07-15', 1, 'Ruang Sidang 1');

    expect(true)->toBeTrue();
});

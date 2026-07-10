<?php

use App\Jobs\SendWhatsAppNotification;
use App\Livewire\Dosen\BerikanRevisi;
use App\Livewire\Mahasiswa\DaftarRevisi;
use App\Models\Revisi;
use App\Models\Setting;
use App\Services\WhatsApp\WhatsAppService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Mockery;
use Tests\Support\TestHelpers;

uses(RefreshDatabase::class);

test('mahasiswa dapat upload file revisi dan status menjadi diperiksa', function () {
    Storage::fake('public');

    $dosen = TestHelpers::createUser('dosen');
    $mahasiswa = TestHelpers::createUser('mahasiswa', $dosen->jurusan, $dosen->prodi);
    $pendaftaran = TestHelpers::createPendaftaran($mahasiswa, 'seminar_proposal', ['status' => 'selesai']);

    TestHelpers::attachPenguji($pendaftaran, $dosen);

    $revisi = Revisi::create([
        'pendaftaran_id' => $pendaftaran->id,
        'dosen_id' => $dosen->id,
        'peran_pemberi' => 'penguji_1',
        'isi_revisi' => 'Perbaiki bagian metodologi penelitian.',
        'kategori' => 'minor',
        'status' => 'pending',
        'deadline' => now()->addDays(14),
    ]);

    $this->actingAs($mahasiswa);

    Livewire::test(DaftarRevisi::class)
        ->call('selectRevisi', $revisi->id)
        ->set('file_revisi', UploadedFile::fake()->create('revisi.pdf', 500, 'application/pdf'))
        ->set('catatan_mahasiswa', 'Revisi metodologi sudah diperbaiki.')
        ->call('uploadRevisi')
        ->assertHasNoErrors();

    $revisi->refresh();

    expect($revisi->status)->toBe('diperiksa')
        ->and($revisi->file_revisi_mahasiswa)->not->toBeNull()
        ->and($revisi->catatan_mahasiswa)->toBe('Revisi metodologi sudah diperbaiki.')
        ->and($revisi->uploaded_at)->not->toBeNull();

    Storage::disk('public')->assertExists($revisi->file_revisi_mahasiswa);
});

test('dosen dapat menyetujui revisi mahasiswa', function () {
    $dosen = TestHelpers::createUser('dosen');
    $mahasiswa = TestHelpers::createUser('mahasiswa', $dosen->jurusan, $dosen->prodi);
    $pendaftaran = TestHelpers::createPendaftaran($mahasiswa, 'seminar_proposal', ['status' => 'selesai']);

    TestHelpers::attachPenguji($pendaftaran, $dosen);

    $revisi = Revisi::create([
        'pendaftaran_id' => $pendaftaran->id,
        'dosen_id' => $dosen->id,
        'peran_pemberi' => 'penguji_1',
        'isi_revisi' => 'Perbaiki abstrak.',
        'kategori' => 'minor',
        'status' => 'diperiksa',
        'file_revisi_mahasiswa' => 'revisi_mahasiswa/test.pdf',
        'uploaded_at' => now(),
    ]);

    $this->actingAs($dosen);

    Livewire::test(BerikanRevisi::class, ['pendaftaran' => $pendaftaran])
        ->call('openReviewModal', $revisi->id)
        ->call('approveRevisi');

    $revisi->refresh();

    expect($revisi->status)->toBe('disetujui')
        ->and($revisi->is_approved)->toBeTrue()
        ->and($revisi->approved_at)->not->toBeNull();
});

test('dosen dapat menolak revisi dan mahasiswa diminta upload ulang', function () {
    $dosen = TestHelpers::createUser('dosen');
    $mahasiswa = TestHelpers::createUser('mahasiswa', $dosen->jurusan, $dosen->prodi);
    $pendaftaran = TestHelpers::createPendaftaran($mahasiswa, 'sidang_skripsi', ['status' => 'selesai']);

    TestHelpers::attachPenguji($pendaftaran, $dosen);

    $revisi = Revisi::create([
        'pendaftaran_id' => $pendaftaran->id,
        'dosen_id' => $dosen->id,
        'peran_pemberi' => 'penguji_1',
        'isi_revisi' => 'Perbaiki lampiran.',
        'kategori' => 'major',
        'status' => 'diperiksa',
        'file_revisi_mahasiswa' => 'revisi_mahasiswa/lama.pdf',
        'uploaded_at' => now(),
    ]);

    $this->actingAs($dosen);

    Livewire::test(BerikanRevisi::class, ['pendaftaran' => $pendaftaran])
        ->call('openReviewModal', $revisi->id)
        ->set('catatanDosenReview', 'Perbaikan belum lengkap.')
        ->call('rejectRevisi');

    $revisi->refresh();

    expect($revisi->status)->toBe('pending')
        ->and($revisi->file_revisi_mahasiswa)->toBeNull();
});

test('job whatsapp melewati nomor kosong', function () {
    $mock = Mockery::mock(WhatsAppService::class);
    $mock->shouldNotReceive('send');

    $job = new SendWhatsAppNotification('', 'Pesan test');
    $job->handle($mock);

    expect(true)->toBeTrue();
});

test('job whatsapp memanggil service saat nomor tersedia', function () {
    Setting::set('whatsapp_enabled', '1');
    Setting::set('whatsapp_provider', 'fonnte');
    Setting::set('whatsapp_fonnte_token', 'test-token');

    Http::fake([
        'api.fonnte.com/*' => Http::response(['status' => true], 200),
    ]);

    $job = new SendWhatsAppNotification('081234567890', 'Jadwal ujian Anda sudah tersedia.');
    $job->handle(new WhatsAppService);

    Http::assertSentCount(1);
});

test('penjadwalan ujian mengantri notifikasi whatsapp', function () {
    Queue::fake();

    $penguji = TestHelpers::createUser('dosen', null, null, ['nomor_hp' => '082222222222']);
    $mahasiswa = TestHelpers::createUser('mahasiswa', $penguji->jurusan, $penguji->prodi, [
        'nomor_hp' => '081111111111',
    ]);
    $pendaftaran = TestHelpers::createPendaftaran($mahasiswa, 'seminar_hasil', [
        'status' => 'disetujui_kajur',
        'first_registered_at' => now()->subDays(14),
    ]);

    TestHelpers::attachPenguji($pendaftaran, $penguji);

    $panitia = TestHelpers::createUser('panitia_penjadwalan', $penguji->jurusan, $penguji->prodi);
    $this->actingAs($panitia);

    Livewire::test(\App\Livewire\Panitia\Penjadwalan\JadwalUjians::class)
        ->call('openScheduleModal', $pendaftaran->id)
        ->set('tanggal_ujian', now()->addWeek()->format('Y-m-d'))
        ->set('ruangan', 'Ruang Seminar 1')
        ->set('sesi', 1)
        ->call('scheduleUjian');

    Queue::assertPushed(SendWhatsAppNotification::class, 2);
});

test('mahasiswa melihat semua revisi dari pembimbing dan penguji', function () {
    $pembimbing = TestHelpers::createUser('dosen');
    $penguji = TestHelpers::createUser('dosen', $pembimbing->jurusan, $pembimbing->prodi);
    $mahasiswa = TestHelpers::createUser('mahasiswa', $pembimbing->jurusan, $pembimbing->prodi);
    $pendaftaran = TestHelpers::createPendaftaran($mahasiswa, 'seminar_proposal', ['status' => 'selesai']);

    // Attach pembimbing and penguji
    TestHelpers::attachPembimbing($pendaftaran, $pembimbing);
    TestHelpers::attachPenguji($pendaftaran, $penguji);

    // Create revisi from pembimbing
    $revisiPembimbing = Revisi::create([
        'pendaftaran_id' => $pendaftaran->id,
        'dosen_id' => $pembimbing->id,
        'peran_pemberi' => 'pembimbing_1',
        'isi_revisi' => 'Perbaiki bab 1.',
        'kategori' => 'minor',
        'status' => 'pending',
        'deadline' => now()->addDays(14),
    ]);

    // Create revisi from penguji
    $revisiPenguji = Revisi::create([
        'pendaftaran_id' => $pendaftaran->id,
        'dosen_id' => $penguji->id,
        'peran_pemberi' => 'penguji_1',
        'isi_revisi' => 'Perbaiki metodologi.',
        'kategori' => 'major',
        'status' => 'pending',
        'deadline' => now()->addDays(14),
    ]);

    $this->actingAs($mahasiswa);

    Livewire::test(DaftarRevisi::class)
        ->assertSet('revisis', function ($revisis) use ($revisiPembimbing, $revisiPenguji) {
            expect($revisis)->toHaveCount(2)
                ->and($revisis->pluck('id')->toArray())->toContain($revisiPembimbing->id, $revisiPenguji->id);
            return true;
        });
});

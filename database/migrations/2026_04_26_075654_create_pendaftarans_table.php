<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pendaftarans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mahasiswa_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('jurusan_id')->constrained('jurusans')->onDelete('cascade');
            $table->foreignId('prodi_id')->nullable()->constrained('prodis')->onDelete('set null');

            // Jenis Ujian
            $table->enum('jenis_ujian', ['seminar_proposal', 'seminar_hasil', 'sidang_skripsi']);

            // Data Penelitian
            $table->string('judul_penelitian');
            $table->text('abstrak')->nullable();
            $table->string('bidang_keahlian')->nullable();

            // File Uploads
            $table->string('file_proposal')->nullable();
            $table->string('file_skripsi')->nullable();
            $table->string('file_persetujuan')->nullable();
            $table->string('file_krs')->nullable();
            $table->string('file_transkrip')->nullable();
            $table->string('file_bukti_bimbingan')->nullable();

            // Status
            $table->enum('status', [
                'draft',
                'pending',
                'disetujui_kaprodi',
                'ditolak_kaprodi',
                'disetujui_kajur',
                'ditolak_kajur',
                'dijadwalkan',
                'selesai',
                'revisi'
            ])->default('draft');

            // Penjadwalan
            $table->dateTime('tanggal_ujian')->nullable();
            $table->string('ruangan')->nullable();
            $table->integer('sesi')->nullable();

            // Nilai
            $table->decimal('nilai_total', 5, 2)->nullable();
            $table->string('grade')->nullable();
            $table->text('catatan_revisi')->nullable();
            $table->text('catatan_penguji')->nullable();

            // Timestamp
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('scheduled_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pendaftarans');
    }
};

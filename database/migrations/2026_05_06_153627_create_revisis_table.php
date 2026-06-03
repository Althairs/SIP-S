<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('revisis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pendaftaran_id')->constrained('pendaftarans')->onDelete('cascade');
            $table->foreignId('dosen_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('ujian_penguji_id')->nullable()->constrained('ujian_pengujis')->onDelete('set null');

            // Peran pemberi revisi
            $table->enum('peran_pemberi', ['penguji_1', 'penguji_2', 'pembimbing_1', 'pembimbing_2']);

            // Isi revisi
            $table->text('isi_revisi');
            $table->enum('kategori', ['minor', 'major', 'kritis']);

            // Status revisi
            $table->enum('status', ['pending', 'selesai', 'ditolak'])->default('pending');

            // Upload mahasiswa
            $table->string('file_revisi_mahasiswa')->nullable();
            $table->text('catatan_mahasiswa')->nullable();
            $table->dateTime('uploaded_at')->nullable();

            // Persetujuan dosen
            $table->boolean('is_approved')->default(false);
            $table->dateTime('approved_at')->nullable();
            $table->text('catatan_dosen')->nullable();

            // Deadline
            $table->date('deadline')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('revisis');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penilaians', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pendaftaran_id')->constrained('pendaftarans')->onDelete('cascade');
            $table->foreignId('dosen_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('ujian_penguji_id')->nullable()->constrained('ujian_pengujis')->onDelete('set null');

            // Peran pemberi nilai
            $table->enum('peran_pemberi', ['penguji_1', 'penguji_2', 'pembimbing_1', 'pembimbing_2']);

            // Tipe penilaian
            $table->enum('tipe_input', ['sistem', 'berkas'])->default('sistem');

            // Komponen Nilai (0-100)
            $table->decimal('presentasi', 5, 2)->nullable()->comment('Presentasi Karya Ilmiah - 10%');
            $table->decimal('penguasaan', 5, 2)->nullable()->comment('Penguasaan Materi - 15%');
            $table->decimal('menjawab', 5, 2)->nullable()->comment('Cara Menjawab - 10%');
            $table->decimal('deskripsi', 5, 2)->nullable()->comment('Daya Deskripsi - 10%');
            $table->decimal('analisis', 5, 2)->nullable()->comment('Daya Analisis - 20%');
            $table->decimal('menyimpulkan', 5, 2)->nullable()->comment('Daya Menyimpulkan - 15%');
            $table->decimal('implikasi', 5, 2)->nullable()->comment('Daya Implikasi - 20%');

            // Hasil
            $table->decimal('nilai_akhir', 5, 2)->nullable();
            $table->string('nilai_huruf', 2)->nullable();
            $table->string('predikat')->nullable();

            // Upload berkas
            $table->string('file_penilaian')->nullable();
            $table->text('catatan')->nullable();

            // Status
            $table->enum('status', ['draft', 'selesai', 'diverifikasi'])->default('draft');
            $table->timestamp('submitted_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penilaians');
    }
};

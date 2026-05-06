<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengaturan_reminders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jurusan_id')->constrained('jurusans')->onDelete('cascade');
            $table->enum('jenis_ujian', ['seminar_proposal', 'seminar_hasil', 'sidang_skripsi', 'revisi']);
            $table->boolean('is_active')->default(true);

            // Frekuensi reminder (JSON untuk multiple reminder)
            // Contoh: [{"type": "daily", "interval": 5}, {"type": "before_deadline", "days": 7}]
            $table->json('reminder_settings')->nullable();

            // Deadline default dalam hari (untuk revisi)
            $table->integer('deadline_days')->default(30);

            $table->text('pesan_template')->nullable();
            $table->timestamps();

            $table->unique(['jurusan_id', 'jenis_ujian']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengaturan_reminders');
    }
};

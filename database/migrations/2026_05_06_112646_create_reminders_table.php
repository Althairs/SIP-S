<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reminders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('pendaftaran_id')->constrained('pendaftarans')->onDelete('cascade');
            $table->foreignId('pengaturan_reminder_id')->nullable()->constrained('pengaturan_reminders')->onDelete('set null');

            $table->string('judul');
            $table->text('pesan');
            $table->enum('tipe', ['revisi', 'deadline', 'periodik', 'info']);
            $table->enum('prioritas', ['rendah', 'sedang', 'tinggi']);

            $table->dateTime('tanggal_tampil');
            $table->dateTime('tanggal_kadaluarsa')->nullable();
            $table->boolean('is_read')->default(false);
            $table->dateTime('read_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reminders');
    }
};

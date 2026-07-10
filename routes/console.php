<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\Facades\Log;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule monthly quota reset on the 1st of every month at 00:00
Schedule::command('kuota:reset-bulanan')
    ->monthlyOn(1, '00:00')
    ->description('Reset kuota dosen bulanan ke default')
    ->onSuccess(function () {
        Log::info('Kuota dosen bulanan berhasil direset pada ' . now()->format('Y-m-d H:i:s'));
    })
    ->onFailure(function () {
        Log::error('Gagal mereset kuota dosen bulanan pada ' . now()->format('Y-m-d H:i:s'));
    });

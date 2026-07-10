<?php

use App\Models\Setting;
use App\Services\WhatsApp\WhatsAppService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;

uses(RefreshDatabase::class);

test('whatsapp tidak terkirim jika fitur dinonaktifkan', function () {
    Setting::set('whatsapp_enabled', '0');

    $service = new WhatsAppService;
    $result = $service->send('081234567890', 'Test message');

    expect($result['success'])->toBeFalse()
        ->and($result['response'])->toBe('WhatsApp notifications are disabled.');
});

test('whatsapp fonnte menormalisasi nomor dan mengirim pesan', function () {
    Setting::set('whatsapp_enabled', '1');
    Setting::set('whatsapp_provider', 'fonnte');
    Setting::set('whatsapp_fonnte_token', 'test-token');

    Http::fake([
        'api.fonnte.com/*' => Http::response(['status' => true], 200),
    ]);

    $service = new WhatsAppService;
    $result = $service->send('081234567890', 'Jadwal ujian Anda: 15 Juli 2026');

    expect($result['success'])->toBeTrue();

    Http::assertSent(function ($request) {
        return $request->url() === 'https://api.fonnte.com/send'
            && $request['target'] === '6281234567890'
            && $request['message'] === 'Jadwal ujian Anda: 15 Juli 2026';
    });
});

test('whatsapp netflie membutuhkan token dan phone id', function () {
    Setting::set('whatsapp_enabled', '1');
    Setting::set('whatsapp_provider', 'netflie');
    Setting::set('whatsapp_netflie_token', '');
    Setting::set('whatsapp_netflie_phone_id', '');

    $service = new WhatsAppService;
    $result = $service->send('6281234567890', 'Test');

    expect($result['success'])->toBeFalse()
        ->and($result['response'])->toBe('Netflie credentials not configured.');
});

test('whatsapp netflie mengirim pesan jika credential lengkap', function () {
    Setting::set('whatsapp_enabled', '1');
    Setting::set('whatsapp_provider', 'netflie');
    Setting::set('whatsapp_netflie_token', 'netflie-token');
    Setting::set('whatsapp_netflie_phone_id', '123456789');

    Http::fake([
        'graph.facebook.com/*' => Http::response(['messages' => [['id' => 'wamid.test']]], 200),
    ]);

    $service = new WhatsAppService;
    $result = $service->send('6281234567890', 'Penguji baru ditugaskan');

    expect($result['success'])->toBeTrue();
});

<?php

namespace App\Livewire;

use Livewire\Component;
use App\Services\WhatsApp\WhatsAppService;
use App\Models\Setting;
use Illuminate\Support\Facades\Log;

class WhatsAppTest extends Component
{
    public $phone = '';
    public $message = '';
    public $response = '';
    public $isSending = false;
    public $success = false;
    public $settings = [];

    // Untuk template
    public $templateMessage = '';
    public $templatePhone = '';
    public $templates = [];
    public $selectedTemplate = '';

    protected $listeners = ['refreshComponent' => '$refresh'];

    public function mount()
    {
        // Load settings
        $this->settings = [
            'enabled' => Setting::get('whatsapp_enabled', '0'),
            'provider' => Setting::get('whatsapp_provider', 'fonnte'),
            'fonnte_token' => Setting::get('whatsapp_fonnte_token', ''),
            'netflie_token' => Setting::get('whatsapp_netflie_token', ''),
            'netflie_phone_id' => Setting::get('whatsapp_netflie_phone_id', ''),
        ];

        // Load templates from config
        $this->templates = config('whatsapp.templates', [
            [
                'name' => 'Pendaftaran Berhasil',
                'message' => "Assalamualaikum Wr. Wb.\n\nYth. {name}\n\nSelamat! Pendaftaran {jenis_ujian} Anda dengan judul \"{judul}\" telah berhasil diverifikasi.\n\nTanggal Ujian: {tanggal}\nRuangan: {ruangan}\nSesi: {sesi}\n\nMohon hadir tepat waktu.\n\nTerima kasih."
            ],
            [
                'name' => 'Jadwal Ujian',
                'message' => "Assalamualaikum Wr. Wb.\n\nYth. {name}\n\nBerikut jadwal ujian Anda:\n\nJenis Ujian: {jenis_ujian}\nJudul: {judul}\nTanggal: {tanggal}\nRuangan: {ruangan}\nSesi: {sesi}\n\nTerima kasih."
            ],
            [
                'name' => 'Revisi Skripsi',
                'message' => "Assalamualaikum Wr. Wb.\n\nYth. {name}\n\nMohon segera melakukan revisi {jenis_ujian} dengan judul \"{judul}\".\n\nCatatan Revisi:\n{catatan_revisi}\n\nDeadline revisi: {deadline}\n\nTerima kasih."
            ],
            [
                'name' => 'Pengingat Ujian',
                'message' => "Assalamualaikum Wr. Wb.\n\nYth. {name}\n\nIni adalah pengingat bahwa ujian {jenis_ujian} Anda akan dilaksanakan besok.\n\nJudul: {judul}\nTanggal: {tanggal}\nRuangan: {ruangan}\nSesi: {sesi}\n\nMohon persiapkan diri Anda.\n\nTerima kasih."
            ]
        ]);

        // Set default
        if (count($this->templates) > 0) {
            $this->selectedTemplate = $this->templates[0]['name'];
            $this->templateMessage = $this->templates[0]['message'];
            $this->message = $this->templates[0]['message'];
        }
    }

    public function selectTemplate($templateName)
    {
        foreach ($this->templates as $template) {
            if ($template['name'] === $templateName) {
                $this->selectedTemplate = $templateName;
                $this->templateMessage = $template['message'];
                $this->message = $template['message'];
                break;
            }
        }
    }

    public function applyTemplateToMessage()
    {
        $this->message = $this->templateMessage;
    }

    public function saveTemplate()
    {
        // Save to database or config

        // Perbaikan Livewire v3
        $this->dispatch('alert', type: 'success', message: 'Template berhasil disimpan!');
    }

    public function sendMessage()
{
    $this->validate([
        'phone' => 'required|string|min:8',
        'message' => 'required|string|min:1',
    ]);

    $this->isSending = true;
    $this->response = '';
    $this->success = false;

    try {
        $whatsapp = new WhatsAppService();
        $result = $whatsapp->send($this->phone, $this->message);

        $this->success = $result['success'];

        if ($this->success) {
            $this->response = 'Pesan berhasil dikirim!';
        } else {
            // Cek apakah response berbentuk array, jika ya, konversi ke string JSON
            $errorDetail = $result['response'] ?? 'Unknown error';
            if (is_array($errorDetail)) {
                $errorDetail = json_encode($errorDetail, JSON_PRETTY_PRINT);
            }

            $this->response = 'Gagal mengirim: ' . $errorDetail;
        }

        // Log untuk debugging
        Log::info('WhatsApp test result', [
            'phone' => $this->phone,
            'success' => $result['success'],
            'response' => $result['response']
        ]);

    } catch (\Exception $e) {
        $this->success = false;
        $this->response = 'Error: ' . $e->getMessage();
        Log::error('WhatsApp test error: ' . $e->getMessage());
    }

    $this->isSending = false;

    // Dispatch event ke browser (Livewire v3)
    $this->dispatch('alert',
        type: $this->success ? 'success' : 'error',
        message: $this->response
    );
}

    public function sendTestNotification()
    {
        // Test with predefined data
        $this->phone = '08123456789';
        $this->message = "Test WhatsApp Notification\n\n" .
                         "Waktu: " . now()->format('d-m-Y H:i:s') . "\n" .
                         "Ini adalah pesan test dari sistem SIP-S.\n\n" .
                         "Terima kasih.";
        $this->sendMessage();
    }

    public function render()
    {
        return view('livewire.whatsapp-test', [
            'templates' => $this->templates,
            'settings' => $this->settings
        ])->layout('components.layouts.app-auth');
    }
}

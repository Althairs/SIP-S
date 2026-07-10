<?php

namespace App\Livewire\Admin;

use Livewire\Component;

use App\Models\Setting;

class NotificationSettings extends Component
{
    public $whatsapp_enabled = false;
    public $whatsapp_provider = 'fonnte';
    public $whatsapp_fonnte_token = '';
    public $whatsapp_netflie_token = '';
    public $whatsapp_netflie_phone_id = '';

    public function mount()
    {
        $this->whatsapp_enabled = Setting::get('whatsapp_enabled', '0') === '1';
        $this->whatsapp_provider = Setting::get('whatsapp_provider', 'fonnte');
        $this->whatsapp_fonnte_token = Setting::get('whatsapp_fonnte_token', '');
        $this->whatsapp_netflie_token = Setting::get('whatsapp_netflie_token', '');
        $this->whatsapp_netflie_phone_id = Setting::get('whatsapp_netflie_phone_id', '');
    }

    public function save()
    {
        $this->validate([
            'whatsapp_provider' => 'required|in:fonnte,netflie',
        ]);

        Setting::set('whatsapp_enabled', $this->whatsapp_enabled ? '1' : '0');
        Setting::set('whatsapp_provider', $this->whatsapp_provider);
        Setting::set('whatsapp_fonnte_token', $this->whatsapp_fonnte_token);
        Setting::set('whatsapp_netflie_token', $this->whatsapp_netflie_token);
        Setting::set('whatsapp_netflie_phone_id', $this->whatsapp_netflie_phone_id);

        session()->flash('success', 'Pengaturan notifikasi berhasil disimpan.');
    }

    public function render()
    {
        return view('livewire.admin.notification-settings')->layout('components.layouts.app-auth');
    }
}

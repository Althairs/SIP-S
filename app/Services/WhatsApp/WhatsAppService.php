<?php

namespace App\Services\WhatsApp;

use App\Models\Setting;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    public function send(string $phone, string $message): array
    {
        $enabled = Setting::get('whatsapp_enabled', '0');
        
        if ($enabled !== '1') {
            return ['success' => false, 'response' => 'WhatsApp notifications are disabled.'];
        }

        // Format phone number to start with country code if it starts with 0
        $phone = preg_replace('/^0/', '62', $phone);
        $phone = preg_replace('/[^0-9]/', '', $phone); // Remove non-numeric chars
        
        $provider = Setting::get('whatsapp_provider', 'fonnte');
        
        $gateway = null;

        if ($provider === 'fonnte') {
            $token = Setting::get('whatsapp_fonnte_token', '');
            if (empty($token)) {
                return ['success' => false, 'response' => 'Fonnte token not configured.'];
            }
            $gateway = new FonnteGateway($token);
        } else if ($provider === 'netflie') {
            $token = Setting::get('whatsapp_netflie_token', '');
            $phoneId = Setting::get('whatsapp_netflie_phone_id', '');
            if (empty($token) || empty($phoneId)) {
                return ['success' => false, 'response' => 'Netflie credentials not configured.'];
            }
            $gateway = new NetflieGateway($token, $phoneId);
        } else {
            return ['success' => false, 'response' => 'Unknown WhatsApp provider.'];
        }

        return $gateway->send($phone, $message);
    }
}

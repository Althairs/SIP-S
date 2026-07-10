<?php

namespace App\Services\WhatsApp;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class NetflieGateway implements WhatsAppGatewayInterface
{
    protected $token;
    protected $phoneNumberId;

    public function __construct(string $token, string $phoneNumberId)
    {
        $this->token = $token;
        $this->phoneNumberId = $phoneNumberId;
    }

    public function send(string $phone, string $message): array
    {
        try {
            // Meta WhatsApp Cloud API endpoint

            $url = "https://graph.facebook.com/v24.0/{$this->phoneNumberId}/messages";

            Log::info([
                'token' => substr($this->token, 0, 20),
                'phoneNumberId' => $this->phoneNumberId,
                'url' => $url,
            ]);
            $response = Http::withToken($this->token)->post($url, [
                'messaging_product' => 'whatsapp',
                'to' => $phone,
                'type' => 'text',
                'text' => [
                    'body' => $message,
                    'preview_url' => false,
                ],
            ]);

            return [
                'success' => $response->successful(),
                'response' => $response->json(),
            ];
        } catch (\Exception $e) {
            Log::error('Netflie (Cloud API) send error: ' . $e->getMessage());
            return [
                'success' => false,
                'response' => $e->getMessage(),
            ];
        }
    }
}

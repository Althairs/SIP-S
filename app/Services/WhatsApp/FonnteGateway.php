<?php

namespace App\Services\WhatsApp;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FonnteGateway implements WhatsAppGatewayInterface
{
    protected $token;

    public function __construct(string $token)
    {
        $this->token = $token;
    }

    public function send(string $phone, string $message): array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => $this->token
            ])->post('https://api.fonnte.com/send', [
                'target' => $phone,
                'message' => $message,
                'countryCode' => '62',
            ]);

            return [
                'success' => $response->successful(),
                'response' => $response->json(),
            ];
        } catch (\Exception $e) {
            Log::error('Fonnte send error: ' . $e->getMessage());
            return [
                'success' => false,
                'response' => $e->getMessage(),
            ];
        }
    }
}

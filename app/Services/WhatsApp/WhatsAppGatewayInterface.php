<?php

namespace App\Services\WhatsApp;

interface WhatsAppGatewayInterface
{
    /**
     * Send a WhatsApp message to the specified phone number.
     * 
     * @param string $phone
     * @param string $message
     * @return array [success => bool, response => mixed]
     */
    public function send(string $phone, string $message): array;
}

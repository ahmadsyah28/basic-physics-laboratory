<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    private $adminPhone;

    public function __construct()
    {
        // Set nomor WhatsApp admin lab (format: 62812345678)
        $this->adminPhone = config('app.whatsapp_admin', '6287801482963'); // Sesuaikan dengan nomor admin
    }

    /**
     * Send WhatsApp message using WhatsApp Web API
     * Note: Untuk production, disarankan menggunakan WhatsApp Business API
     */
    public function sendMessage($message)
    {
        try {
            // Format nomor untuk WhatsApp Web
            $phone = $this->formatPhoneNumber($this->adminPhone);

            // Create WhatsApp Web URL
            $whatsappUrl = $this->createWhatsAppWebUrl($phone, $message);

            // Log the message for tracking
            Log::info('WhatsApp message prepared', [
                'phone' => $phone,
                'url_length' => strlen($whatsappUrl),
                'message_preview' => substr($message, 0, 100) . '...'
            ]);

            return [
                'success' => true,
                'url' => $whatsappUrl,
                'phone' => $phone,
                'message' => 'WhatsApp URL generated successfully'
            ];

        } catch (\Exception $e) {
            Log::error('WhatsApp service error: ' . $e->getMessage());

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Format phone number for WhatsApp
     */
    private function formatPhoneNumber($phone)
    {
        // Remove all non-digits
        $phone = preg_replace('/[^0-9]/', '', $phone);

        // Add country code if not present
        if (substr($phone, 0, 2) !== '62') {
            if (substr($phone, 0, 1) === '0') {
                $phone = '62' . substr($phone, 1);
            } else {
                $phone = '62' . $phone;
            }
        }

        return $phone;
    }

    /**
     * Create WhatsApp Web URL
     */
    private function createWhatsAppWebUrl($phone, $message)
    {
        $encodedMessage = urlencode($message);
        return "https://wa.me/{$phone}?text={$encodedMessage}";
    }

    /**
     * Alternative: Send via WhatsApp Business API (untuk production)
     * Uncomment dan configure jika menggunakan WhatsApp Business API
     */
    /*
    public function sendViaBusinessAPI($message)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . config('services.whatsapp.token'),
                'Content-Type' => 'application/json',
            ])->post(config('services.whatsapp.url'), [
                'messaging_product' => 'whatsapp',
                'to' => $this->adminPhone,
                'type' => 'text',
                'text' => [
                    'body' => $message
                ]
            ]);

            if ($response->successful()) {
                Log::info('WhatsApp message sent successfully via Business API');
                return ['success' => true, 'response' => $response->json()];
            } else {
                Log::error('WhatsApp Business API error: ' . $response->body());
                return ['success' => false, 'error' => $response->body()];
            }

        } catch (\Exception $e) {
            Log::error('WhatsApp Business API exception: ' . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
    */

    /**
     * Send notification to multiple numbers (if needed)
     */
    public function sendToMultiple(array $phones, $message)
    {
        $results = [];

        foreach ($phones as $phone) {
            $originalAdminPhone = $this->adminPhone;
            $this->adminPhone = $phone;

            $result = $this->sendMessage($message);
            $results[$phone] = $result;

            $this->adminPhone = $originalAdminPhone;
        }

        return $results;
    }

    /**
     * Get admin phone number
     */
    public function getAdminPhone()
    {
        return $this->adminPhone;
    }

    /**
     * Set admin phone number
     */
    public function setAdminPhone($phone)
    {
        $this->adminPhone = $phone;
        return $this;
    }
}

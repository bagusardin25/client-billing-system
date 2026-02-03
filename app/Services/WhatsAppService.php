<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    protected string $apiUrl = 'https://api.fonnte.com/send';
    protected ?string $token;

    public function __construct()
    {
        $this->token = config('services.fonnte.token');
    }

    /**
     * Check if the service is configured
     */
    public function isConfigured(): bool
    {
        return !empty($this->token);
    }

    /**
     * Send single WhatsApp message
     */
    public function sendMessage(string $phone, string $message): array
    {
        if (!$this->isConfigured()) {
            return [
                'status' => false,
                'reason' => 'Fonnte API token not configured. Please set FONNTE_TOKEN in .env file.',
            ];
        }

        // Format phone number
        $phone = $this->formatPhoneNumber($phone);

        try {
            $response = Http::withHeaders([
                'Authorization' => $this->token,
            ])->post($this->apiUrl, [
                'target' => $phone,
                'message' => $message,
                'countryCode' => '62',
            ]);

            $result = $response->json();

            // Log the response
            Log::info('WhatsApp message sent', [
                'phone' => $phone,
                'status' => $result['status'] ?? 'unknown',
                'detail' => $result['detail'] ?? $result['reason'] ?? 'no detail',
            ]);

            return $result;
        } catch (\Exception $e) {
            Log::error('WhatsApp message failed', [
                'phone' => $phone,
                'error' => $e->getMessage(),
            ]);

            return [
                'status' => false,
                'reason' => 'Failed to send message: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Send bulk WhatsApp messages
     * 
     * @param array $recipients Array of ['phone' => '...', 'message' => '...']
     * @return array Results for each recipient
     */
    public function sendBulkMessages(array $recipients): array
    {
        $results = [
            'success' => 0,
            'failed' => 0,
            'details' => [],
        ];

        foreach ($recipients as $recipient) {
            $result = $this->sendMessage($recipient['phone'], $recipient['message']);

            $isSuccess = $result['status'] ?? false;
            
            if ($isSuccess) {
                $results['success']++;
            } else {
                $results['failed']++;
            }

            $results['details'][] = [
                'phone' => $recipient['phone'],
                'success' => $isSuccess,
                'response' => $result,
            ];

            // Delay 1 second between messages to avoid rate limiting
            usleep(1000000);
        }

        return $results;
    }

    /**
     * Format phone number to international format (62xxx)
     */
    public function formatPhoneNumber(string $phone): string
    {
        // Remove all non-numeric characters
        $phone = preg_replace('/[^0-9]/', '', $phone);

        // Convert leading 0 to 62
        if (str_starts_with($phone, '0')) {
            $phone = '62' . substr($phone, 1);
        }

        // Add 62 if not present
        if (!str_starts_with($phone, '62')) {
            $phone = '62' . $phone;
        }

        return $phone;
    }
}

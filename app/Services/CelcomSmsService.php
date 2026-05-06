<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CelcomSmsService
{
    protected $apiKey;
    protected $shortCode;
    protected $partnerId;
    protected $baseUrl;

    public function __construct()
    {
        $this->apiKey = config('services.celcom.api_key');
        $this->shortCode = config('services.celcom.shortcode');
        $this->partnerId = config('services.celcom.partner_id');
        $this->baseUrl = config('services.celcom.base_url', 'https://isms.celcomafrica.com/api/services/sendsms/');
    }

    /**
     * Send an SMS message.
     *
     * @param string $to
     * @param string $message
     * @return bool
     */
    public function send($to, $message)
    {
        // Format phone number to 254... if needed
        $to = $this->formatPhoneNumber($to);

        try {
            $response = Http::post($this->baseUrl, [
                'apikey' => $this->apiKey,
                'shortcode' => $this->shortCode,
                'partnerID' => $this->partnerId,
                'mobile' => $to,
                'message' => $message,
            ]);

            if ($response->successful()) {
                Log::info("SMS sent successfully to {$to}");
                return true;
            }

            Log::error("SMS failed to {$to}: " . $response->body());
            return false;
        } catch (\Exception $e) {
            Log::error("SMS Exception for {$to}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Format phone number to standard international format (e.g., 2547...).
     *
     * @param string $number
     * @return string
     */
    protected function formatPhoneNumber($number)
    {
        $number = preg_replace('/[^0-9]/', '', $number);
        
        if (str_starts_with($number, '0')) {
            $number = '254' . substr($number, 1);
        }
        
        if (strlen($number) == 9) {
            $number = '254' . $number;
        }

        return $number;
    }
}

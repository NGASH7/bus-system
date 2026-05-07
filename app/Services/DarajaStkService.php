<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use RuntimeException;

class DarajaStkService
{
    public function diagnoseAuth(): array
    {
        $consumerKey = $this->setting('consumer_key', 'DARAJA_CONSUMER_KEY');
        $consumerSecret = $this->setting('consumer_secret', 'DARAJA_CONSUMER_SECRET');
        $results = [];

        foreach ($this->candidateBaseUrls() as $baseUrl) {
            $response = Http::withBasicAuth($consumerKey, $consumerSecret)
                ->get(rtrim($baseUrl, '/') . '/oauth/v1/generate?grant_type=client_credentials');

            $results[] = [
                'base_url' => $baseUrl,
                'http_status' => $response->status(),
                'has_access_token' => !empty($response->json('access_token')),
                'raw' => $response->json() ?: $response->body(),
            ];
        }

        return [
            'configured' => $this->isConfigured(),
            'consumer_key_length' => strlen((string) $consumerKey),
            'consumer_secret_length' => strlen((string) $consumerSecret),
            'shortcode' => $this->setting('shortcode', 'DARAJA_SHORTCODE'),
            'callback_url' => config('services.mpesa.callback_url'),
            'results' => $results,
        ];
    }

    public function isConfigured(): bool
    {
        return !empty($this->setting('consumer_key', 'DARAJA_CONSUMER_KEY'))
            && !empty($this->setting('consumer_secret', 'DARAJA_CONSUMER_SECRET'))
            && !empty($this->setting('shortcode', 'DARAJA_SHORTCODE'))
            && !empty($this->setting('passkey', 'DARAJA_PASSKEY'));
    }

    public function push(array $payload): array
    {
        if (!$this->isConfigured()) {
            throw new RuntimeException('M-Pesa Daraja credentials are not configured.');
        }

        ['token' => $token, 'base_url' => $baseUrl] = $this->accessTokenAndBaseUrl();
        $shortCode = (string) ($payload['shortcode'] ?? $this->setting('shortcode', 'DARAJA_SHORTCODE'));
        $partyB = (string) ($payload['party_b'] ?? $shortCode);
        $transactionType = (string) ($payload['transaction_type'] ?? ($this->setting('transaction_type', 'DARAJA_TRANSACTION_TYPE') ?: 'CustomerPayBillOnline'));
        $passkey = $this->setting('passkey', 'DARAJA_PASSKEY');
        $timestamp = now('Africa/Nairobi')->format('YmdHis');
        $password = base64_encode($shortCode . $passkey . $timestamp);

        $response = Http::withToken($token)
            ->post(rtrim($baseUrl, '/') . '/mpesa/stkpush/v1/processrequest', [
                'BusinessShortCode' => $shortCode,
                'Password' => $password,
                'Timestamp' => $timestamp,
                'TransactionType' => $transactionType,
                'Amount' => (int) $payload['amount'],
                'PartyA' => $payload['phone'],
                'PartyB' => $partyB,
                'PhoneNumber' => $payload['phone'],
                'CallBackURL' => $payload['callback_url'],
                'AccountReference' => $payload['reference'],
                'TransactionDesc' => $payload['description'],
            ]);

        if (!$response->ok()) {
            throw new RuntimeException('M-Pesa STK request failed: ' . $response->body());
        }

        $payload = $response->json();
        $payload['_base_url'] = $baseUrl;
        $payload['_request_payload'] = [
            'BusinessShortCode' => $shortCode,
            'TransactionType' => $transactionType,
            'PartyB' => $partyB,
        ];

        return $payload;
    }

    private function accessTokenAndBaseUrl(): array
    {
        $consumerKey = $this->setting('consumer_key', 'DARAJA_CONSUMER_KEY');
        $consumerSecret = $this->setting('consumer_secret', 'DARAJA_CONSUMER_SECRET');

        foreach ($this->candidateBaseUrls() as $baseUrl) {
            $response = Http::withBasicAuth($consumerKey, $consumerSecret)
                ->get(rtrim($baseUrl, '/') . '/oauth/v1/generate?grant_type=client_credentials');

            if ($response->ok() && !empty($response->json('access_token'))) {
                return [
                    'token' => (string) $response->json('access_token'),
                    'base_url' => $baseUrl,
                ];
            }
        }

        throw new RuntimeException('Failed to authenticate with M-Pesa Daraja API. Check consumer key/secret and environment.');
    }

    private function candidateBaseUrls(): array
    {
        $configured = $this->setting('base_url', 'DARAJA_BASE_URL') ?: 'https://sandbox.safaricom.co.ke';
        $configured = rtrim($configured, '/');

        // If credentials were copied from another Daraja app environment, try both endpoints automatically.
        $alternates = [
            'https://sandbox.safaricom.co.ke',
            'https://api.safaricom.co.ke',
        ];

        $all = array_unique(array_merge([$configured], $alternates));

        return array_values(array_filter($all));
    }

    private function setting(string $configKey, string $envKey): ?string
    {
        $value = (string) config('services.mpesa.' . $configKey);
        if (trim($value) !== '') {
            return $value;
        }

        $dotEnvPath = base_path('.env');
        if (!is_file($dotEnvPath)) {
            return null;
        }

        $contents = (string) file_get_contents($dotEnvPath);
        if (!preg_match('/^' . preg_quote($envKey, '/') . '=(.*)$/m', $contents, $matches)) {
            return null;
        }

        return trim($matches[1], " \t\n\r\0\x0B\"'");
    }
}


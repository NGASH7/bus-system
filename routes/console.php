<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use App\Services\DarajaStkService;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('daraja:diagnose', function (DarajaStkService $daraja) {
    $report = $daraja->diagnoseAuth();

    $this->info('Daraja configuration diagnosis');
    $this->line('Configured: ' . ($report['configured'] ? 'yes' : 'no'));
    $this->line('Consumer key length: ' . $report['consumer_key_length']);
    $this->line('Consumer secret length: ' . $report['consumer_secret_length']);
    $this->line('Shortcode: ' . ($report['shortcode'] ?: '(empty)'));
    $this->line('Callback URL: ' . ($report['callback_url'] ?: '(empty)'));
    $this->newLine();

    foreach ($report['results'] as $result) {
        $this->line('Endpoint: ' . $result['base_url']);
        $this->line('HTTP status: ' . $result['http_status']);
        $this->line('Has token: ' . ($result['has_access_token'] ? 'yes' : 'no'));
        $this->line('Response: ' . json_encode($result['raw']));
        $this->newLine();
    }
})->purpose('Diagnose Daraja OAuth credentials and endpoints');

Artisan::command('daraja:stk-probe {phone=254708374149} {amount=1} {transactionType=CustomerPayBillOnline} {shortcode=174379} {partyB=174379}', function (DarajaStkService $daraja) {
    $phone = (string) $this->argument('phone');
    $digits = preg_replace('/\D+/', '', $phone);
    if (str_starts_with($digits, '0') && strlen($digits) === 10) {
        $digits = '254' . substr($digits, 1);
    }

    try {
        $response = $daraja->push([
            'amount' => (int) $this->argument('amount'),
            'phone' => $digits,
            'callback_url' => (string) config('services.mpesa.callback_url'),
            'reference' => 'PROBE-' . now()->format('His'),
            'description' => 'Daraja probe',
            'transaction_type' => (string) $this->argument('transactionType'),
            'shortcode' => (string) $this->argument('shortcode'),
            'party_b' => (string) $this->argument('partyB'),
        ]);

        $this->info('STK probe response:');
        $this->line('Phone used: ' . $digits);
        $this->line(json_encode($response));
    } catch (\Throwable $e) {
        $this->error('STK probe failed: ' . $e->getMessage());
    }
})->purpose('Send a test STK request to verify shortcode/passkey');

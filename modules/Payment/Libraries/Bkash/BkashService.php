<?php

namespace Modules\Payment\Libraries\Bkash;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class BkashService
{
    protected array $config;
    protected string $baseUrl;

    public function __construct(array $config)
    {
        $this->config = $config;

        $config['sandbox']
            ? $this->baseUrl = 'https://tokenized.sandbox.bka.sh/v1.2.0-beta/tokenized'
            : $this->baseUrl = 'https://tokenized.pay.bka.sh/v1.2.0-beta/tokenized';
    }


    public function getToken(): string
    {
        $headers = [
            'Content-Type' => 'application/json',
            'username' => $this->config['username'],
            'password' => $this->config['password'],
        ];

        $data = [
            'app_key' => $this->config['app_key'],
            'app_secret' => $this->config['app_secret'],
        ];

        if (Cache::has('bkash_refresh_token')) {
            $url = $this->baseUrl . '/checkout/token/refresh';
            $data['refresh_token'] = Cache::get('bkash_refresh_token');
        } else {
            $url = $this->baseUrl . '/checkout/token/grant';
        }

        if (Cache::has('bkash_token')) {
            $token = Cache::get('bkash_token');

            if ($token) {
                return $token;
            }
        }

        $response = Http::withHeaders($headers)
            ->post($url, $data)
            ->json();

        if (!isset($response['id_token'])) {
            throw new \Exception('Token Not Found.');
        }

        Cache::put('bkash_token', $response['id_token'], 3000);
        Cache::put('bkash_refresh_token', $response['refresh_token'], 3300);

        return $response['id_token'];
    }


    public function createPayment(array $paymentData)
    {
        $token = $this->getToken();
        $url = $this->baseUrl . '/checkout/create';

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'authorization' => $token,
            'x-app-key' => $this->config['app_key'],
        ])->post($url, $paymentData);

        return $response->json();
    }

    public function executePayment(string $paymentID): array
    {
        $token = $this->getToken();
        $url = $this->baseUrl . '/checkout/execute';

        $response = Http::withHeaders([
            'Authorization' => $token,
            'X-APP-Key' => $this->config['app_key'],
        ])->post($url, [
            'paymentID' => $paymentID,
        ]);

        return $response->json();
    }

    public function queryPayment($paymentID)
    {
        $token = $this->getToken();
        $url = $this->baseUrl . '/checkout/payment/status';

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => $token,
            'X-APP-Key' => $this->config['app_key']
        ])->post($url, [
            'paymentID' => $paymentID,
        ]);

        return $response->json();
    }

    public function searchTransaction(string $trxID): array|string
    {
        $token = $this->getToken();
        $appKey = $this->config['app_key'];
        $url = $this->baseUrl . '/checkout/payment/status';

        $response = Http::withHeaders([
            'Authorization' => $token,
            'x-app-key' => $appKey,
        ])->post($url, [
            'trxID' => $trxID,
        ]);

        return $response->json();
    }
}

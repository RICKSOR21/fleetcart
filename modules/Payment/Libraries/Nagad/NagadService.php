<?php

namespace Modules\Payment\Libraries\Nagad;

use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Modules\Payment\Libraries\Nagad\Exceptions\NagadException;

class NagadService
{
    protected array $config;
    protected string $baseUrl;

    public function __construct(array $config)
    {
        $this->config = $config;
        $this->baseUrl = $this->config['sandbox']
            ? 'https://api.nagad.com.bd/v2/'
            : 'https://api.nagad.com.bd/v1/';
    }

    private function initPayment($invoice)
    {
        $url = $this->baseUrl . "check-out/initialize/" . $this->config['merchant_id'] . "/{$invoice}";
        $sensitiveData = $this->getSensitiveData($invoice, $this->config);

        $body = [
            "accountNumber" => $this->config['merchant_number'],
            "dateTime" => Carbon::now()->timezone('Asia/Dhaka')->format('YmdHis'),
            "sensitiveData" => $this->encryptWithPublicKey(json_encode($sensitiveData), $this->config),
            'signature' => $this->signatureGenerate(json_encode($sensitiveData), $this->config),
        ];

        $response = Http::acceptJson()->post($url, $body)->json();

        if (isset($response->reason)) {
            throw new NagadException($response->message);
        }

        return $response;
    }

    public function createPayment($invoice)
    {
        $initialize = $this->initPayment($invoice, $this->config);

        if ($initialize->sensitiveData && $initialize->signature) {
            $decryptData = json_decode($this->decryptDataPrivateKey($initialize->sensitiveData, $this->config));
            $url = $this->baseUrl . "/check-out/complete/" . $decryptData->paymentReferenceId;
            $sensitiveOrderData = [
                'merchantId' => $this->config['merchant_id'],
                'orderId' => $invoice,
                'currencyCode' => '050',
                'amount' => $amount,
                'challenge' => $decryptData->challenge
            ];

            $response = Http::withHeaders($this->headers())
                ->post($url, [
                    'sensitiveData' => $this->encryptWithPublicKey(json_encode($sensitiveOrderData), $this->config),
                    'signature' => $this->signatureGenerate(json_encode($sensitiveOrderData), $this->config),
                    'merchantCallbackURL' => $this->config['callback_url'],
                ]);

            $response = json_decode($response->body());

            if (isset($response->reason)) {
                throw new NagadException($response->message);
            }

            return $response;
        }
    }

    public function verifyPayment($paymentRefId)
    {
        $url = $this->baseUrl . "verify/payment/{$paymentRefId}";
        $response = Http::acceptJson()->get($url);

        return $response->json();
    }

    public function executePayment($amount, $invoice)
    {
        return $this->createPayment($amount, $invoice);
    }
}

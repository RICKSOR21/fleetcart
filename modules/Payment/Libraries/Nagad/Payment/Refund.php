<?php

namespace Karim007\LaravelNagad\Payment;

use Exception;
use Illuminate\Support\Facades\Http;
use Modules\Payment\Libraries\Nagad\Payment\BaseApi;
use Modules\Payment\Libraries\Nagad\Payment\Payment;
use Modules\Payment\Libraries\Nagad\Exception\NagadException;

class Refund extends BaseApi
{
    protected $config;
    public function _construct($config)
    {
        parent::__construct($config);
        $this->config = $config;
    }
    /**
     * Payment refund
     *
     * @param $paymentRefId
     * @param float $refundAmount
     * @param string $referenceNo
     * @param string $message
     *
     * @return mixed
     * @throws NagadException
     * @throws InvalidPrivateKey
     * @throws InvalidPublicKey
     */
    public function refund($paymentRefId, $refundAmount, $referenceNo = "", $message = "Requested for refund")
    {
        $config =  $this->config;

        $paymentDetails = (new Payment($config))->verify($paymentRefId);

        if (isset($paymentDetails->reason)) {
            throw new NagadException($paymentDetails->message);
        }

        if (empty($referenceNo)) {
            $referenceNo = $this->getRandomString(10);
        }

        $sensitiveOrderData = [
            'merchantId'          => $config['merchant_id'],
            "originalRequestDate" => date("Ymd"),
            'originalAmount'      => $paymentDetails->amount,
            'cancelAmount'        => $refundAmount,
            'referenceNo'         => $referenceNo,
            'referenceMessage'    => $message,
        ];

        $response = Http::withHeaders($this->headers())
            ->post($this->baseUrl . "purchase/cancel?paymentRefId={$paymentDetails->paymentRefId}&orderId={$paymentDetails->orderId}", [
                "sensitiveDataCancelRequest" => $this->encryptWithPublicKey(json_encode($sensitiveOrderData), $config),
                "signature"                  => $this->signatureGenerate(json_encode($sensitiveOrderData), $config)
            ]);

        $responseData = json_decode($response->body());

        if (isset($responseData->reason)) {
            throw new NagadException($responseData->message);
        }

        return json_decode($this->decryptDataPrivateKey($responseData->sensitiveData, $config));
    }
}

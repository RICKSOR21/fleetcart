<?php

namespace Modules\Payment\Libraries\Bkash;

class BkashPayment
{
    protected array $config;
    protected BkashService $service;

    public function __construct(array $config)
    {
        $this->config = $config;
        $this->service = new BkashService($config);
    }

    /**
     * Initiates a checkout payment request
     * @param string $paymentData
     * @return array|mixed
     */
    public function create(array $paymentData)
    {
        return $this->service->createPayment($paymentData);
    }

    /**
     * Executes the payment using a previously created payment ID
     */
    public function execute(string $paymentID)
    {
        return $this->service->executePayment($paymentID);
    }

    /**
     * Queries the status of a payment
     */
    public function query(string $paymentID)
    {
        return $this->service->queryPayment($paymentID);
    }


    /**
     * Searches a transaction by trxID
     */
    public function searchTransaction(string $trxID): array|string
    {
        return $this->service->searchTransaction($trxID);
    }
}

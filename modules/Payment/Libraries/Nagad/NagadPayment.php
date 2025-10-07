<?php

namespace Modules\Payment\Libraries\Nagad;

use Modules\Payment\Libraries\Nagad\Exceptions\InvalidPrivateKey;
use Modules\Payment\Libraries\Nagad\Exceptions\InvalidPublicKey;
use Modules\Payment\Libraries\Nagad\Exceptions\NagadException;

class NagadPayment
{
    protected array $config;
    protected NagadService $service;

    public function __construct(array $config)
    {
        $this->config = $config;
        $this->service = new NagadService($config);
    }


    /**
     * @param float $amount
     * @param string $invoice
     *
     * @return mixed
     * @throws InvalidPrivateKey
     * @throws InvalidPublicKey
     * @throws NagadException|\Illuminate\Http\Client\ConnectionException
     */
    public function create($amount, $invoice)
    {
        return $this->service->createPayment($amount, $invoice);
    }

    public function execute($amount, $invoice)
    {
        $response = $this->service->createPayment($amount, $invoice);

        if ($response->status == "Success") {
            return redirect($response->callBackUrl);
        }
    }

    /**
     * @param string $paymentRefId
     *
     * @return mixed
     */
    public function verify(string $paymentRefId)
    {
        return $this->service->verifyPayment($paymentRefId);
    }
}

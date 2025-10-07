<?php

namespace Modules\Payment\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Payment\Libraries\Bkash\BkashService;

class BkashPaymentController
{

    public BkashService $bkashService;

    public function __construct()
    {
        $config = [
            'sandbox' => (bool)setting('bkash_test_mode'),
            'app_key' => setting('bkash_app_key'),
            'app_secret' => setting('bkash_app_secret'),
            'username' => setting('bkash_username'),
            'password' => setting('bkash_password'),
            'timezone' => 'Asia/Dhaka',
        ];

        $this->bkashService = new BkashService($config);
    }

    public function getToken()
    {
        return $this->bkashService->getToken();
    }

    public function createPayment(Request $request)
    {
        $paymentData = [
            'intent' => 'sale',
            'mode' => '0011',
            'payerReference' => $request->input('payerReference'),
            'currency' => 'BDT',
            'amount' => $request->input('amount'),
            'merchantInvoiceNumber' => $request->input('merchantInvoiceNumber'),
            'callbackURL' => $request->input('callbackURL'),
        ];

        return $this->bkashService->createPayment($paymentData);
    }


    public function executePayment($paymentID)
    {
        return $this->bkashService->executePayment($paymentID);
    }
}

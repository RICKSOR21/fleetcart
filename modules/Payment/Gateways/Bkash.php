<?php

namespace Modules\Payment\Gateways;

use Exception;
use Illuminate\Http\Request;
use Modules\Order\Entities\Order;
use Modules\Payment\GatewayInterface;
use Modules\Payment\Libraries\Bkash\BkashPayment;
use Modules\Payment\Libraries\Bkash\BkashService;
use Modules\Payment\Responses\BkashResponse;

class Bkash implements GatewayInterface
{
    public $label;
    public $description;

    public function __construct()
    {
        $this->label = setting('bkash_label');
        $this->description = setting('bkash_description');
    }


    /**
     * @throws Exception
     */
    public function purchase(Order $order, Request $request)
    {
        if (currency() !== 'BDT') {
            throw new Exception(trans('payment::messages.only_supports_bdt'));
        }

        $config = [
            'sandbox' => (bool)setting('bkash_test_mode'),
            'app_key' => setting('bkash_app_key') ?? '',
            'app_secret' => setting('bkash_app_secret') ?? '',
            'username' => setting('bkash_username') ?? '',
            'password' => setting('bkash_password') ?? '',
            'timezone' => 'Asia/Dhaka',
        ];

        $bkashPayment = new BkashPayment($config);

        $paymentData = [
            'intent' => 'sale',
            'mode' => '0011',
            'payerReference' => $order->id,
            'currency' => 'BDT',
            'amount' => $order->total->convertToCurrentCurrency()->round()->amount(),
            'merchantInvoiceNumber' => $order->id,
            'callbackURL' => $this->getRedirectUrl($order),
        ];

        $response = $bkashPayment->create($paymentData);

        return new BkashResponse($order, $response);
    }


    public function complete(Order $order)
    {
        return new BkashResponse($order, request()->all());
    }


    private function getRedirectUrl($order)
    {
        return route('checkout.complete.store', ['orderId' => $order->id, 'paymentMethod' => 'bkash']);
    }
}

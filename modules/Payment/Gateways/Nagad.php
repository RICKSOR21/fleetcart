<?php

namespace Modules\Payment\Gateways;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Modules\Order\Entities\Order;
use Modules\Payment\GatewayInterface;
use Modules\Payment\Libraries\Nagad\NagadPayment;
use Modules\Payment\Responses\NagadResponse;

class Nagad implements GatewayInterface
{
    public $label;
    public $description;


    public function __construct()
    {
        $this->label = setting('nagad_label');
        $this->description = setting('nagad_description');
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
            'sandbox' => (bool)setting('nagad_test_mode'),
            'merchant_id' => setting('nagad_merchant_id'),
            'merchant_number' => setting('nagad_merchant_number'),
            'public_key' => setting('nagad_public_key'),
            'private_key' => setting('nagad_private_key'),
            'callback_url' => $this->getRedirectUrl($order),
        ];

        $payment = new NagadPayment($config);

        $invoiceId = Str::substr(Str::uuid()->toString(), 0, 8);
        $amount = $order->total->convertToCurrentCurrency()->round()->amount();

        $response = $payment->create($amount, $invoiceId);

        return new NagadResponse($order, $response);
    }


    public function complete(Order $order)
    {
        return new NagadResponse($order, request()->all());
    }


    private function getRedirectUrl($order)
    {
        return route('checkout.complete.store', ['orderId' => $order->id, 'paymentMethod' => 'nagad']);
    }
}

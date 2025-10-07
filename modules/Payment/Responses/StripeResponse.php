<?php

namespace Modules\Payment\Responses;

use Modules\Order\Entities\Order;
use Modules\Payment\GatewayResponse;
use Modules\Payment\HasTransactionReference;

class StripeResponse extends GatewayResponse implements HasTransactionReference
{
    private $order;
    private $clientResponse;


    public function __construct(Order $order, array|object $clientResponse)
    {
        $this->order = $order;
        $this->clientResponse = $clientResponse;
    }


    public function getOrderId()
    {
        return $this->order->id;
    }


    public function getTransactionReference()
    {
        return $this->clientResponse->query('reference');
    }


    public function toArray()
    {
        if (setting('stripe_integration_type') === 'embedded_form') {
            $array['orderId'] = $this->order->id;
            $array['client_secret'] = $this->clientResponse['client_secret'];
            $array['return_url'] = $this->clientResponse['return_url'];
        } else {
            $array['redirectUrl'] = $this->clientResponse->url;
        }

        return $array;
    }
}

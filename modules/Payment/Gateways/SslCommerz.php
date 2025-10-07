<?php

namespace Modules\Payment\Gateways;

use Exception;
use Illuminate\Http\Request;
use Modules\Order\Entities\Order;
use Modules\Payment\GatewayInterface;
use Modules\Payment\Libraries\SslCommerz\SSLCommerzConfig;
use Modules\Payment\Libraries\SslCommerz\SslCommerzNotification;
use Modules\Payment\Responses\SslCommerzResponse;

class SslCommerz implements GatewayInterface
{
    public $label;
    public $description;


    public function __construct()
    {
        $this->label = setting('sslcommerz_label');
        $this->description = setting('sslcommerz_description');
    }


    /**
     * @throws Exception
     */
    public function purchase(Order $order, Request $request)
    {
        $supported_currencies = ['BDT', 'EUR', 'GBP', 'AUD', 'CAD'];

        if (!in_array(currency(), $supported_currencies)) {
            throw new Exception(trans('payment::messages.currency_not_supported'));
        }

        $config =
            [
                'sandbox' => setting('sslcommerz_sandbox') ? true : false,
                'store_id' => setting('sslcommerz_store_id') ?? '',
                'store_password' => setting('sslcommerz_store_password') ?? '',
                'is_localhost' => setting('sslcommerz_is_localhost') ? true : false,
                'success_url' => $this->getRedirectUrl($order),
                'fail_url' => $this->getPaymentFailedUrl($order),
            ];

        $sslcommerz_config = (new SSLCommerzConfig($config))->getConfig();

        $sslc = new SslCommerzNotification($sslcommerz_config);

        $post_data = array();
        $post_data['total_amount'] = $order->total->convertToCurrentCurrency()->round()->amount();
        $post_data['currency'] = currency();
        $post_data['tran_id'] = uniqid('TRX_');

        # CUSTOMER INFORMATION
        $post_data['cus_name'] = implode(' ', [$order->customer_first_name, $order->customer_last_name]);
        $post_data['cus_email'] = $order->customer_email;
        $post_data['cus_add1'] = $order->billing_address_1;
        $post_data['cus_add2'] = $order->billing_address_2;
        $post_data['cus_city'] = $order->billing_city;
        $post_data['cus_state'] = $order->billing_state;
        $post_data['cus_postcode'] = $order->billing_zip;
        $post_data['cus_country'] = "Bangladesh";
        $post_data['cus_phone'] = $order->customer_phone;
        $post_data['cus_fax'] = "";

        # SHIPMENT INFORMATION
        $post_data['ship_name'] = setting('store_name');
        $post_data['ship_add1'] = $order->shipping_address_1;
        $post_data['ship_add2'] = $order->shipping_address_2;
        $post_data['ship_city'] = $order->shipping_city;
        $post_data['ship_state'] = $order->shipping_state;
        $post_data['ship_postcode'] = $order->shipping_zip;
        $post_data['ship_phone'] = $order->customer_phone;
        $post_data['ship_country'] = "Bangladesh";

        $post_data['shipping_method'] = "NO";
        $post_data['product_name'] = "Product";
        $post_data['product_category'] = "Goods";
        $post_data['product_profile'] = "physical-goods";

        $response = $sslc->makePayment($post_data, 'checkout');

        if ($response) {
            $response = json_decode($response);
        } else {
            throw new Exception(trans('payment::messages.something_went_wrong'));
        }

        return new SslCommerzResponse($order, $response);
    }


    public function complete(Order $order)
    {
        return new SslCommerzResponse($order, request()->all());
    }


    private function getRedirectUrl($order)
    {
        return route('checkout.complete.store', ['orderId' => $order->id, 'paymentMethod' => 'sslcommerz']);
    }

    private function getPaymentFailedUrl($order)
    {
        return route('checkout.payment_canceled.store', ['orderId' => $order->id, 'paymentMethod' => 'sslcommerz']);
    }
}

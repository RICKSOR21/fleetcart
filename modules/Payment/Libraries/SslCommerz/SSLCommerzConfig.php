<?php

namespace Modules\Payment\Libraries\SslCommerz;

class SSLCommerzConfig
{
    protected string $apiDomain;
    protected array $apiCredentials;
    protected array $apiUrl;
    protected bool $connectFromLocalhost;
    protected string $successUrl;
    protected string $failedUrl;
    protected string $cancelUrl;
    protected string $ipnUrl;
    protected $config;

    public function __construct(array $config)
    {
        $apiDomain  = $config['sandbox'] ? "https://sandbox.sslcommerz.com" : "https://securepay.sslcommerz.com";


        $this->config = [
            'apiCredentials' => [
                'store_id' => $config['store_id'],
                'store_password' => $config['store_password'],
            ],
            'apiUrl' => [
                'make_payment' => "/gwprocess/v4/api.php",
                'transaction_status' => "/validator/api/merchantTransIDvalidationAPI.php",
                'order_validate' => "/validator/api/validationserverAPI.php",
                'refund_payment' => "/validator/api/merchantTransIDvalidationAPI.php",
                'refund_status' => "/validator/api/merchantTransIDvalidationAPI.php",
            ],
            'apiDomain' => $apiDomain,
            'connect_from_localhost' => $config['is_localhost'] ?? false,
            'success_url' => $config['success_url'],
            'failed_url' => $config['fail_url'],
            'cancel_url' => $config['fail_url'],
            'ipn_url' => '/ipn',
        ];
    }

    public function getConfig()
    {
        return $this->config;
    }

}

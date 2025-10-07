<?php

namespace Modules\Payment\Libraries\Nagad\Traits;

use Carbon\Carbon;
use Modules\Payment\Libraries\Nagad\Exceptions\InvalidPublicKey;
use Modules\Payment\Libraries\Nagad\Exceptions\InvalidPrivateKey;

trait Helpers
{
    /**
     * @return string|null
     */
    public function getIp()
    {
        return request()->ip();
    }

    /**
     * @param int $length
     *
     * @return string
     */
    public function getRandomString($length = 45)
    {
        $characters       = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString     = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    /**
     * @param string $invoice
     *
     * @return array
     */
    public function getSensitiveData(string $invoice, $config)
    {
        return [
            'merchantId' => $config['merchant_id'],
            'datetime'   => Carbon::now('Asia/Dhaka')->format("YmdHis"),
            'orderId'    => $invoice,
            'challenge'  => $this->getRandomString()
        ];
    }

    /**
     * @param string $data
     *
     * @return string
     * @throws InvalidPublicKey
     */
    public function encryptWithPublicKey(string $data, $config)
    {
        $publicKey   = "-----BEGIN PUBLIC KEY-----\n" . $config['public_key'] . "\n-----END PUBLIC KEY-----";
        $keyResource = openssl_get_publickey($publicKey);
        $status      = openssl_public_encrypt($data, $cryptoText, $keyResource);
        if ($status) {
            return base64_encode($cryptoText);
        } else {
            throw new InvalidPublicKey('Invalid Public key');
        }
    }

    /**
     * @param string $data
     * @param $config
     * @return mixed
     */
    public static function decryptDataPrivateKey(string $data, $config)
    {
        $private_key = "-----BEGIN RSA PRIVATE KEY-----\n" . $config['private_key'] . "\n-----END RSA PRIVATE KEY-----";
        openssl_private_decrypt(base64_decode($data), $plain_text, $private_key);
        return $plain_text;
    }

    /**
     * @param string $data
     *
     * @return string
     * @throws InvalidPrivateKey
     */
    public function signatureGenerate(string $data, $config)
    {
        $private_key = "-----BEGIN RSA PRIVATE KEY-----\n" . $config['private_key'] . "\n-----END RSA PRIVATE KEY-----";
        $status      = openssl_sign($data, $signature, $private_key, OPENSSL_ALGO_SHA256);
        if ($status) {
            return base64_encode($signature);
        } else {
            throw new InvalidPrivateKey('Invalid private key');
        }
    }
}

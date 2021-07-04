<?php


namespace PayU\PaymentsApi\PaymentsV4\Services;

use PayU\Alu\MerchantConfig;
use PayU\PaymentsApi\PaymentsV4\PaymentsV4;

class HashService
{
    /**
     * @param MerchantConfig $merchantConfig
     * @param string $orderDate
     * @param string $httpMethod
     * @param string $basePath
     * @param string $jsonRequest
     * @return string
     */
    public function generateSignature($merchantConfig, $orderDate, $httpMethod, $basePath, $jsonRequest)
    {
        $stringToBeHashed =
            $merchantConfig->getMerchantCode() .
            $orderDate .
            $httpMethod .
            $basePath .
            '' .
            md5($jsonRequest);

        return hash_hmac("sha256", $stringToBeHashed, $merchantConfig->getSecretKey());
    }
}

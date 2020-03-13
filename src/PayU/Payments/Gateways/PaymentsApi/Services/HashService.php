<?php


namespace PaymentsApi\Services;


use PayU\Alu\Client;
use PayU\Alu\MerchantConfig;
use PayU\Alu\Request;
use PaymentsApi\PaymentsApiGateway;


class HashService
{
    /**
     * @var string
     */
    private $secretKey;

    /**
     * @param MerchantConfig $merchantConfig
     * @param string $orderDate
     * @param string $jsonRequest
     * @return string
     */
    public function generateSignature($merchantConfig, $orderDate, $jsonRequest)
    {
        $stringToBeHashed =
            $merchantConfig->getMerchantCode() .
            $orderDate .
            HTTPClient::POST_METHOD .
            PaymentsApiGateway::PAYMENTS_API_AUTHORIZE_PATH .
            '' .
            md5($jsonRequest);

        return hash_hmac("sha256", $stringToBeHashed, $merchantConfig->getSecretKey());
    }

    /**
     * @param string $secretKey
     */
    public function setSecretKey($secretKey)
    {
        $this->secretKey = $secretKey;
    }
}
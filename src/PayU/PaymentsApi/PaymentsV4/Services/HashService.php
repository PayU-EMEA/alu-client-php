<?php


namespace PayU\PaymentsApi\PaymentsV4\Services;

use PayU\Alu\MerchantConfig;
use PayU\PaymentsApi\PaymentsV4\PaymentsV4;

class HashService
{
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
            PaymentsV4::PAYMENTS_API_AUTHORIZE_PATH .
            '' .
            md5($jsonRequest);

        return hash_hmac("sha256", $stringToBeHashed, $merchantConfig->getSecretKey());
    }

    /**
     * @param array $requestArray
     * @param string $secretKey
     * @return string
     */
    public function generateTokenSignature($requestArray, $secretKey)
    {
        ksort($requestArray);
        $stringToBeHashed = '';
        foreach ($requestArray as $key => $val) {
            if ($key !== 'timestamp')
                $stringToBeHashed = $stringToBeHashed . $val;
        }
        $stringToBeHashed = $stringToBeHashed . $requestArray['timestamp'];

        return hash_hmac("sha256", $stringToBeHashed, $secretKey);
    }
}

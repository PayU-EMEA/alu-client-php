<?php


namespace PaymentsV4\Services;

use PayU\Alu\Exceptions\ClientException;
use PayU\Alu\Exceptions\ConnectionException;
use PayU\Alu\MerchantConfig;

class HTTPClient
{
    const POST_METHOD = "POST";

    /**
     * @var resource
     */
    private $handler;

    /**
     * @throws ClientException
     */
    public function __construct()
    {
        if (!function_exists('curl_init')) {
            throw new ClientException('CURL php extension is not available on your system');
        }

        $this->handler = curl_init();

        curl_setopt_array(
            $this->handler,
            array(
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_USERAGENT => 'ALU Client Library',
            )
        );
    }

    /**
     * @param $url
     * @param $requestBody
     * @param $requestHeaders
     * @return bool|string
     * @throws ConnectionException
     */
    public function post($url, $requestBody, $requestHeaders)
    {
        curl_setopt_array(
            $this->handler,
            array(
                CURLOPT_URL => $url,
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => $requestBody,
                CURLOPT_HTTPHEADER => $requestHeaders,
            )
        );

        $result = curl_exec($this->handler);
        if (curl_errno($this->handler) > 0) {
            throw new ConnectionException(
                sprintf(
                    'Curl error "%s" when accessing url: "%s"',
                    curl_error($this->handler),
                    $url
                )
            );
        }

        return $result;
    }

    /**
     * @param MerchantConfig $merchantConfig
     * @param string $orderDate
     * @param string $apiSignature
     * @return array
     */
    public function buildRequestHeaders($merchantConfig, $orderDate, $apiSignature)
    {
        return [
            'Accept: application/json',
            'X-Header-Signature:' . $apiSignature,
            'X-Header-Merchant:' . $merchantConfig->getMerchantCode(),
            'X-Header-Date:' . $orderDate,
            'Content-Type: application/json;charset=utf-8'
        ];
    }

    public function __destruct()
    {
        curl_close($this->handler);
    }
}

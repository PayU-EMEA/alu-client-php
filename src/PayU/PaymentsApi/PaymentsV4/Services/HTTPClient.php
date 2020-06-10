<?php


namespace PayU\PaymentsApi\PaymentsV4\Services;

use PayU\Alu\MerchantConfig;
use PayU\PaymentsApi\PaymentsV4\Exceptions\ConnectionException;
use PayU\PaymentsApi\PaymentsV4\Exceptions\HttpClientException;

class HTTPClient
{
    const POST_METHOD = "POST";

    /**
     * @var HashService
     */
    private $hashService;

    /**
     * @var resource
     */
    private $handler;

    /**
     * HTTPClient constructor.
     * @throws HttpClientException
     */
    public function __construct()
    {
        if (!function_exists('curl_init')) {
            throw new HttpClientException('CURL php extension is not available on your system');
        }

        $this->hashService = new HashService();
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
     * @param string $url
     * @param MerchantConfig $merchantConfig
     * @param string $orderDate
     * @param string $requestBody
     * @return bool|string
     * @throws ConnectionException
     */
    public function post(
        $url,
        MerchantConfig $merchantConfig,
        $orderDate,
        $requestBody
    ) {
        $signature = $this->hashService->generateSignature($merchantConfig, $orderDate, $requestBody);
        $requestHeaders = $this->buildRequestHeaders($merchantConfig->getMerchantCode(), $orderDate, $signature);

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
     * @param string $merchantCode
     * @param string $orderDate
     * @param string $signature
     * @return array
     */
    public function buildRequestHeaders($merchantCode, $orderDate, $signature)
    {
        return [
            'Accept: application/json',
            'X-Header-Signature:' . $signature,
            'X-Header-Merchant:' . $merchantCode,
            'X-Header-Date:' . $orderDate,
            'Content-Type: application/json;charset=utf-8'
        ];
    }

    /**
     * @param string $url
     * @param array $requestBody
     * @param string $secretKey
     * @return string
     * @throws ConnectionException
     */
    public function postTokenCreationRequest(
        $url,
        $requestBody,
        $secretKey
    ) {
        $signature = $this->hashService->generateTokenSignature($requestBody, $secretKey);
        $requestBody['signature'] = $signature;

        curl_setopt_array(
            $this->handler,
            array(
                CURLOPT_URL => $url,
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => http_build_query($requestBody),
                CURLOPT_HTTPHEADER => array('Content-Type: application/x-www-form-urlencoded')
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
}

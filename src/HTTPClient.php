<?php

namespace PayU\Alu;

use PayU\Alu\Exception\ClientException;
use PayU\Alu\Exception\ConnectionException;

class HTTPClient
{
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

        curl_setopt_array($this->handler, array(
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_USERAGENT => 'ALU Client Library',
        ));
    }

    public function skipSSLVerifyPeer()
    {
        curl_setopt($this->handler, CURLOPT_SSL_VERIFYPEER, false);
    }

    /**
     * @param $url
     * @param array $postParams
     * @throws ConnectionException
     * @return string
     */
    public function post($url, array $postParams)
    {
        curl_setopt_array($this->handler, array(
            CURLOPT_URL => $url,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => http_build_query($postParams),
        ));

        $result = curl_exec($this->handler);
        if (curl_errno($this->handler) > 0) {
            throw new ConnectionException(
                sprintf('Curl error "%s" when accessing url: "%s"', curl_error($this->handler), $url)
            );
        }
        return $result;
    }

    public function __destruct()
    {
        curl_close($this->handler);
    }
}

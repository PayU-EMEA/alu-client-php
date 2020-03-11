<?php


namespace PayU\Payments\Gateways\PaymentsApi\Services;


class ResponseParserJson
{
    /**
     * @var array
     */
    private $response;

    /**
     * @return array
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @param string $jsonResponse
     */
    public function parseJsonResponse($jsonResponse)
    {
        $this->response = json_decode($jsonResponse, true);
    }
}
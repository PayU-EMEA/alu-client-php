<?php

namespace PayU\PaymentsApi\PaymentsV4;

use PayU\Alu\Request;
use PayU\PaymentsApi\Exceptions\AuthorizationException;
use PayU\PaymentsApi\Interfaces\AuthorizationPaymentsApiClient;
use PayU\PaymentsApi\PaymentsV4\Exceptions\ConnectionException;
use PayU\PaymentsApi\PaymentsV4\Exceptions\HttpClientException;
use PayU\PaymentsApi\PaymentsV4\Exceptions\RequestBuilderException;
use PayU\PaymentsApi\PaymentsV4\Services\HTTPClient;
use PayU\PaymentsApi\PaymentsV4\Services\RequestBuilder;
use PayU\PaymentsApi\PaymentsV4\Services\ResponseBuilder;
use PayU\PaymentsApi\PaymentsV4\Exceptions\AuthorizationResponseException;
use PayU\PaymentsApi\PaymentsV4\Services\ResponseParser;

class PaymentsV4 implements AuthorizationPaymentsApiClient
{
    const PAYMENTS_API_AUTHORIZE_PATH = '/api/v4/payments/authorize';
    const API_VERSION_V4 = 'v4';

    /**
     * @var HTTPClient
     */
    private $httpClient;

    /**
     * @var RequestBuilder
     */
    private $requestBuilder;

    /**
     * @var ResponseParser
     */
    private $responseParser;

    /**
     * @var ResponseBuilder
     */
    private $responseBuilder;

    /**
     * PaymentsV4 constructor.
     * @throws HttpClientException
     */
    public function __construct()
    {
        $this->httpClient = new HTTPClient();
        $this->requestBuilder = new RequestBuilder();
        $this->responseParser = new ResponseParser();
        $this->responseBuilder = new ResponseBuilder();
    }

    /**
     * @param string $country
     * @return string
     * @throws AuthorizationException
     */
    private function getPaymentsUrl($country)
    {
        $platformHostname = [
            'ro' => 'https://secure.payu.ro',
            'ru' => 'https://secure.payu.ru',
            'ua' => 'https://secure.payu.ua',
            'hu' => 'https://secure.payu.hu',
            'tr' => 'https://secure.payu.com.tr',
        ];

        if (!isset($platformHostname[$country])) {
            throw new AuthorizationException('Invalid platform');
        }

        return $platformHostname[$country] . self::PAYMENTS_API_AUTHORIZE_PATH;
    }

    /**
     * @inheritDoc
     */
    public function authorize(Request $request)
    {
        try {
            $jsonRequest = $this->requestBuilder->buildAuthorizationRequest($request);
        } catch (RequestBuilderException $e) {
            throw new AuthorizationException($e->getMessage(), $e->getCode(), $e);
        }

        try {
            $responseJson = $this->httpClient->post(
                $this->getPaymentsUrl($request->getMerchantConfig()->getPlatform()),
                $request->getMerchantConfig(),
                $request->getOrder()->getOrderDate(),
                $jsonRequest
            );
        } catch (ConnectionException $e) {
            throw new AuthorizationException($e->getMessage() . ' ' . $e->getCode());
        }

        try {
            $authorizationResponse = $this->responseParser->parseJsonResponse($responseJson);
        } catch (AuthorizationResponseException $e) {
            throw new AuthorizationException($e->getMessage(), $e->getCode(), $e);
        }

        return $this->responseBuilder->build($authorizationResponse);
    }
}

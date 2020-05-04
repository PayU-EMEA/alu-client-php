<?php

namespace PayU\PaymentsApi\PaymentsV4;

use PayU\Alu\Request;
use PayU\Alu\Response;
use PayU\PaymentsApi\Exceptions\AuthorizationException;
use PayU\PaymentsApi\Interfaces\AuthorizationPaymentsApiClient;
use PayU\PaymentsApi\PaymentsV4\Exceptions\ConnectionException;
use PayU\PaymentsApi\PaymentsV4\Exceptions\RequestBuilderException;
use PayU\PaymentsApi\PaymentsV4\Services\HTTPClient;
use PayU\PaymentsApi\PaymentsV4\Services\RequestBuilder;
use PayU\PaymentsApi\PaymentsV4\Services\ResponseBuilder;
use PayU\PaymentsApi\PaymentsV4\Exceptions\AuthorizationResponseException;
use PayU\PaymentsApi\PaymentsV4\Services\ResponseParser;

class PaymentsV4 implements AuthorizationPaymentsApiClient
{
    const PAYMENTS_API_AUTHORIZE_PATH = '/api/v4/payments/authorize';
    const BASE_CREATE_TOKEN_PATH = '/order/token/v2/merchantToken';

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

    /** @var array */
    private $platformHostname = [
        'ro' => 'https://secure.payu.ro',
        'ru' => 'https://secure.payu.ru',
        'ua' => 'https://secure.payu.ua',
        'hu' => 'https://secure.payu.hu',
        'tr' => 'https://secure.payu.com.tr',
    ];

    /**
     * PaymentsV4 constructor.
     * @throws AuthorizationException
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
        if (!isset($this->platformHostname[$country])) {
            throw new AuthorizationException('Invalid platform');
        }

        return $this->platformHostname[$country] . self::PAYMENTS_API_AUTHORIZE_PATH;
    }

    /**
     * @param string $country
     * @return string
     * @throws AuthorizationException
     */
    private function getTokenUrl($country)
    {
        if (!isset($this->platformHostname[$country])) {
            throw new AuthorizationException('Invalid platform');
        }

        return $this->platformHostname[$country] . self::BASE_CREATE_TOKEN_PATH;
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

        $response = $this->responseBuilder->buildResponse($authorizationResponse);

        if (($response->getCode() === 200 || $response->getCode() === 202)
            && $request->getCard() !== null
            && $request->getCard()->isEnableTokenCreation()
        ) {
            return $this->makeTokenCreationRequest($request, $response);
        }

        return $response;
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     * @throws AuthorizationException
     */
    private function makeTokenCreationRequest(Request $request, Response $response)
    {
        try {
            $tokenRequest = $this->requestBuilder->buildTokenRequestBody(
                $request->getMerchantConfig()->getMerchantCode(),
                $response->getRefno()
            );
        } catch (\Exception $e) {
            throw new AuthorizationException($e->getMessage(), $e->getCode(), $e);
        }

        try {
            $responseJson = $this->httpClient->postTokenCreationRequest(
                $this->getTokenUrl($request->getMerchantConfig()->getPlatform()),
                $tokenRequest,
                $request->getMerchantConfig()->getSecretKey()
            );
        } catch (ConnectionException $e) {
            throw new AuthorizationException($e->getMessage(), $e->getCode(), $e);
        }

        try {
            $authorizationResponse = $this->responseParser->parseTokenJsonResponse($responseJson);
        } catch (AuthorizationResponseException $e) {
            throw new AuthorizationException($e->getMessage(), $e->getCode(), $e);
        }

        return $this->responseBuilder->buildTokenResponse($authorizationResponse, $response);
    }
}

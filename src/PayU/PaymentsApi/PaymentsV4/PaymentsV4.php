<?php

namespace PayU\PaymentsApi\PaymentsV4;

use PayU\Alu\Exceptions\ClientException;
use PayU\Alu\Exceptions\ConnectionException;
use PayU\Alu\Request;
use PayU\PaymentsApi\PaymentsV4\Services\HTTPClient;
use PayU\PaymentsApi\PaymentsV4\Services\RequestBuilder;
use PayU\PaymentsApi\PaymentsV4\Services\ResponseBuilder;
use PayU\PaymentsApi\Interfaces\AuthorizationInterface;
use PayU\PaymentsApi\PaymentsV4\Exceptions\AuthorizationResponseException;
use PayU\PaymentsApi\PaymentsV4\Services\ResponseParser;

class PaymentsV4 implements AuthorizationInterface
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
     * PaymentsApiGateway constructor.
     *
     * @throws ClientException
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
     * @throws ClientException
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
            throw new ClientException('Invalid platform');
        }

        return $platformHostname[$country] . self::PAYMENTS_API_AUTHORIZE_PATH;
    }

    /**
     * @inheritDoc
     */
    public function authorize(Request $request)
    {
        $jsonRequest = $this->requestBuilder->buildAuthorizationRequest($request);

        try {
            $responseJson = $this->httpClient->post(
                $this->getPaymentsUrl($request->getMerchantConfig()->getPlatform()),
                $request->getMerchantConfig(),
                $request->getOrder()->getOrderDate(),
                $jsonRequest
            );
        } catch (ClientException $e) {
            echo($e->getMessage() . ' ' . $e->getCode());
        } catch (ConnectionException $e) {
            echo($e->getMessage() . ' ' . $e->getCode());
        }

        try {
            $authorizationResponse = $this->responseParser->parseJsonResponse($responseJson);
        } catch (AuthorizationResponseException $exception) {
            throw new ClientException($exception->getMessage());
        }

        return $this->responseBuilder->buildResponse($authorizationResponse);
    }
}

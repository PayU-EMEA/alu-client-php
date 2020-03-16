<?php

namespace PaymentsApi;

use PaymentsApi\Services\ResponseParser;
use PayU\Payments\Interfaces\GatewayInterface;
use PayU\Alu\Exceptions\ClientException;
use PayU\Alu\Exceptions\ConnectionException;

use PayU\Alu\MerchantConfig;
use PayU\Alu\Request;
use PaymentsApi\Services\HashService;
use PaymentsApi\Services\HTTPClient;
use PaymentsApi\Services\RequestBuilder;
use PaymentsApi\Services\ResponseBuilder;


class PaymentsApiGateway implements GatewayInterface
{

    const PAYMENTS_API_AUTHORIZE_PATH = '/api/v4/payments/authorize';

    /**
     * @var array
     * todo set ro to original value
     */
    private $aluUrlHostname = array(
        //'ro' => 'https://secure.payu.ro',
        'ro' => 'http://ro.payu.local',
        'ru' => 'https://secure.payu.ru',
        'ua' => 'https://secure.payu.ua',
        'hu' => 'https://secure.payu.hu',
        'tr' => 'https://secure.payu.com.tr',
    );

    /**
     * @var string
     */
    private $customUrl = null;

    /**
     * @var HashService
     */
    private $hashService;

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
        $this->hashService = new HashService();
        $this->requestBuilder = new RequestBuilder();
        $this->responseParser = new ResponseParser();
        $this->responseBuilder = new ResponseBuilder();
    }

    /**
     * @param string $platform
     * @return string
     * @throws ClientException
     */
    private function getPaymentsUrl($platform)
    {
        if (!empty($this->customUrl)) {
            return $this->customUrl;
        }

        if (!isset($this->aluUrlHostname[$platform])) {
            throw new ClientException('Invalid platform');
        }

        return $this->aluUrlHostname[$platform] . self::PAYMENTS_API_AUTHORIZE_PATH;
    }

    /**
     * @inheritDoc
     */
    public function authorize(Request $request)
    {
        $jsonRequest = $this->requestBuilder->buildAuthorizationRequest($request);

        $apiSignature = $this->hashService->generateSignature(
            $request->getMerchantConfig(),
            $request->getOrder()->getOrderDate(),
            $jsonRequest
        );

        $headers = $this->httpClient->buildRequestHeaders(
            $request->getMerchantConfig(),
            $request->getOrder()->getOrderDate(),
            $apiSignature
        );

        try {
            $responseJson = $this->httpClient->post(
                $this->getPaymentsUrl($request->getMerchantConfig()->getPlatform()),
                $jsonRequest,
                $headers
            );
        } catch (ClientException $e) {
            echo($e->getMessage() . ' ' . $e->getCode());
        } catch (ConnectionException $e) {
            echo($e->getMessage() . ' ' . $e->getCode());
        }

        $authorizationResponse = $this->responseParser->parseJsonResponse($responseJson);

        return $this->responseBuilder->buildResponse($authorizationResponse);

        //todo token payment (another task)
//        // enable the token only if authorization was successful
//        if (($response->getCode() === 200 || $response->getCode() === 202)
//            && !is_null($request->getCard())
//            && $request->getCard()->isEnableTokenCreation()
//        ) {
//            return $this->createTokenRequest($request, $response, $httpClient, $hashService);
//        }
    }
}

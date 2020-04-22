<?php


namespace PayU\PaymentsApi\AluV3;

use PayU\Alu\Exceptions\ConnectionException;
use PayU\Alu\Request;
use PayU\PaymentsApi\AluV3\Exceptions\ResponseBuilderException;
use PayU\PaymentsApi\AluV3\Exceptions\ResponseParserException;
use PayU\PaymentsApi\AluV3\Services\HashService;
use PayU\PaymentsApi\AluV3\Services\HTTPClient;
use PayU\PaymentsApi\AluV3\Services\RequestBuilder;
use PayU\PaymentsApi\AluV3\Services\ResponseBuilder;
use PayU\PaymentsApi\AluV3\Services\ResponseParser;
use PayU\PaymentsApi\Exceptions\AuthorizationException;
use PayU\PaymentsApi\Interfaces\AuthorizationInterface;

final class AluV3 implements AuthorizationInterface
{
    const ALU_URL_PATH = '/order/alu/v3';
    const API_VERSION_V3 = "v3";

    /**
     * @var array
     */
    private $aluUrlHostname = [
        'ro' => 'https://secure.payu.ro',
        'ru' => 'https://secure.payu.ru',
        'tr' => 'https://secure.payu.com.tr',
    ];

    /**
     * @var HTTPClient
     */
    private $httpClient;

    /**
     * @var HashService
     */
    private $hashService;

    /**
     * @var RequestBuilder
     */
    private $requestBuilder;

    /**
     * @var ResponseBuilder
     */
    private $responseBuilder;

    /**
     * @var ResponseParser
     */
    private $responseParser;

    public function __construct(
        HTTPClient $httpClient,
        HashService $hashService
    ) {
        $this->httpClient = $httpClient;
        $this->hashService = $hashService;
        $this->requestBuilder = new RequestBuilder();
        $this->responseParser = new ResponseParser();
        $this->responseBuilder = new ResponseBuilder();
    }

    /**
     * @inheritDoc
     */
    public function authorize(Request $request)
    {
        $requestParams = $this->requestBuilder->buildAuthorizationRequest($request, $this->hashService);
        $url = $this->getAluUrl($request);
        try {
            $responseXML = $this->httpClient->post(
                $url,
                $requestParams
            );
        } catch (ConnectionException $e) {
            throw new AuthorizationException($e->getMessage(), $e->getCode(), $e);
        }

        try {
            $authorizationResponse = $this->responseParser->parseXMLResponse($responseXML);
        } catch (ResponseParserException $e) {
            throw new AuthorizationException($e->getMessage(), $e->getCode(), $e);
        }

        try {
            return $this->responseBuilder->buildResponse($authorizationResponse, $this->hashService);
        } catch (ResponseBuilderException $e) {
            throw new AuthorizationException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @param Request $request
     * @return string
     * @throws AuthorizationException
     */
    private function getAluUrl(Request $request)
    {
        if ($request->getCustomUrl() !== null) {
            return $request->getCustomUrl();
        }

        if (!isset($this->aluUrlHostname[$request->getMerchantConfig()->getPlatform()])) {
            throw new AuthorizationException('Invalid platform');
        }

        return $this->aluUrlHostname[$request->getMerchantConfig()->getPlatform()] . self::ALU_URL_PATH;
    }
}

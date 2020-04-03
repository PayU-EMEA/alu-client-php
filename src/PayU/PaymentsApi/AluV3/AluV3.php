<?php


namespace PayU\PaymentsApi\AluV3;

use PayU\Alu\Exceptions\ClientException;
use PayU\Alu\HashService;
use PayU\Alu\HTTPClient;
use PayU\Alu\Request;
use PayU\PaymentsApi\AluV3\Services\RequestBuilder;
use PayU\PaymentsApi\AluV3\Services\ResponseBuilder;
use PayU\PaymentsApi\AluV3\Services\ResponseParser;
use PayU\PaymentsApi\Interfaces\AuthorizationInterface;

final class AluV3 implements AuthorizationInterface
{
    const ALU_URL_PATH = '/order/alu/v3';
    const API_VERSION_V3 = "v3";

    /**
     * @var array
     * todo set ro to original value
     */
    private $aluUrlHostname = [
        'ro' => 'https://secure.payu.ro',
        'ru' => 'https://secure.payu.ru',
        'ua' => 'https://secure.payu.ua',
        'hu' => 'https://secure.payu.hu',
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
     * @throws ClientException
     */
    public function authorize(Request $request)
    {
        $requestParams = $this->requestBuilder->buildAuthorizationRequest($request, $this->hashService);

        try {
            $responseXML = $this->httpClient->post(
                $this->getAluUrl($request),
                $requestParams
            );
        } catch (\Exception $e) {
            throw new ClientException($e->getMessage(), $e->getCode(), $e);
        }

        try {
            $authorizationResponse = $this->responseParser->parseXMLResponse($responseXML);
        } catch (\Exception $e) {
            throw new ClientException($e->getMessage(), $e->getCode(), $e);
        }

        return $this->responseBuilder->buildResponse($authorizationResponse, $this->hashService);
    }

    /**
     * @param Request $request
     * @return string
     * @throws ClientException
     */
    private function getAluUrl(Request $request)
    {
        if ($request->getCustomUrl() !== null) {
            return $request->getCustomUrl();
        }

        if (!isset($this->aluUrlHostname[$request->getMerchantConfig()->getPlatform()])) {
            throw new ClientException('Invalid platform');
        }

        return $this->aluUrlHostname[$request->getMerchantConfig()->getPlatform()] . self::ALU_URL_PATH;
    }
}

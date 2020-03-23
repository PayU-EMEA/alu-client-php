<?php


namespace PayU\PaymentsApi\AluV3;

use PayU\Alu\Exceptions\ClientException;
use PayU\Alu\Exceptions\ConnectionException;
use PayU\Alu\HashService;
use PayU\Alu\HTTPClient;
use PayU\Alu\Request;
use PayU\PaymentsApi\AluV3\Services\RequestBuilder;
use PayU\PaymentsApi\AluV3\Services\ResponseBuilder;
use PayU\PaymentsApi\AluV3\Services\ResponseParser;
use PayU\PaymentsApi\Interfaces\AuthorizationInterface;

class AluV3 implements AuthorizationInterface
{
    const ALU_URL_PATH = '/order/alu/v3';
    const API_VERSION_V3 = "v3";

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
        $requestArray = $this->requestBuilder->buildAuthorizationRequest($request);

        $requestHash = $this->hashService->makeRequestHash(
            $requestArray,
            $request->getMerchantConfig()->getSecretKey()
        );

        $this->requestBuilder->setOrderHash($requestHash);

        $requestParams = $this->requestBuilder->buildAuthorizationRequest($request);
        try {
            $responseXML = $this->httpClient->post(
                $this->getAluUrl($request->getMerchantConfig()->getPlatform()),
                $requestParams
            );

            //todo remove after testing is done
            var_dump($responseXML);
        } catch (ConnectionException $e) {
            echo($e->getMessage() . ' ' . $e->getCode());
        } catch (\Exception $e) {
            throw new ClientException($e->getMessage(), $e->getCode());
        }

        $authorizationResponse = $this->responseParser->parseXMLResponse($responseXML);

        $response = $this->responseBuilder->buildResponse($authorizationResponse);

        if ('' != $response->getHash()) {
            try {
                $this->hashService->validateResponseHash($response);
            } catch (ClientException $e) {
                echo($e->getMessage() . ' ' . $e->getCode());
            }
        }

        return $response;
    }

    /**
     * @param string $platform
     * @return string
     * @throws ClientException
     */
    private function getAluUrl($platform)
    {
        if (!empty($this->customUrl)) {
            return $this->customUrl;
        }

        if (!isset($this->aluUrlHostname[$platform])) {
            throw new ClientException('Invalid platform');
        }
        return $this->aluUrlHostname[$platform] . self::ALU_URL_PATH;
    }

    /**
     * @param string $customUrl
     */
    public function setCustomUrl($customUrl)
    {
        $this->customUrl = $customUrl;
    }
}

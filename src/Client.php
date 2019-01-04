<?php

namespace PayU\Alu;

use PayU\Alu\Component\Request;
use PayU\Alu\Component\Response;
use PayU\Alu\Exception\ClientException;
use PayU\Alu\Parser\PaymentResponseParser;
use PayU\Alu\Parser\ThreeDSecureResponseParser;

/**
 * Class Client
 * @package PayU\Alu
 */
class Client
{
    /**
     * @var MerchantConfig
     */
    private $merchantConfig;

    /**
     * @var array
     */
    private $aluUrlHostname = array(
        'ro' => 'https://secure.payu.ro',
        'ru' => 'https://secure.payu.ru',
        'ua' => 'https://secure.payu.ua',
        'hu' => 'https://secure.payu.hu',
        'tr' => 'https://secure.payu.com.tr',
    );

    /**
     * @var string
     */
    private $aluUrlPath = '/order/alu/v3';

    /**
     * @var string
     */
    private $customUrl = null;

    /** @var HTTPClient */
    private $httpClient;

    /** @var HashService */
    private $hashService;

    /** @var PaymentResponseParser */
    private $paymentResponseParser;

    /** @var ThreeDSecureResponseParser */
    private $threeDSecureResponseParser;

    /**
     * @param MerchantConfig $merchantConfig
     */
    public function __construct(MerchantConfig $merchantConfig)
    {
        $this->merchantConfig = $merchantConfig;
        $this->httpClient = new HTTPClient();
        $this->hashService = new HashService($merchantConfig);
        $this->paymentResponseParser = new PaymentResponseParser($this->hashService);
        $this->threeDSecureResponseParser = new ThreeDSecureResponseParser($this->hashService);
    }

    /**
     * @return string
     * @throws ClientException
     */
    private function getAluUrl()
    {
        if (!empty($this->customUrl)) {
            return $this->customUrl;
        }

        if (!isset($this->aluUrlHostname[$this->merchantConfig->getPlatform()])) {
            throw new ClientException('Invalid platform');
        }
        return $this->aluUrlHostname[$this->merchantConfig->getPlatform()] . $this->aluUrlPath;
    }

    /**
     * @param $fullUrl
     * @codeCoverageIgnore
     */
    public function setCustomUrl($fullUrl)
    {
        $this->customUrl = $fullUrl;
    }


    /**
     * @param Request $request
     * @return Response
     * @throws ClientException
     */
    public function pay(Request $request)
    {
        $signedData = $this->hashService->sign($request);
        $rawResponse = $this->httpClient->post($this->getAluUrl(), $signedData);
        return $this->paymentResponseParser->parse($rawResponse);
    }

    /**
     * @param array $returnData
     * @return Response
     * @throws ClientException
     */
    public function handleThreeDSReturnResponse(array $returnData = array())
    {
        return $this->threeDSecureResponseParser->parse($returnData);
    }
}

<?php

namespace PayU\Alu;

use PayU\Alu\Exceptions\ClientException;
use PayU\Alu\Exceptions\ConnectionException;
use SimpleXMLElement;

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

    /**
     * @param MerchantConfig $merchantConfig
     */
    public function __construct(MerchantConfig $merchantConfig)
    {
        $this->merchantConfig = $merchantConfig;
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
     * @param SimpleXMLElement $xmlObject
     * @param SimpleXMLElement $xmlObject
     * @return Response
     */
    private function getResponse(SimpleXMLElement $xmlObject)
    {
        $response = new Response();
        $response->setRefno((string) $xmlObject->REFNO);
        $response->setAlias((string) $xmlObject->ALIAS);
        $response->setStatus((string) $xmlObject->STATUS);
        $response->setReturnCode((string) $xmlObject->RETURN_CODE);
        $response->setReturnMessage((string) $xmlObject->RETURN_MESSAGE);
        $response->setDate((string) $xmlObject->DATE);

        if (property_exists($xmlObject, 'HASH')) {
            $response->setHash((string)$xmlObject->HASH);
        }

        // for 3D secure handling flow
        if (property_exists($xmlObject, 'URL_3DS')) {
            $response->setThreeDsUrl((string)$xmlObject->URL_3DS);
        }

        // 4 parameters used only on TR platform for ALU v1, v2 and v3
        if (property_exists($xmlObject, 'AMOUNT')) {
            $response->setAmount((string)$xmlObject->AMOUNT);
        }
        if (property_exists($xmlObject, 'CURRENCY')) {
            $response->setCurrency((string)$xmlObject->CURRENCY);
        }
        if (property_exists($xmlObject, 'INSTALLMENTS_NO')) {
            $response->setInstallmentsNo((string)$xmlObject->INSTALLMENTS_NO);
        }
        if (property_exists($xmlObject, 'CARD_PROGRAM_NAME')) {
            $response->setCardProgramName((string)$xmlObject->CARD_PROGRAM_NAME);
        }

        // parameters used on ALU v2 and v3
        if (property_exists($xmlObject, 'ORDER_REF')) {
            $response->setOrderRef((string) $xmlObject->ORDER_REF);
        }
        if (property_exists($xmlObject, 'AUTH_CODE')) {
            $response->setAuthCode((string)$xmlObject->AUTH_CODE);
        }
        if (property_exists($xmlObject, 'RRN')) {
            $response->setRrn((string)$xmlObject->RRN);
        }

        $response->parseAdditionalParameters($xmlObject);

        if (property_exists($xmlObject, 'TOKEN_HASH')) {
            $response->setTokenHash((string)$xmlObject->TOKEN_HASH);
        }

        // parameters used for wire payments on ALU v3
        if (property_exists($xmlObject, 'WIRE_ACCOUNTS') && count($xmlObject->WIRE_ACCOUNTS->ITEM) > 0) {
            foreach ($xmlObject->WIRE_ACCOUNTS->ITEM as $account) {
                $response->addWireAccount($this->getResponseWireAccount($account));
            }
        }

        return $response;
    }

    /**
     * @param SimpleXMLElement $account
     * @return ResponseWireAccount
     */
    private function getResponseWireAccount(SimpleXMLElement $account)
    {
        $responseWireAccount = new ResponseWireAccount();
        $responseWireAccount->setBankIdentifier((string)$account->BANK_IDENTIFIER);
        $responseWireAccount->setBankAccount((string)$account->BANK_ACCOUNT);
        $responseWireAccount->setRoutingNumber((string)$account->ROUTING_NUMBER);
        $responseWireAccount->setIbanAccount((string)$account->IBAN_ACCOUNT);
        $responseWireAccount->setBankSwift((string)$account->BANK_SWIFT);
        $responseWireAccount->setCountry((string)$account->COUNTRY);
        $responseWireAccount->setWireRecipientName((string)$account->WIRE_RECIPIENT_NAME);
        $responseWireAccount->setWireRecipientVatId((string)$account->WIRE_RECIPIENT_VAT_ID);

        return $responseWireAccount;
    }

    /**
     * @param Request $request
     * @param HTTPClient $httpClient
     * @param HashService $hashService
     * @throws ClientException
     * @throws ConnectionException
     * @return Response
     */
    public function pay(Request $request, HTTPClient $httpClient = null, HashService $hashService = null)
    {
        if (null === $hashService) {
            $hashService = new HashService($this->merchantConfig->getSecretKey());
        }

        if (null === $httpClient) {
            $httpClient = new HTTPClient();
        }

        $requestHash = $hashService->makeRequestHash($request);
        $request->setOrderHash($requestHash);

        $requestParams = $request->getRequestParams();
        $responseXML = $httpClient->post($this->getAluUrl(), $requestParams);
        try {
            $xmlObject = new SimpleXMLElement($responseXML);
        } catch (\Exception $e) {
            throw new ClientException($e->getMessage(), $e->getCode());
        }
        $response = $this->getResponse($xmlObject);
        if ('' != $response->getHash()) {
            $hashService->validateResponseHash($response);
        }
        return $response;
    }

    /**
     * @param array $returnData
     * @return Response
     * @throws ClientException
     */
    public function handleThreeDSReturnResponse(array $returnData = array())
    {
        if (!empty($returnData['HASH'])) {
            $hashService = new HashService($this->merchantConfig->getSecretKey());
            $threeDSReturnResponse = $this->getThreeDSReturnResponse($returnData);
            $hashService->validateResponseHash($threeDSReturnResponse);
        } else {
            throw new ClientException('Missing HASH');
        }
        return $threeDSReturnResponse;
    }

    /**
     * @param array $returnData
     * @return Response
     */
    private function getThreeDSReturnResponse(array $returnData = array())
    {
        $response = new Response();
        $response->setRefno($returnData['REFNO']);
        $response->setAlias($returnData['ALIAS']);
        $response->setStatus($returnData['STATUS']);
        $response->setReturnCode($returnData['RETURN_CODE']);
        $response->setReturnMessage($returnData['RETURN_MESSAGE']);
        $response->setDate($returnData['DATE']);

        $response->setHash($returnData['HASH']);

        if (array_key_exists('AMOUNT', $returnData)) {
            $response->setAmount($returnData['AMOUNT']);
        }
        if (array_key_exists('CURRENCY', $returnData)) {
            $response->setCurrency($returnData['CURRENCY']);
        }
        if (array_key_exists('INSTALLMENTS_NO', $returnData)) {
            $response->setInstallmentsNo($returnData['INSTALLMENTS_NO']);
        }
        if (array_key_exists('CARD_PROGRAM_NAME', $returnData)) {
            $response->setCardProgramName($returnData['CARD_PROGRAM_NAME']);
        }

        if (array_key_exists('ORDER_REF', $returnData)) {
            $response->setOrderRef($returnData['ORDER_REF']);
        }
        if (array_key_exists('AUTH_CODE', $returnData)) {
            $response->setAuthCode($returnData['AUTH_CODE']);
        }
        if (array_key_exists('RRN', $returnData)) {
            $response->setRrn($returnData['RRN']);
        }

        $response->parseAdditionalParameters($returnData);

        if (array_key_exists('TOKEN_HASH', $returnData)) {
            $response->setTokenHash($returnData['TOKEN_HASH']);
        }

        if (array_key_exists('WIRE_ACCOUNTS', $returnData)
            && is_array($returnData['WIRE_ACCOUNTS'])
        ) {
            foreach ($returnData['WIRE_ACCOUNTS'] as $wireAccount) {
                $response->addWireAccount($this->getResponseWireAccountFromArray($wireAccount));
            }
        }

        return $response;
    }

    /**
     * @param array $wireAccount
     * @return ResponseWireAccount
     */
    private function getResponseWireAccountFromArray(array $wireAccount)
    {
        $responseWireAccount = new ResponseWireAccount();
        if (array_key_exists('BANK_IDENTIFIER', $wireAccount)) {
            $responseWireAccount->setBankIdentifier($wireAccount['BANK_IDENTIFIER']);
        }
        if (array_key_exists('BANK_ACCOUNT', $wireAccount)) {
            $responseWireAccount->setBankIdentifier($wireAccount['BANK_ACCOUNT']);
        }
        if (array_key_exists('ROUTING_NUMBER', $wireAccount)) {
            $responseWireAccount->setBankIdentifier($wireAccount['ROUTING_NUMBER']);
        }
        if (array_key_exists('IBAN_ACCOUNT', $wireAccount)) {
            $responseWireAccount->setBankIdentifier($wireAccount['IBAN_ACCOUNT']);
        }
        if (array_key_exists('BANK_SWIFT', $wireAccount)) {
            $responseWireAccount->setBankIdentifier($wireAccount['BANK_SWIFT']);
        }
        if (array_key_exists('COUNTRY', $wireAccount)) {
            $responseWireAccount->setBankIdentifier($wireAccount['COUNTRY']);
        }
        if (array_key_exists('WIRE_RECIPIENT_NAME', $wireAccount)) {
            $responseWireAccount->setBankIdentifier($wireAccount['WIRE_RECIPIENT_NAME']);
        }
        if (array_key_exists('WIRE_RECIPIENT_VAT_ID', $wireAccount)) {
            $responseWireAccount->setBankIdentifier($wireAccount['WIRE_RECIPIENT_VAT_ID']);
        }

        return $responseWireAccount;
    }
}

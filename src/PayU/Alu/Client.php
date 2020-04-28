<?php

namespace PayU\Alu;

use PayU\Alu\Exceptions\ClientException;
use PayU\PaymentsApi\Exceptions\AuthorizationException;
use PayU\PaymentsApi\Exceptions\AuthorizationPaymentsApiClientFactoryException;
use PayU\PaymentsApi\Factories\AuthorizationPaymentsApiClientFactory;

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
     * @var string
     */
    private $customUrl = null;

    /** @var AuthorizationPaymentsApiClientFactory */
    private $authorizationPaymentsApiFactory;

    /**
     * @param MerchantConfig $merchantConfig
     */
    public function __construct(MerchantConfig $merchantConfig)
    {
        $this->merchantConfig = $merchantConfig;
        $this->authorizationPaymentsApiFactory = new AuthorizationPaymentsApiClientFactory();
    }

    /**
     * @deprecated Should use \PayU\Alu\Request::setCustomUrl instead for further usage
     * @param string $fullUrl
     * @codeCoverageIgnore
     */
    public function setCustomUrl($fullUrl)
    {
        $this->customUrl = $fullUrl;
    }

    /**
     * Method responsible with making an authorization call, based on the payments API version, which should be set
     * before on \PayU\Alu\Request instance.
     * Depending on the API version, an AuthorizationPaymentsApiClient implementation is instantiated by
     * AuthorizationPaymentsApiFactory and used to place the authorization call.
     * @param Request $request
     * @param HTTPClient $httpClient
     * @param HashService $hashService
     * @return Response
     * @throws ClientException
     */
    public function pay(Request $request, HTTPClient $httpClient = null, HashService $hashService = null)
    {
        if ($this->customUrl !== null) {
            $request->setCustomUrl($this->customUrl);
        }

        try {
            $paymentsApiClient = $this->authorizationPaymentsApiFactory->createPaymentsApiClient(
                $request->getPaymentsApiVersion(),
                $this->merchantConfig->getSecretKey(),
                $httpClient,
                $hashService
            );
        } catch (AuthorizationPaymentsApiClientFactoryException $exception) {
            throw new ClientException($exception->getMessage());
        }

        try {
            $response = $paymentsApiClient->authorize($request);
        } catch (AuthorizationException $e) {
            throw new ClientException($e->getMessage(), $e->getCode(), $e);
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

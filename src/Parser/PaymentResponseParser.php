<?php
namespace PayU\Alu\Parser;

use PayU\Alu\Exception\ClientException;
use PayU\Alu\Component\Response;
use PayU\Alu\Component\ResponseWireAccount;
use \SimpleXMLElement;

/**
 * Class PaymentResponseParser
 * @package Payu\Alu\Parser
 */
class PaymentResponseParser extends AbstractParser
{
    /**
     * @param $data
     * @return Response
     * @throws ClientException
     */
    public function parse($data)
    {
        libxml_use_internal_errors(true);
        try {
            $xmlObject = new SimpleXMLElement($data);
        } catch (\Exception $e) {
            throw new ClientException($e->getMessage(), $e->getCode());
        }

        $response = $this->prepareResponse($xmlObject);

        if (!empty($response->getHash())) {
            $this->hashService->validateResponse($response);
        }

        return $response;
    }

    /**
     * @param SimpleXMLElement $xmlObject
     * @param SimpleXMLElement $xmlObject
     * @return Response
     */
    private function prepareResponse(SimpleXMLElement $xmlObject)
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

        $response->setIsThreeDs($response->getStatus() == 'SUCCESS' &&
            $response->getReturnCode() == '3DS_ENROLLED' &&
            !empty($response->getThreeDsUrl()));

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

        if (property_exists($xmlObject, 'URL_REDIRECT')) {
            $response->setUrlRedirect((string)$xmlObject->URL_REDIRECT);
        }

        $response->setAdditionalResponseParameters($this->parseAdditionalParameters((array) $xmlObject));

        if (property_exists($xmlObject, 'TOKEN_HASH')) {
            $response->setTokenHash((string)$xmlObject->TOKEN_HASH);
        }

        // parameters used for wire payments on ALU v3
        if (property_exists($xmlObject, 'WIRE_ACCOUNTS') && count($xmlObject->WIRE_ACCOUNTS->ITEM) > 0) {
            foreach ($xmlObject->WIRE_ACCOUNTS->ITEM as $account) {
                $response->addWireAccount($this->createWiredAccount((array) $account));
            }
        }

        return $response;
    }
}
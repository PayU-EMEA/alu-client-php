<?php
namespace PayU\Alu\Parser;

use PayU\Alu\Component\Response;
use PayU\Alu\Exception\ClientException;
use SimpleXMLElement;

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
        $hash = $response->getHash();
        if (!empty($hash)) {
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

        //NOTE: HHVM can't cover functional expectations of some functions like get_object_vars or property_exists
        // although they have different behaviour on standard php versions. So we needed to replace property_exists
        // usages with isset.

        if (isset($xmlObject->HASH)) {
            $response->setHash((string)$xmlObject->HASH);
        }

        // for 3D secure handling flow
        if (isset($xmlObject->URL_3DS)) {
            $response->setThreeDsUrl((string)$xmlObject->URL_3DS);
        }

        $threeDsUrl = $response->getThreeDsUrl();
        $response->setIsThreeDs($response->getStatus() == 'SUCCESS' &&
            $response->getReturnCode() == '3DS_ENROLLED' &&
            !empty($threeDsUrl));

        // 4 parameters used only on TR platform for ALU v1, v2 and v3
        if (isset($xmlObject->AMOUNT)) {
            $response->setAmount((string)$xmlObject->AMOUNT);
        }
        if (isset($xmlObject->CURRENCY)) {
            $response->setCurrency((string)$xmlObject->CURRENCY);
        }
        if (isset($xmlObject->INSTALLMENTS_NO)) {
            $response->setInstallmentsNo((string)$xmlObject->INSTALLMENTS_NO);
        }
        if (isset($xmlObject->CARD_PROGRAM_NAME)) {
            $response->setCardProgramName((string)$xmlObject->CARD_PROGRAM_NAME);
        }
        // parameters used on ALU v2 and v3
        if (isset($xmlObject->ORDER_REF)) {
            $response->setOrderRef((string) $xmlObject->ORDER_REF);
        }
        if (isset($xmlObject->AUTH_CODE)) {
            $response->setAuthCode((string)$xmlObject->AUTH_CODE);
        }
        if (isset($xmlObject->RRN)) {
            $response->setRrn((string)$xmlObject->RRN);
        }

        if (isset($xmlObject->URL_REDIRECT)) {
            $response->setUrlRedirect((string)$xmlObject->URL_REDIRECT);
        }

        $response->setAdditionalResponseParameters($this->parseAdditionalParameters((array) $xmlObject));

        if (isset($xmlObject->TOKEN_HASH)) {
            $response->setTokenHash((string)$xmlObject->TOKEN_HASH);
        }

        // parameters used for wire payments on ALU v3
        if (isset($xmlObject->WIRE_ACCOUNTS) && count($xmlObject->WIRE_ACCOUNTS->ITEM) > 0) {
            foreach ($xmlObject->WIRE_ACCOUNTS->ITEM as $account) {
                $response->addWireAccount($this->createWiredAccount((array) $account));
            }
        }

        return $response;
    }
}

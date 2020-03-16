<?php


namespace AluV3\Services;

use AluV3\Entities\AuthorizationResponse;
use PayU\Alu\Exceptions\ClientException;
use PayU\Alu\Response;
use PayU\Alu\ResponseWireAccount;

class ResponseBuilder
{

    /**
     * @param AuthorizationResponse $response
     * @return Response
     */
    public function buildResponse(AuthorizationResponse $response)
    {
        $xmlObject = $response->getResponse();
        $response = new Response();
        $response->setRefno((string)$xmlObject->REFNO);
        $response->setAlias((string)$xmlObject->ALIAS);
        $response->setStatus((string)$xmlObject->STATUS);
        $response->setReturnCode((string)$xmlObject->RETURN_CODE);
        $response->setReturnMessage((string)$xmlObject->RETURN_MESSAGE);
        $response->setDate((string)$xmlObject->DATE);

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
            $response->setOrderRef((string)$xmlObject->ORDER_REF);
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
}

<?php


namespace PayU\PaymentsApi\PaymentsV4\Services;

use PayU\Alu\Response;
use PayU\Alu\ResponseWireAccount;
use PayU\PaymentsApi\PaymentsV4\Entities\AuthorizationResponse;

class ResponseBuilder
{
    /**
     * @param AuthorizationResponse $authorizationResponse
     * @return Response
     */
    public function buildResponse(AuthorizationResponse $authorizationResponse)
    {
        return $this->build($authorizationResponse);
    }

    /**
     * @param AuthorizationResponse $authorizationResponse
     * @return Response
     */
    private function build(AuthorizationResponse $authorizationResponse)
    {
        $responseArray = $authorizationResponse->getResponse();

        $response = new Response();
        $response->setCode($responseArray['CODE']);
        $response->setRefno($responseArray['REFNO']);
        $response->setAlias($responseArray['ALIAS']);
        $response->setStatus($responseArray['STATUS']);
        $response->setReturnCode($responseArray['RETURN_CODE']);
        $response->setReturnMessage($responseArray['RETURN_MESSAGE']);
        $response->setDate($responseArray['DATE']);

        // for 3D secure handling flow
        if ($responseArray['URL_3DS'] !== null) {
            $response->setThreeDsUrl($responseArray['URL_3DS']);
        }

        // 4 parameters used only on TR platform for ALU v1, v2 and v3
        if ($responseArray['AMOUNT'] !== null) {
            $response->setAmount($responseArray['AMOUNT']);
        }
        if ($responseArray['CURRENCY'] !== null) {
            $response->setCurrency($responseArray['CURRENCY']);
        }
        if ($responseArray['INSTALLMENTS_NO'] !== null) {
            $response->setInstallmentsNo($responseArray['INSTALLMENTS_NO']);
        }
        if ($responseArray['CARD_PROGRAM_NAME'] !== null) {
            $response->setCardProgramName($responseArray['CARD_PROGRAM_NAME']);
        }

        // parameters used on ALU v2 and v3
        if ($responseArray['ORDER_REF'] !== null) {
            $response->setOrderRef($responseArray['ORDER_REF']);
        }
        if ($responseArray['AUTH_CODE'] !== null) {
            $response->setAuthCode($responseArray['AUTH_CODE']);
        }
        if ($responseArray['RRN'] !== null) {
            $response->setRrn($responseArray['RRN']);
        }
        if ($responseArray['URL_REDIRECT'] !== null) {
            $response->setUrlRedirect($responseArray['URL_REDIRECT']);
        }

        $response->parseAdditionalParameters($responseArray);

        // parameters used for wire payments on ALU v3
        if ($responseArray['WIRE_ACCOUNTS'] !== null && count($responseArray['WIRE_ACCOUNTS']) > 0) {
            foreach ($responseArray['WIRE_ACCOUNTS'] as $account) {
                $response->addWireAccount($this->getResponseWireAccount($account));
            }
        }

        return $response;
    }

    /**
     * @param array $account
     * @return ResponseWireAccount
     */
    private function getResponseWireAccount($account)
    {
        $responseWireAccount = new ResponseWireAccount();
        $responseWireAccount->setBankIdentifier($account['BANK_IDENTIFIER']);
        $responseWireAccount->setBankAccount($account['BANK_ACCOUNT']);
        $responseWireAccount->setRoutingNumber($account['ROUTING_NUMBER']);
        $responseWireAccount->setIbanAccount($account['IBAN_ACCOUNT']);
        $responseWireAccount->setBankSwift($account['BANK_SWIFT']);
        $responseWireAccount->setCountry($account['COUNTRY']);
        $responseWireAccount->setWireRecipientName($account['WIRE_RECIPIENT_NAME']);
        $responseWireAccount->setWireRecipientVatId($account['WIRE_RECIPIENT_VAT_ID']);

        return $responseWireAccount;
    }

    /**
     * @param AuthorizationResponse $authorizationResponse
     * @param Response $response
     * @return Response
     */
    public function buildTokenResponse(
        AuthorizationResponse $authorizationResponse,
        Response $response
    ) {
        $responseArray = $authorizationResponse->getResponse();

        if ($responseArray["meta"]["status"]["code"] === 0) {
            $response->setTokenHash($responseArray["response"]["token"]);
        }
        $response->setTokenCode($responseArray["meta"]["status"]["code"]);
        $response->setTokenMessage($responseArray["meta"]["status"]["message"]);

        return $response;
    }
}

<?php


namespace PayU\PaymentsApi\PaymentsV4\Services;

use PayU\Alu\Response;
use PayU\Alu\ResponseWireAccount;
use PayU\PaymentsApi\PaymentsV4\Entities\Response\AuthorizationResponse;

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
        if (isset($responseArray['CODE'])) {
            $response->setCode($responseArray['CODE']);
        }

        if (isset($responseArray['STATUS'])) {
            $response->setStatus($responseArray['STATUS']);
        }

        if (isset($responseArray['RETURN_MESSAGE'])) {
            $response->setReturnMessage($responseArray['RETURN_MESSAGE']);
        }

        if (isset($responseArray['REFNO'])) {
            $response->setRefno($responseArray['REFNO']);
        }

        if (isset($responseArray['ORDER_REF'])) {
            $response->setOrderRef($responseArray['ORDER_REF']);
        }

        if (isset($responseArray['AMOUNT'])) {
            $response->setAmount($responseArray['AMOUNT']);
        }

        if (isset($responseArray['CURRENCY'])) {
            $response->setCurrency($responseArray['CURRENCY']);
        }

        // PaymentResult object
        if (isset($responseArray['RETURN_CODE'])) {
            $response->setReturnCode($responseArray['RETURN_CODE']);
        }
        if (isset($responseArray['AUTH_CODE'])) {
            $response->setAuthCode($responseArray['AUTH_CODE']);
        }
        if (isset($responseArray['RRN'])) {
            $response->setRrn($responseArray['RRN']);
        }
        if (isset($responseArray['INSTALLMENTS_NO'])) {
            $response->setInstallmentsNo($responseArray['INSTALLMENTS_NO']);
        }
        if (isset($responseArray['CARD_PROGRAM_NAME'])) {
            $response->setCardProgramName($responseArray['CARD_PROGRAM_NAME']);
        }

        if (isset($responseArray['URL_REDIRECT'])) {
            $response->setUrlRedirect($responseArray['URL_REDIRECT']);
            $response->setThreeDsUrl($responseArray['URL_REDIRECT']);
        }

        $response->parseAdditionalParameters($responseArray);

        // parameters used for wire payments on ALU v3
        if (isset($responseArray['WIRE_ACCOUNTS']) && count($responseArray['WIRE_ACCOUNTS']) > 0) {
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

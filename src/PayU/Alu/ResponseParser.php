<?php


namespace PayU\Alu;


class ResponseParser
{
    /**
     * @param string $jsonResponse
     * @return Response
     */
    public function parseJsonResponse($jsonResponse)
    {
        $responseArray = json_decode($jsonResponse, true);
        // todo rename from parse to build
        switch ($responseArray['code']) {
            case 200:
            case 202:
                return $this->parseSuccessResponse($responseArray);
            case 400:
            case 401:
            case 403:
            case 409:
            case 412:
            case 404:
                return $this->parseInvalidParametersResponse($responseArray);
            case 429:
                return $this->parseTooManyRequestsResponse($responseArray);
            case 500:
            case 502:
                return $this->parseInternalErrorResponse($responseArray);
        }
    }

    /**
     * @param array $responseArray
     * @return Response
     */
    private function parseSuccessResponse($responseArray)
    {
        $response = new Response();

        $response->setCode($responseArray['code']);
        $response->setReturnMessage($responseArray['message']);
        $response->setRefno($responseArray['payuPaymentReference']);

        if (isset($responseArray['merchantPaymentReference']))
            $response->setOrderRef($responseArray['merchantPaymentReference']);

        $response->setAmount($responseArray['amount']);

        if (isset($responseArray['currency']))
            $response->setCurrency($responseArray['currency']);

        if (isset($responseArray['paymentResult'])) {

            // paymentResponse object in AluV4
            $response->setReturnCode($responseArray['paymentResult']['payuResponseCode']);
            $response->setAuthCode($responseArray['paymentResult']['authCode']);
            $response->setRrn($responseArray['paymentResult']['rrn']);
            $response->setInstallmentsNo($responseArray['paymentResult']['installmentsNumber']);
            $response->setCardProgramName($responseArray['paymentResult']['cardProgramName']);
            $response->setType($responseArray['paymentResult']['type']);
            $response->setUrlRedirect($responseArray['paymentResult']['url']);

            $additionalResponseParameters = [];

            // bankResponseDetails object in paymentResult
            if (isset($responseArray['paymentResult']['bankResponseDetails'])) {
                $additionalResponseParameters['CLIENTID'] = $responseArray['paymentResult']['bankResponseDetails']['terminalId'];

                // response object in bankResponseDetails
                if (isset($responseArray['paymentResult']['bankResponseDetails']['response'])) {
                    $additionalResponseParameters['PROCRETURNCODE'] = $responseArray['paymentResult']['bankResponseDetails']['response']['code'];
                    $additionalResponseParameters['ERRORMESSAGE'] = $responseArray['paymentResult']['bankResponseDetails']['response']['message'];
                    $additionalResponseParameters['RESPONSE'] = $responseArray['paymentResult']['bankResponseDetails']['response']['status'];
                }

                $additionalResponseParameters['HOSTREFNUM'] = $responseArray['paymentResult']['bankResponseDetails']['hostRefNum'];
                $additionalResponseParameters['BANK_MERCHANT_ID'] = $responseArray['paymentResult']['bankResponseDetails']['merchantId'];
                $additionalResponseParameters['TERMINAL_BANK'] = $responseArray['paymentResult']['bankResponseDetails']['shortName'];
                $additionalResponseParameters['TX_REFNO'] = $responseArray['paymentResult']['bankResponseDetails']['txRefNo'];
                $additionalResponseParameters['OID'] = $responseArray['paymentResult']['bankResponseDetails']['oid'];
                $additionalResponseParameters['TRANSID'] = $responseArray['paymentResult']['bankResponseDetails']['transId'];
            }

            // cardDetails object in paymentResult
            if (isset($responseArray['paymentResult']['cardDetails'])) {
                $additionalResponseParameters['PAN'] = $responseArray['paymentResult']['cardDetails']['pan'];
                $additionalResponseParameters['EXPYEAR'] = $responseArray['paymentResult']['cardDetails']['expiryYear'];
                $additionalResponseParameters['EXPMONTH'] = $responseArray['paymentResult']['cardDetails']['expiryMonth'];
            }

            // 3dsDetails object in paymentResult
            if (isset($responseArray['paymentResult']['3dsDetails'])) {
                $additionalResponseParameters['MDSTATUS'] = $responseArray['paymentResult']['3dsDetails']['mdStatus'];
                $additionalResponseParameters['MDERRORMSG'] = $responseArray['paymentResult']['3dsDetails']['errorMessage'];
                $additionalResponseParameters['TXSTATUS'] = $responseArray['paymentResult']['3dsDetails']['txStatus'];
                $additionalResponseParameters['XID'] = $responseArray['paymentResult']['3dsDetails']['xid'];
                $additionalResponseParameters['ECI'] = $responseArray['paymentResult']['3dsDetails']['eci'];
                $additionalResponseParameters['CAVV'] = $responseArray['paymentResult']['3dsDetails']['cavv'];
                $response->setThreeDsUrl($responseArray['paymentResult']['url']);
            }

            // wireAccounts object in paymentResult
            if (isset($responseArray['paymentResult']['wireAccounts'])) {
                $wireAccounts = [];
                $cnt = 0;
                foreach ($responseArray['paymentResult']['wireAccounts'] as $account) {
                    $wireAccount = new ResponseWireAccount();

                    $wireAccount->setBankIdentifier($account['bankIdentifier']);
                    $wireAccount->setBankAccount($account['bankAccount']);
                    $wireAccount->setRoutingNumber($account['routingNumber']);
                    $wireAccount->setIbanAccount($account['ibanAccount']);
                    $wireAccount->setBankSwift($account['bankSwift']);
                    $wireAccount->setCountry($account['country']);
                    $wireAccount->setWireRecipientName($account['recipientName']);
                    $wireAccount->setWireRecipientVatId($account['recipientVatId']);

                    $wireAccounts[$cnt++] = $wireAccount;
                }

                $response->setWireAccounts($wireAccounts);
            }
        }

        return $response;
    }

    /**
     * @param array $responseArray
     * @return Response
     */
    private function parseTooManyRequestsResponse($responseArray)
    {
        $response = new Response();

        $response->setCode($responseArray['code']);
        $response->setReturnMessage($responseArray['message']);
        $response->setStatus($responseArray['status']);

        return $response;
    }

    /**
     * @param array $responseArray
     * @return Response
     */
    private function parseInvalidParametersResponse($responseArray)
    {
        $response = new Response();

        $response->setCode($responseArray['code']);
        $response->setReturnMessage($responseArray['message']);

        return $response;
    }

    /**
     * @param $responseArray
     * @return Response
     */
    private function parseInternalErrorResponse($responseArray)
    {
        $response = new Response();

        $response->setCode($responseArray['code']);
        $response->setReturnMessage($responseArray['message']);
        $response->setRefno($responseArray['payuPaymentReference']);

        return $response;
    }

}
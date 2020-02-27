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
                return $this->buildSuccessResponse($responseArray);
            case 400:
            case 401:
            case 403:
            case 409:
            case 412:
            case 404:
                return $this->buildInvalidParametersResponse($responseArray);
            case 429:
                return $this->buildTooManyRequestsResponse($responseArray);
            case 500:
            case 502:
                return $this->buildInternalErrorResponse($responseArray);
        }
    }

    /**
     * @param array $responseArray
     * @return Response
     */
    private function buildSuccessResponse($responseArray)
    {
        $response = new Response();
        //todo remove after code is tested
        var_dump($responseArray);
        $response->setCode($responseArray['code']);
        $response->setReturnMessage($responseArray['message']);
        $response->setRefno($responseArray['payuPaymentReference']);

        if (isset($responseArray['merchantPaymentReference'])) {
            $response->setOrderRef($responseArray['merchantPaymentReference']);
        }

        $response->setAmount($responseArray['amount']);

        if (isset($responseArray['currency'])) {
            $response->setCurrency($responseArray['currency']);
        }

        if (isset($responseArray['paymentResult'])) {
            // paymentResponse object in AluV4

            if (isset($responseArray['paymentResult']['payuResponseCode'])) {
                $response->setReturnCode($responseArray['paymentResult']['payuResponseCode']);
            }

            if (isset($responseArray['paymentResult']['authCode'])) {
                $response->setAuthCode($responseArray['paymentResult']['authCode']);
            }

            if (isset($responseArray['paymentResult']['rrn'])) {
                $response->setRrn($responseArray['paymentResult']['rrn']);
            }

            if (isset($responseArray['paymentResult']['installmentsNumber'])) {
                $response->setInstallmentsNo($responseArray['paymentResult']['installmentsNumber']);
            }

            if (isset($responseArray['paymentResult']['cardProgramName'])) {
                $response->setCardProgramName($responseArray['paymentResult']['cardProgramName']);
            }

            if (isset($responseArray['paymentResult']['type'])) {
                $response->setType($responseArray['paymentResult']['type']);
            }

            if (isset($responseArray['paymentResult']['url'])) {
                $response->setUrlRedirect($responseArray['paymentResult']['url']);
            }

            $additionalResponseParameters = [];

            // bankResponseDetails object in paymentResult
            if (isset($responseArray['paymentResult']['bankResponseDetails'])) {
                $bankResponseDetails = $responseArray['paymentResult']['bankResponseDetails'];

                if (isset($bankResponseDetails['terminalId'])) {
                    $additionalResponseParameters['CLIENTID'] = $bankResponseDetails['terminalId'];
                }

                // response object in bankResponseDetails
                if (isset($responseArray['paymentResult']['bankResponseDetails']['response'])) {
                    $bankResponse = $responseArray['paymentResult']['bankResponseDetails']['response'];

                    if (isset($bankResponse['code'])) {
                        $additionalResponseParameters['PROCRETURNCODE'] = $bankResponse['code'];
                    }

                    if (isset($bankResponse['message'])) {
                        $additionalResponseParameters['ERRORMESSAGE'] = $bankResponse['message'];
                    }

                    if (isset($bankResponse['status'])) {
                        $additionalResponseParameters['RESPONSE'] = $bankResponse['status'];
                    }
                }

                if (isset($bankResponseDetails['hostRefNum'])) {
                    $additionalResponseParameters['HOSTREFNUM'] = $bankResponseDetails['hostRefNum'];
                }

                if (isset($bankResponseDetails['merchantId'])) {
                    $additionalResponseParameters['BANK_MERCHANT_ID'] = $bankResponseDetails['merchantId'];
                }

                if (isset($bankResponseDetails['shortName'])) {
                    $additionalResponseParameters['TERMINAL_BANK'] = $bankResponseDetails['shortName'];
                }

                if (isset($bankResponseDetails['txRefNo'])) {
                    $additionalResponseParameters['TX_REFNO'] = $bankResponseDetails['txRefNo'];
                }

                if (isset($bankResponseDetails['oid'])) {
                    $additionalResponseParameters['OID'] = $bankResponseDetails['oid'];
                }

                if (isset($bankResponseDetails['transId'])) {
                    $additionalResponseParameters['TRANSID'] = $bankResponseDetails['transId'];
                }
            }

            // cardDetails object in paymentResult
            if (isset($responseArray['paymentResult']['cardDetails'])) {
                $cardDetails = $responseArray['paymentResult']['cardDetails'];

                if (isset($cardDetails['pan'])) {
                    $additionalResponseParameters['PAN'] = $cardDetails['pan'];
                }

                if (isset($cardDetails['expiryYear'])) {
                    $additionalResponseParameters['EXPYEAR'] = $cardDetails['expiryYear'];
                }

                if (isset($cardDetails['expiryMonth'])) {
                    $additionalResponseParameters['EXPMONTH'] = $cardDetails['expiryMonth'];
                }
            }

            // 3dsDetails object in paymentResult
            if (isset($responseArray['paymentResult']['3dsDetails'])) {
                $threeDsDetails = $responseArray['paymentResult']['3dsDetails'];

                if (isset($threeDsDetails['mdStatus'])) {
                    $additionalResponseParameters['MDSTATUS'] = $threeDsDetails['mdStatus'];
                }

                if (isset($threeDsDetails['errorMessage'])) {
                    $additionalResponseParameters['MDERRORMSG'] = $threeDsDetails['errorMessage'];
                }

                if (isset($threeDsDetails['txStatus'])) {
                    $additionalResponseParameters['TXSTATUS'] = $threeDsDetails['txStatus'];
                }

                if (isset($threeDsDetails['xid'])) {
                    $additionalResponseParameters['XID'] = $threeDsDetails['xid'];
                }

                if (isset($threeDsDetails['eci'])) {
                    $additionalResponseParameters['ECI'] = $threeDsDetails['eci'];
                }

                if (isset($threeDsDetails['cavv'])) {
                    $additionalResponseParameters['CAVV'] = $threeDsDetails['cavv'];
                }

                if (isset($threeDsDetails['url'])) {
                    $response->setThreeDsUrl($responseArray['paymentResult']['url']);
                }
            }

            // wireAccounts object in paymentResult
            if (isset($responseArray['paymentResult']['wireAccounts'])) {
                $wireAccounts = [];
                $cnt = 0;
                foreach ($responseArray['paymentResult']['wireAccounts'] as $account) {
                    $wireAccount = new ResponseWireAccount();

                    if (isset($account['bankIdentifier'])) {
                        $wireAccount->setBankIdentifier($account['bankIdentifier']);
                    }

                    if (isset($account['bankAccount'])) {
                        $wireAccount->setBankAccount($account['bankAccount']);
                    }

                    if (isset($account['routingNumber'])) {
                        $wireAccount->setRoutingNumber($account['routingNumber']);
                    }

                    if (isset($account['ibanAccount'])) {
                        $wireAccount->setIbanAccount($account['ibanAccount']);
                    }

                    if (isset($account['bankSwift'])) {
                        $wireAccount->setBankSwift($account['bankSwift']);
                    }

                    if (isset($account['country'])) {
                        $wireAccount->setCountry($account['country']);
                    }

                    if (isset($account['recipientName'])) {
                        $wireAccount->setWireRecipientName($account['recipientName']);
                    }

                    if (isset($account['recipientVatId'])) {
                        $wireAccount->setWireRecipientVatId($account['recipientVatId']);
                    }

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
    private function buildTooManyRequestsResponse($responseArray)
    {
        $response = new Response();

        if (isset($responseArray['code'])) {
            $response->setCode($responseArray['code']);
        }

        if (isset($responseArray['message'])) {
            $response->setReturnMessage($responseArray['message']);
        }

        if (isset($responseArray['status'])) {
            $response->setStatus($responseArray['status']);
        }

        return $response;
    }

    /**
     * @param array $responseArray
     * @return Response
     */
    private function buildInvalidParametersResponse($responseArray)
    {
        $response = new Response();

        if (isset($responseArray['code'])) {
            $response->setCode($responseArray['code']);
        }

        if (isset($responseArray['message'])) {
            $response->setReturnMessage($responseArray['message']);
        }

        return $response;
    }

    /**
     * @param $responseArray
     * @return Response
     */
    private function buildInternalErrorResponse($responseArray)
    {
        $response = new Response();

        if (isset($responseArray['code'])) {
            $response->setCode($responseArray['code']);
        }

        if (isset($responseArray['message'])) {
            $response->setReturnMessage($responseArray['message']);
        }

        if (isset($responseArray['payuPaymentReference'])) {
            $response->setRefno($responseArray['payuPaymentReference']);
        }

        return $response;
    }

    /**
     * @param string $responseJson
     * @return string
     */
    public function parseJsonTokenResponse($responseJson)
    {
        $responseArray = json_decode($responseJson, true);

        if ($responseArray["meta"]["status"]["code"] === 0) {
            return $responseArray["response"]["token"];
        }

        //todo parse 40x and 500 error codes (how ?)
        return '';
    }
}

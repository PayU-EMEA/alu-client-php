<?php

namespace PayU\PaymentsApi\PaymentsV4\Services;

use PayU\PaymentsApi\PaymentsV4\Exceptions\ResponseBuilderException;

class AluResponseTransformation
{
    const TYPE_REDIRECT = 'redirect';
    const TYPE_WIRE = 'wire';
    const TYPE_OFFLINE = 'offline';

    const WIRE_DATA_NODE = 'WIRE_ACCOUNTS';
    const TYPE_KEY = 'TYPE';

    const LU_STATUS_FAILED = 'FAILED';
    const LU_STATUS_SUCCESS = 'SUCCESS';

    const ERROR_CODE = 'INVALID_3DS20_PARAMETERS';

    /**
     * @param array $responseData
     * @return array
     * @throws ResponseBuilderException
     */
    public function process(array $responseData)
    {
        $responseData = $this->transformResponseParameters($responseData);
        $responseData = $this->mapAluV3ParametersFromErrorMessageToV4($responseData);

        return $responseData;
    }

    /**
     * @param array $responseData
     * @return array
     */
    private function mapAluV3ParametersFromErrorMessageToV4(array $responseData)
    {
        if ($responseData['status'] !== self::ERROR_CODE) {
            return $responseData;
        }

        /**
         * Why the sorting? Without it, given
         * $mapping = [
         *      "request_date" => "DATE",
         *      "order_date" => "ORDER_DATE"
         * ] and message = "Invalid DATE,ORDER_DATE",
         * the result of str_replace would be "Invalid request_date,ORDER_request_date"
         * instead of "Invalid request_date,order_date"
         */
        //todo ask andra
//        $mapping = AluRequestMapper::MAP;
//        uasort($mapping, function ($value1, $value2) {
//            return strlen($value1) < strlen($value2);
//        });
//
//        $responseData['message'] = str_replace(
//            array_values($mapping),
//            array_keys($mapping),
//            $responseData['message']
//        );

        return $responseData;
    }

    /**
     * @param array $responseData
     * @return array
     * @throws ResponseBuilderException
     */
    private function transformResponseParameters(array $responseData)
    {
        if (isset($responseData[AluResponseMapper::PAYMENT_RESULT][self::WIRE_DATA_NODE])) {
            $responseData = $this->buildWireResponse($responseData);
        }

        if (empty($responseData['message'])) {
            $responseData['message'] = $this->convertAluStatusToHumanReadable(
                $responseData[AluResponseMapper::STATUS_KEY]
            );
        }

        if (isset($responseData[AluResponseMapper::PAYMENT_RESULT]['url'])) {
            $responseData[self::TYPE_KEY] = self::TYPE_REDIRECT;
        }

        if (isset($responseData[AluResponseMapper::PAYMENT_RESULT][AluResponseMapper::NODE_BANK]['txRefNo'])) {
            $responseData[self::TYPE_KEY] = self::TYPE_OFFLINE;
        }

        // moved in AluResponseMapper map
        //$responseData['code'] = $this->aluHttpCodeMapper->getCode($responseData);

        if (!in_array($responseData[AluResponseMapper::STATUS_KEY],
                      [self::LU_STATUS_SUCCESS, self::LU_STATUS_FAILED],
                      true)
        ) {
            if (!empty($responseData[AluResponseMapper::PAYMENT_RESULT]['payuResponseCode'])) {
                $responseData[AluResponseMapper::STATUS_KEY] = $responseData[AluResponseMapper::PAYMENT_RESULT]['payuResponseCode'];
            } elseif (empty($responseData[AluResponseMapper::STATUS_KEY])) {
                //this should never happen
                //as status key from $responseData always has something before calling this method.

//                $this->logger->log(
//                    CLogger::LEVEL_ERROR,
//                    CLogger::MODULE_AUTOMATIC_LIVE_UPDATE,
//                    'Merchant API - empty status detected',
//                    "Error building response - status key expected in response array : " . print_r($responseData, 1)
//                );

                throw new ResponseBuilderException('Merchant API - empty status detected.');
            }

            //Keep payuResponseCode only for success and auth errors (cases when the order is created)
            $responseData = $this->removePayuResponseCodeFromResponse($responseData);
        } /** @noinspection PhpStatementHasEmptyBodyInspection */ else {
            /*
             * Here, the STATUS_KEY is NOT changed as it already is 'SUCCESS'
             * (LU_STATUS_SUCCESS) or 'FAILED' (LU_STATUS_FAILED)
             * It will have the values from these constants.
            */
        }

        return $responseData;
    }

    /**
     * @param string $status
     * @return string
     */
    private function convertAluStatusToHumanReadable($status)
    {
        $regex = '/^[A-Z_]*$/'; // check that string is made only of uppercase letters and _
        if (!preg_match($regex, $status)) {
            return $status;
        }

        return ucfirst(
            strtolower(
                str_replace('_', ' ', $status)
            )
        );
    }

    /**
     * @param $responseData
     * @return array
     */
    private function buildWireResponse(array $responseData)
    {
        $buildWire = [];
        $cnt = 0;
        foreach ($responseData[AluResponseMapper::PAYMENT_RESULT][self::WIRE_DATA_NODE] as $wireAccount) {
            $buildWire[$cnt] = [
                'BANK_IDENTIFIER' => $wireAccount['bankIdentifier'],
                'BANK_ACCOUNT' => $wireAccount['bankAccount'],
                'ROUTING_NUMBER' => $wireAccount['routingNumber'],
                'IBAN_ACCOUNT' => $wireAccount['ibanAccount'],
                'BANK_SWIFT' => $wireAccount['bankSwift'],
                'COUNTRY' => $wireAccount['country'],
                'WIRE_RECIPIENT_NAME' => $wireAccount['recipientName'],
                'WIRE_RECIPIENT_VAT_ID' => $wireAccount['recipientVatId'],
            ];
        }

        $responseData[self::TYPE_KEY] = self::TYPE_WIRE;
        $responseData[self::WIRE_DATA_NODE] = $buildWire;

        return $responseData;
    }

    /**
     * @param array $responseData
     * @return array
     */
    private function removePayuResponseCodeFromResponse($responseData)
    {
        unset($responseData[AluResponseMapper::PAYMENT_RESULT]['payuResponseCode']);

        if (empty($responseData[AluResponseMapper::PAYMENT_RESULT])) {
            unset($responseData[AluResponseMapper::PAYMENT_RESULT]);
        }

        return $responseData;
    }
}

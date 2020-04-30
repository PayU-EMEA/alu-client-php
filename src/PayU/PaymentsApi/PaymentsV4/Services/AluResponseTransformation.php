<?php

namespace PayU\PaymentsApi\PaymentsV4\Services;

class AluResponseTransformation
{
    const TYPE_REDIRECT = 'redirect';
    const TYPE_WIRE = 'wire';
    const TYPE_OFFLINE = 'offline';

    const WIRE_DATA_NODE = 'WIRE_ACCOUNTS';
    const TYPE_KEY = 'TYPE';

    const STATUS_KEY = 'STATUS';

    /**
     * @param array $responseData
     * @return array
     */
    public function process(array $responseData)
    {
        return $this->transformResponseParameters($responseData);
    }

    /**
     * @param array $responseData
     * @return array
     */
    private function transformResponseParameters(array $responseData)
    {
        if (isset($responseData[AluResponseMapper::PAYMENT_RESULT][self::WIRE_DATA_NODE])) {
            $responseData = $this->buildWireResponse($responseData);
        }

        if (empty($responseData['RETURN_MESSAGE'])) {
            $responseData['RETURN_MESSAGE'] = $this->convertAluStatusToHumanReadable(
                $responseData[self::STATUS_KEY]
            );
        }

        if (isset($responseData[AluResponseMapper::PAYMENT_RESULT]['url'])) {
            $responseData[self::TYPE_KEY] = self::TYPE_REDIRECT;
        }

        if (isset($responseData[AluResponseMapper::PAYMENT_RESULT][AluResponseMapper::NODE_BANK]['txRefNo'])) {
            $responseData[self::TYPE_KEY] = self::TYPE_OFFLINE;
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
}

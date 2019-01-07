<?php
namespace PayU\Alu\Parser;

use PayU\Alu\Component\Response;
use PayU\Alu\Exception\ClientException;

/**
 * Class ThreeDSecureResponseParser
 * @package Payu\Alu\Parser
 */
class ThreeDSecureResponseParser extends AbstractParser
{
    /**
     * @param $data
     * @return \PayU\Alu\Component\Response
     * @throws ClientException
     */
    public function parse($data)
    {
        if (!empty($data['HASH'])) {
            $response = $this->prepareResponse($data);
            $this->hashService->validateResponse($response);
        } else {
            throw new ClientException('Missing HASH');
        }
        return $response;
    }


    /**
     * @param array $returnData
     * @return Response
     */
    private function prepareResponse(array $returnData = array())
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

        $response->setAdditionalResponseParameters($this->parseAdditionalParameters($returnData));

        if (array_key_exists('TOKEN_HASH', $returnData)) {
            $response->setTokenHash($returnData['TOKEN_HASH']);
        }

        if (array_key_exists('WIRE_ACCOUNTS', $returnData) && is_array($returnData['WIRE_ACCOUNTS'])) {
            foreach ($returnData['WIRE_ACCOUNTS'] as $wireAccount) {
                $response->addWireAccount($this->createWiredAccount($wireAccount));
            }
        }

        return $response;
    }
}

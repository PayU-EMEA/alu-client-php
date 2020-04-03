<?php


namespace PayU\PaymentsApi\AluV3\Services;

use PayU\Alu\Exceptions\ClientException;
use PayU\PaymentsApi\AluV3\Entities\AuthorizationResponse;
use SimpleXMLElement;

final class ResponseParser
{
    /**
     * @param $xmlResponse
     * @return AuthorizationResponse
     * @throws ClientException
     */
    public function parseXMLResponse($xmlResponse)
    {
        try {
            return new AuthorizationResponse(new SimpleXMLElement($xmlResponse));
        } catch (\Exception $e) {
            throw new ClientException($e->getMessage(), $e->getCode(), $e);
        }

    }
}

<?php


namespace PayU\PaymentsApi\AluV3\Services;

use PayU\PaymentsApi\AluV3\Entities\AuthorizationResponse;
use PayU\PaymentsApi\AluV3\Exceptions\ResponseParserException;
use SimpleXMLElement;

final class ResponseParser
{
    /**
     * @param $xmlResponse
     * @return AuthorizationResponse
     * @throws ResponseParserException
     */
    public function parseXMLResponse($xmlResponse)
    {
        try {
            return new AuthorizationResponse(new SimpleXMLElement($xmlResponse));
        } catch (\Exception $e) {
            throw new ResponseParserException($e->getMessage(), $e->getCode(), $e);
        }
    }
}

<?php


namespace PayU\PaymentsApi\AluV3\Services;

use PayU\PaymentsApi\AluV3\Entities\AuthorizationResponse;
use SimpleXMLElement;

class ResponseParser
{
    public function parseXMLResponse($xmlResponse)
    {
        return new AuthorizationResponse(new SimpleXMLElement($xmlResponse));
    }
}

<?php


namespace PayU\PaymentsApi\PaymentsV4\Services;

use PayU\PaymentsApi\PaymentsV4\Entities\AuthorizationResponse;
use PayU\PaymentsApi\PaymentsV4\Exceptions\AuthorizationResponseException;

class ResponseParser
{

    /**
     * @param $jsonResponse
     * @return AuthorizationResponse
     * @throws AuthorizationResponseException
     */
    public function parseJsonResponse($jsonResponse)
    {
        $responseArray = json_decode($jsonResponse, true);

        if (is_array($responseArray)) {
            return new AuthorizationResponse($responseArray);
        }

        throw new AuthorizationResponseException('Could not decode Json response');
    }
}

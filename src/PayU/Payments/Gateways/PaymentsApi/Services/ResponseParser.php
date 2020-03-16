<?php


namespace PaymentsApi\Services;

use PaymentsApi\Entities\AuthorizationResponse;

class ResponseParser
{
    /**
     * @param string $jsonResponse
     * @return AuthorizationResponse
     */
    public function parseJsonResponse($jsonResponse)
    {
        return new AuthorizationResponse(json_decode($jsonResponse, true));
    }
}

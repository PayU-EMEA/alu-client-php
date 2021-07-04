<?php


namespace PayU\PaymentsApi\PaymentsV4\Services;

use PayU\PaymentsApi\PaymentsV4\Entities\Response\AuthorizationResponse;
use PayU\PaymentsApi\PaymentsV4\Exceptions\AuthorizationResponseException;

class ResponseParser
{
    /** @var AluResponseMapper */
    private $aluResponseMapper;

    public function __construct()
    {
        $this->aluResponseMapper = new AluResponseMapper();
    }

    /**
     * @param string $jsonResponse
     * @return AuthorizationResponse
     * @throws AuthorizationResponseException
     */
    public function parseJsonResponse($jsonResponse)
    {
        $responseArray = json_decode($jsonResponse, true);

        if (is_array($responseArray)) {
            $responseArray = $this->aluResponseMapper->processResponse($responseArray);

            return new AuthorizationResponse($responseArray);
        }

        throw new AuthorizationResponseException('Could not decode Json response');
    }
}

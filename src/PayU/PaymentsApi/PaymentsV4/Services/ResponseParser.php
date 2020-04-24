<?php


namespace PayU\PaymentsApi\PaymentsV4\Services;

use PayU\PaymentsApi\PaymentsV4\Entities\AuthorizationResponse;
use PayU\PaymentsApi\PaymentsV4\Exceptions\AuthorizationResponseException;
use PayU\PaymentsApi\PaymentsV4\Exceptions\ResponseBuilderException;

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
     * @throws ResponseBuilderException
     */
    public function parseJsonResponse($jsonResponse)
    {
        $responseArray = json_decode($jsonResponse, true);

        if (is_array($responseArray)) {
            $responseArray = $this->createArray($responseArray);

            return new AuthorizationResponse($responseArray);
        }

        throw new AuthorizationResponseException('Could not decode Json response');
    }

    /**
     * @param array $jsonResponse
     * @return array
     * @throws ResponseBuilderException
     */
    private function createArray($jsonResponse)
    {
        return $this->aluResponseMapper->processResponse($jsonResponse);
    }
}

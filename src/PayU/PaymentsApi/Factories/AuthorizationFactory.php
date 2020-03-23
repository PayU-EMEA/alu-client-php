<?php

namespace PayU\PaymentsApi\Factories;

use PayU\Alu\Exceptions\ClientException;
use PayU\Alu\HashService;
use PayU\Alu\HTTPClient;
use PayU\PaymentsApi\AluV3\AluV3;

class AuthorizationFactory
{

    /**
     * @param $apiVersion
     * @param HTTPClient $httpClient
     * @param HashService $hashService
     * @return AluV3|PaymentsApiGateway
     * @throws ClientException
     */
    public function create(
        $apiVersion,
        HTTPClient $httpClient,
        HashService $hashService
    ) {
        switch ($apiVersion) {
            case 'v3':
                return new AluV3($httpClient, $hashService);

            case 'v4':
                return new PaymentsApiGateway();

            default:
                throw new \Exception('Api version not available');
        }
    }
}

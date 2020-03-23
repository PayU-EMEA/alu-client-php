<?php

namespace PayU\PaymentsApi\Factories;

use PayU\Alu\Exceptions\ClientException;
use PayU\Alu\HashService;
use PayU\Alu\HTTPClient;
use PayU\PaymentsApi\AluV3\AluV3;
use PayU\PaymentsApi\Exceptions\AuthorizationFactoryException;

class AuthorizationFactory
{
    /**
     * @param string $apiVersion
     * @param HTTPClient $httpClient
     * @param HashService $hashService
     * @return AluV3
     * @throws AuthorizationFactoryException
     */
    public function create(
        $apiVersion,
        HTTPClient $httpClient,
        HashService $hashService
    ) {
        switch ($apiVersion) {
            case AluV3::API_VERSION_V3:
                return new AluV3($httpClient, $hashService);

            default:
                throw new AuthorizationFactoryException('Invalid API version provided.');
        }
    }
}

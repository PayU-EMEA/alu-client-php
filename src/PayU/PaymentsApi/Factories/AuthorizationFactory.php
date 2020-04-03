<?php

namespace PayU\PaymentsApi\Factories;

use PayU\Alu\HashService;
use PayU\Alu\HTTPClient;
use PayU\PaymentsApi\AluV3\AluV3;
use PayU\PaymentsApi\Exceptions\AuthorizationFactoryException;
use PayU\PaymentsApi\Interfaces\AuthorizationInterface;

final class AuthorizationFactory
{
    /**
     * @param string $apiVersion
     * @param HTTPClient $httpClient
     * @param HashService $hashService
     * @return AuthorizationInterface
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

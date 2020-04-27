<?php

namespace PayU\PaymentsApi\Factories;

use PayU\Alu\Exceptions\ClientException;
use PayU\Alu\HashService;
use PayU\Alu\HTTPClient;
use PayU\PaymentsApi\AluV3\AluV3;
use PayU\PaymentsApi\Exceptions\AuthorizationPaymentsApiFactoryException;
use PayU\PaymentsApi\Interfaces\AuthorizationPaymentsApiClient;

final class AuthorizationPaymentsApiFactory
{
    /**
     * @param string $apiVersion
     * @param string $secretKey
     * @return HashService
     * @throws AuthorizationPaymentsApiFactoryException
     */
    public function createHashService($apiVersion, $secretKey)
    {
        switch ($apiVersion) {
            case AluV3::API_VERSION_V3:
                return new HashService($secretKey);

            default:
                throw new AuthorizationPaymentsApiFactoryException('Invalid API version provided.');
        }
    }

    /**
     * @param string $apiVersion
     * @return HTTPClient
     * @throws AuthorizationPaymentsApiFactoryException
     * @throws ClientException
     */
    public function createHttpClient($apiVersion)
    {
        switch ($apiVersion) {
            case AluV3::API_VERSION_V3:
                return new HTTPClient();

            default:
                throw new AuthorizationPaymentsApiFactoryException('Invalid API version provided.');
        }
    }

    /**
     * @param string $apiVersion
     * @param HTTPClient $httpClient
     * @param HashService $hashService
     * @return AuthorizationPaymentsApiClient
     * @throws AuthorizationPaymentsApiFactoryException
     */
    public function createPaymentsApiClient(
        $apiVersion,
        HTTPClient $httpClient,
        HashService $hashService
    ) {
        switch ($apiVersion) {
            case AluV3::API_VERSION_V3:
                return new AluV3($httpClient, $hashService);

            default:
                throw new AuthorizationPaymentsApiFactoryException('Invalid API version provided.');
        }
    }
}

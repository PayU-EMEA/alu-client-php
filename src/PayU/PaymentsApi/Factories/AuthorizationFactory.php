<?php

namespace PayU\PaymentsApi\Factories;

use PayU\PaymentsApi\PaymentsV4\PaymentsV4;
use PayU\Alu\HashService;
use PayU\Alu\HTTPClient;
use PayU\PaymentsApi\AluV3\AluV3;
use PayU\PaymentsApi\Exceptions\AuthorizationFactoryException;

final class AuthorizationFactory
{

    /**
     * @param $apiVersion
     * @param HTTPClient $httpClient
     * @param HashService $hashService
     * @return PaymentsV4|AluV3
     * @throws AuthorizationFactoryException
     * @throws \PayU\Alu\Exceptions\ClientException
     */
    public function create(
        $apiVersion,
        HTTPClient $httpClient,
        HashService $hashService
    ) {
        switch ($apiVersion) {
            case AluV3::API_VERSION_V3:
                return new AluV3($httpClient, $hashService);

            case PaymentsV4::API_VERSION_V4:
                return new PaymentsV4();

            default:
                throw new AuthorizationFactoryException('Invalid API version provided.');
        }
    }
}

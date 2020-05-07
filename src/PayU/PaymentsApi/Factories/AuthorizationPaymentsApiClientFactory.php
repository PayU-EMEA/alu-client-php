<?php

namespace PayU\PaymentsApi\Factories;

use PayU\Alu\Exceptions\ClientException;
use PayU\PaymentsApi\AluV3\AluV3;
use PayU\PaymentsApi\AluV3\Services\HashService;
use PayU\PaymentsApi\AluV3\Services\HTTPClient;
use PayU\PaymentsApi\Exceptions\AuthorizationPaymentsApiClientFactoryException;
use PayU\PaymentsApi\PaymentsV4\Exceptions\HttpClientException;
use PayU\PaymentsApi\PaymentsV4\PaymentsV4;

/**
 * Responsible with creating a different implementation for AuthorizationPaymentsApiClient interface, depending on the
 * payments api version.
 * The version should be set on the PayU/Alu/Request instance, which encapsulates all data needed for
 * an authorization request. For ALU v3 use \PayU\PaymentsApi\AluV3\AluV3::API_VERSION_V3 constant.
 */
final class AuthorizationPaymentsApiClientFactory
{
    /**
     * @param string $apiVersion
     * @param string $secretKey
     * @param HTTPClient|null $httpClient
     * @param HashService|null $hashService
     * @return AluV3 | PaymentsV4
     * @throws AuthorizationPaymentsApiClientFactoryException
     * @throws ClientException
     */
    public function createPaymentsApiClient(
        $apiVersion,
        $secretKey,
        HTTPClient $httpClient = null,
        HashService $hashService = null
    ) {
        switch ($apiVersion) {
            case AluV3::API_VERSION_V3:
                if (null === $httpClient) {
                    $httpClient = new HTTPClient();
                }

                if (null === $hashService) {
                    $hashService = new HashService($secretKey);
                }
                return new AluV3($httpClient, $hashService);
            case PaymentsV4::API_VERSION_V4:
                try {
                    return new PaymentsV4();
                } catch (HttpClientException $e) {
                    throw new ClientException($e->getMessage(), $e->getCode(), $e);
                }

            default:
                throw new AuthorizationPaymentsApiClientFactoryException('Invalid API version provided.');
        }
    }
}

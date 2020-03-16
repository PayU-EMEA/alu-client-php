<?php


namespace PayU\Payments;

use AluV3\AluV3Gateway;
use AluV3\Services\HashService;
use AluV3\Services\HTTPClient;
use PaymentsApi\PaymentsApiGateway;
use PayU\Alu\Exceptions\ClientException;

class GatewayFactory
{

    /**
     * @param $apiVersion
     * @param HTTPClient $httpClient
     * @param HashService $hashService
     * @return AluV3Gateway|PaymentsApiGateway
     * @throws ClientException
     */
    public function create(
        $apiVersion,
        HTTPClient $httpClient,
        HashService $hashService
    ) {
        switch ($apiVersion) {
            case 'v3':
                return new AluV3Gateway($httpClient, $hashService);

            case 'v4':
                return new PaymentsApiGateway();

            default:
                throw new \Exception('Api version not available');
        }
    }
}

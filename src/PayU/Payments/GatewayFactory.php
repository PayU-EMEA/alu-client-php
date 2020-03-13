<?php


namespace PayU\Payments;


use PaymentsApi\PaymentsApiGateway;
use PayU\Alu\HashService;
use PayU\Alu\HTTPClient;
use PayU\Alu\MerchantConfig;

class GatewayFactory
{

    public function create(
        $apiVersion,
        HTTPClient $httpClient,
        HashService $hashService)
    {
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
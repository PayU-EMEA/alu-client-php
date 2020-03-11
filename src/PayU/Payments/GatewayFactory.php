<?php


namespace PayU\Payments;


use PayU\Alu\HashService;
use PayU\Alu\HTTPClient;
use PayU\Alu\MerchantConfig;
use PayU\Payments\Gateways\PaymentsApi\PaymentsApiGateway;

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
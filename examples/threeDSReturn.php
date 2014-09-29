<?php
/**
 * Include composer class autoloader
 */
require_once dirname(__FILE__) . '/../vendor/autoload.php';

use PayU\Alu\Client;
use PayU\Alu\MerchantConfig;
use PayU\Alu\Exceptions\ConnectionException;
use PayU\Alu\Exceptions\ClientException;

/**
 * Create configuration with params:
 *
 * Merchant Code - Your PayU Merchant Code
 * Secret Key - Your PayU Secret Key
 * Platform - RO | RU | UA | TR | HU
 */
$cfg = new MerchantConfig('MERCHANT_CODE', 'SECRET_KEY', 'RO');

/**
 * Create new API Client, passing the Config object as parameter
 */
$client = new Client($cfg);

/**
 * Will throw different Exceptions on errors
 */
try {
    /**
     * Gets the Three DS return response and interprets its
     *
     * See documentation for Response params
     */
    $threeDSResponse = $client->handleThreeDSReturnResponse($_POST);

    if ($threeDSResponse->getStatus() == 'SUCCESS') {

        echo $threeDSResponse->getReturnMessage();
        die('Success. PAYU RefNo =' . $threeDSResponse->getRefno());
    } else {
        echo $threeDSResponse->getReturnMessage();
        die('FAIL. PAYU RefNo =' . $threeDSResponse->getRefno());
    }

} catch (ConnectionException $exception) {
    echo $exception->getMessage();
} catch (ClientException $exception) {
    echo $exception->getErrorMessage();
}

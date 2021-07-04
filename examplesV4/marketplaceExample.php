<?php
/**
 * Include composer class autoloader
 */
require_once dirname(__FILE__) . '/../vendor/autoload.php';

use PayU\Alu\Billing;
use PayU\Alu\Card;
use PayU\Alu\Client;
use PayU\Alu\Delivery;
use PayU\Alu\Marketplace;
use PayU\Alu\MerchantConfig;
use PayU\Alu\Order;
use PayU\Alu\Product;
use PayU\Alu\Request;
use PayU\Alu\User;
use PayU\Alu\Exceptions\ClientException;
use PayU\PaymentsApi\PaymentsV4\PaymentsV4;

/**
 * Create configuration with params:
 *
 * Merchant Code - Your PayU Merchant Code
 * Secret Key - Your PayU Secret Key
 * Platform - RO | RU | UA | TR | HU
 */
//todo modify merchantCode back to MERCHANT_CODE
$cfg = new MerchantConfig('PAYU_2', 'SECRET_KEY', 'RO');

/**
 * Create user with params:
 *
 * User IP - User's IP address
 * User Time  - Time of user computer - optional
 *
 */
$user = new User('127.0.0.1');

/**
 * Create new order
 */
$order = new Order();

/**
 * Setup the order params
 *
 * Full params available in the documentation
 */

$order->withBackRef('http://path/to/your/returnUrlScript')
    ->withOrderRef((string)random_int(1000, 9999))
    ->withCurrency('RON')
    ->withOrderDate(gmdate('Y-m-d\TH:i:sP')) // Different format from ALU v3
    ->withOrderTimeout(1000)
    ->withPayMethod('CCVISAMC');

/**
 * Create new product
 */
$product = new Product();

/**
 * Setup the product params
 *
 * Full params available in the documentation
 */
$randomProductId = (string)random_int(100, 999);
$marketplace = new Marketplace(
    "49a88d24-7615-11e8-adc0-fa7ae01bb$randomProductId", // Make sure to generate a product id with uuid format
    'b6a5e941-5f06-4c24-b834-0d9c0a75f487', // Seller ID as uuid format from Marketplace module
    1.66,
    'RON' // Should be the same currency as the one of total amount of order
);

$product->withCode('PCODE01')
    ->withName('PNAME01')
    ->withPrice(100.0)
    ->withVAT(24.0)
    ->withQuantity(1)
    ->withMarketplace($marketplace);

/**
 * Add the product to the order
 */
$order->addProduct($product);

/**
 * Create another product
 */
$product = new Product();

/**
 * Setup the product params
 *
 * Full params available in the documentation
 */
$marketplace = new Marketplace(
    $randomProductId . '88d24-7615-11e8-11e8-fa7ae01bbeb1', // Make sure to generate a product id with uuid format
    'ac1ae54c-ac82-4c31-b4de-7cff8d290e58', // Seller ID as uuid format from Marketplace module
    1.86,
    'RON' // Should be the same currency as the one of total amount of order
);

$product->withCode('PCODE02')
    ->withName('PNAME02')
    ->withPrice(200.0)
    ->withVAT(24.0)
    ->withQuantity(1)
    ->withMarketplace($marketplace);

/**
 * Add the second product to the same order
 */
$order->addProduct($product);

/**
 * Create new billing address
 */
$billing = new Billing();

/**
 * Setup the billing address params
 *
 * Full params available in the documentation
 */
$billing->withAddressLine1('Address1')
    ->withAddressLine2('Address2')
    ->withCity('City')
    ->withCountryCode('RO')
    ->withEmail('john.doe@mail.com')
    ->withFirstName('FirstName')
    ->withLastName('LastName')
    ->withPhoneNumber('40123456789')
    ->withIdentityCardNumber('111222');


/**
 * Create new delivery address
 *
 * If you want to have the same delivery as billing, skip these two steps
 * and pass the Billing $billing object to the request twice
 */
$delivery = new Delivery();

/**
 * Setup the delivery address params
 *
 * Full params available in the documentation
 */
$delivery->withAddressLine1('Address1')
    ->withAddressLine2('Address2')
    ->withCity('City')
    ->withCountryCode('RO')
    ->withEmail('john.doe@mail.com')
    ->withFirstName('FirstName')
    ->withLastName('LastName')
    ->withPhoneNumber('40123456789');

/**
 * Create new Card with params:
 *
 * Credit Card Number
 * Credit Card Expiration Month
 * Credit Card Expiration Year
 * Credit Card CVV (Security Code)
 * Credit Card Owner
 */
$card = new Card(
    '4111111111111111',
    '01',
    date("Y", strtotime("+1 year")),
    '123',
    'Card Owner Name'
);

/**
 * Create new Request with params:
 *
 * Config object
 * Order object
 * Billing object
 * Delivery (or Billing object again, if you want to have the delivery address the same as the billing address)
 * User object
 */
$request = new Request($cfg, $order, $billing, $delivery, $user, PaymentsV4::API_VERSION_V4);

/**
 * Add the Credit Card to the Request
 */
$request->setCard($card);

/**
 * Create new API Client, passing the Config object as parameter
 */
$client = new Client($cfg);

/**
 * Will throw different Exceptions on errors
 */
try {
    /**
     * Sends the Request to ALU and returns a Response
     *
     * See documentation for Response params
     */
    $response = $client->pay($request);

    /**
     * In case of 3DS enrolled cards, PayU will return the URL_3DS that contains a unique url for each
     * transaction. The merchant must redirect the browser to this url to allow user to authenticate.
     * After the authentication process ends the user will be redirected to BACK_REF url
     * with payment result in a HTTP POST request
     */
    if ($response->isThreeDs()) {
        header("Location:" . $response->getThreeDsUrl());
        die();
    }

    echo $response->getCode()
        . ' ' . $response->getStatus()
        . ' ' . $response->getReturnCode()
        . ' ' . $response->getReturnMessage();
} catch (ClientException $exception) {
    echo $exception->getErrorMessage();
}

<?php
/**
 * Include composer class autoloader
 */
require_once __DIR__ . '/../vendor/autoload.php';

use PayU\Alu\Billing;
use PayU\Alu\Card;
use PayU\Alu\Client;
use PayU\Alu\Delivery;
use PayU\Alu\MerchantConfig;
use PayU\Alu\Order;
use PayU\Alu\Product;
use PayU\Alu\Request;
use PayU\Alu\StrongCustomerAuthentication;
use PayU\Alu\User;
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
    ->withOrderRef('EXT_4011578581983')
    ->withCurrency('RON')
    ->withOrderDate(gmdate('Y-m-d H:i:s'))
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
$product->withCode('PCODE01')
    ->withName('PNAME01')
    ->withPrice(100.0)
    ->withVAT(24.0)
    ->withQuantity(1);

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
$product->withCode('PCODE02')
    ->withName('PNAME02')
    ->withPrice(200.0)
    ->withVAT(24.0)
    ->withQuantity(1);

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
    '11',
    '2030',
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
$request = new Request($cfg, $order, $billing, $delivery, $user);

/**
 * Add the Credit Card to the Request
 */
$request->setCard($card);


/**
 *  Add strong customer authentication data.
 */
$strongCustomerAuthentication = new StrongCustomerAuthentication();
$strongCustomerAuthentication
    ->setStrongCustomerAuthentication('YES')
    ->setAddressMatch("YES")
    ->setBrowserAcceptHeaders('text/html')
    ->setBrowserIP('127.0.0.1')
    ->setBrowserJavaEnabled('YES')
    ->setBrowserLanguage('en-US')
    ->setBrowserColorDepth(24)
    ->setBrowserScreenHeight(864)
    ->setBrowserScreenWidth(1536)
    ->setBrowserTimezone(300)
    ->setBrowserUserAgent('Mozilla/5.0')
    ->setBillAddress3('445 Mount Eden Road, Mount Eden, Auckland')
    ->setBillStateCode('RO-B')
    ->setHomePhoneCountryPrefix('40')
    ->setHomePhoneSubscriber('5417543010')
    ->setMobilePhoneCountryPrefix('40')
    ->setMobilePhoneSubscriber('5231543010')
    ->setWorkPhoneCountryPrefix('40')
    ->setWorkPhoneSubscriber('4121543010')
    ->setDeliveryAddress3('445 Mount Eden Road, Mount Eden, Auckland')
    ->setDeliveryStateCode('RO-B')
    ->setCardHolderFraudActivity('YES')
    ->setDeviceChannel('01')
    ->setChallengeIndicator('01')
    ->setChallengeWindowSize('02')
    ->setAccountAdditionalInformation('any info here')
    ->setSdkReferenceNumber('487538453')
    ->setSdkMaximumTimeout('06')
    ->setSdkApplicationId('AA97B177-9383-4934-8543-0F91A7A02836')
    ->setSdkEncData('jwe object here')
    ->setSdkTransId('D952EB9F-7AD2-4B1B-B3CE-386735205990')
    ->setSdkEphemeralPubKey('public key component')
    ->setSdkUiType('01')
    ->setSdkInterface('03')
    ->setTransactionType('10')
    ->setShippingIndicator('03')
    ->setPreOrderIndicator('02')
    ->setPreOrderDate('2019-10-20')
    ->setDeliveryTimeFrame('03')
    ->setReOrderIndicator('01')
    ->setMerchantFundsAmount('123')
    ->setMerchantFundsCurrency('RON')
    ->setRecurringFrequencyDays('100')
    ->setRecurringExpiryDate('2019-12-01')
    ->setAccountCreateDate('2019-07-03')
    ->setAccountDeliveryAddressFirstUsedDate('2019-07-23')
    ->setAccountDeliveryAddressUsageIndicator('04')
    ->setAccountNumberOfTransactionsLastYear('23')
    ->setAccountNumberOfTransactionsLastDay('1')
    ->setAccountNumberOfPurchasesLastSixMonths('12')
    ->setAccountChangeDate('2019-09-14')
    ->setAccountChangeIndicator('02')
    ->setAccountAgeIndicator('03')
    ->setAccountPasswordChangedDate('2019-09-21')
    ->setAccountPasswordChangedIndicator('02')
    ->setAccountNameToRecipientMatch('YES')
    ->setAccountAddCardAttemptsDay('12')
    ->setAccountAuthMethod('03')
    ->setAccountAuthDateTime('2019-09-21 12:03:00')
    ->setRequestorAuthenticationData('Some additional data')
    ->setAccountCardAddedIndicator('05')
    ->setAccountCardAddedDate('2019-10-01');

/**
 * Add Strong Customer Authentication to the Request
 */
$request->setStrongCustomerAuthentication($strongCustomerAuthentication);


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

    echo $response->getStatus() . ' ' . $response->getReturnCode() . ' ' . $response->getReturnMessage();
} catch (ConnectionException $exception) {
    echo $exception->getMessage();
} catch (ClientException $exception) {
    echo $exception->getErrorMessage();
}

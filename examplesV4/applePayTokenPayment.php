<?php
/**
 * Include composer class autoloader
 */
require_once dirname(__FILE__) . '/../vendor/autoload.php';

use PayU\Alu\ApplePayToken;
use PayU\Alu\ApplePayTokenHeader;
use PayU\Alu\Billing;
use PayU\Alu\Client;
use PayU\Alu\Delivery;
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
$cfg = new MerchantConfig('ITEST', 'SECRET_KEY', 'RU');

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

/**
 * todo remove $merchantOrderRef when pushing to master
 */

$merchantOrderRef = strval(rand(1000, 9999));
$order->withBackRef('http://path/to/your/returnUrlScript')
    ->withOrderRef($merchantOrderRef)
    //->withOrderRef('MerchantOrderRef')
    ->withCurrency('RUB')
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
    ->withPrice(1000.0)
    ->withVAT(24.0)
    ->withQuantity(1);

/**
 * Add the product to the order
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
 * Create new ApplePayToken with params:
 *
 * Token
 */
$applePay = '%7B%22version%22%3A%22EC_v1%22%2C%22data%22%3A%22vPoWmcQnI%5C%2F4BLbhuiEWo9YzRGm7iET4AuwlpaNhLiZJafKLIQuxCIjUqeTgTSk5HoZzsTacU7gOKM3%5C%2Fehj88B5Jg224iwILgshQVbmZ0%2BlGeZy40dS2FTXkQm5sM%2BXRRHvvNWyvLhnPxJcc9GtoF98IPZp95xzVLRQAnd4W9z5LA%2BWBNuh9q%5C%2FU4eIMGt1p4UCFMb1MVvQfaT2B8eV3xomq5ux3Bfho2WwXST8%5C%2Fs5HoUSfXWIRLZb%2By8yZBjqTCZb9%5C%2F%5C%2FoWw2uORpX0IIr21D12n7ED1d%5C%2FJliLA9SCrcow9MvxkeefvekNNo4Fsce2usgfRPJUekcrHcDi7d1gcACQTSM4G5jg3LuvSlzgN%2B93j6IZsohu2LTd05W6O24CPVn9P3K6nzbOXT9NPwVjS2B8zyfeOBXi0BIz3AxZQU9T6y4P2g%3D%3D%22%2C%22signature%22%3A%22MIAGCSqGSIb3DQEHAqCAMIACAQExDzANBglghkgBZQMEAgEFADCABgkqhkiG9w0BBwEAAKCAMIID5jCCA4ugAwIBAgIIaGD2mdnMpw8wCgYIKoZIzj0EAwIwejEuMCwGA1UEAwwlQXBwbGUgQXBwbGljYXRpb24gSW50ZWdyYXRpb24gQ0EgLSBHMzEmMCQGA1UECwwdQXBwbGUgQ2VydGlmaWNhdGlvbiBBdXRob3JpdHkxEzARBgNVBAoMCkFwcGxlIEluYy4xCzAJBgNVBAYTAlVTMB4XDTE2MDYwMzE4MTY0MFoXDTIxMDYwMjE4MTY0MFowYjEoMCYGA1UEAwwfZWNjLXNtcC1icm9rZXItc2lnbl9VQzQtU0FOREJPWDEUMBIGA1UECwwLaU9TIFN5c3RlbXMxEzARBgNVBAoMCkFwcGxlIEluYy4xCzAJBgNVBAYTAlVTMFkwEwYHKoZIzj0CAQYIKoZIzj0DAQcDQgAEgjD9q8Oc914gLFDZm0US5jfiqQHdbLPgsc1LUmeY%2BM9OvegaJajCHkwz3c6OKpbC9q%2BhkwNFxOh6RCbOlRsSlaOCAhEwggINMEUGCCsGAQUFBwEBBDkwNzA1BggrBgEFBQcwAYYpaHR0cDovL29jc3AuYXBwbGUuY29tL29jc3AwNC1hcHBsZWFpY2EzMDIwHQYDVR0OBBYEFAIkMAua7u1GMZekplopnkJxghxFMAwGA1UdEwEB%5C%2FwQCMAAwHwYDVR0jBBgwFoAUI%5C%2FJJxE%2BT5O8n5sT2KGw%5C%2Forv9LkswggEdBgNVHSAEggEUMIIBEDCCAQwGCSqGSIb3Y2QFATCB%5C%2FjCBwwYIKwYBBQUHAgIwgbYMgbNSZWxpYW5jZSBvbiB0aGlzIGNlcnRpZmljYXRlIGJ5IGFueSBwYXJ0eSBhc3N1bWVzIGFjY2VwdGFuY2Ugb2YgdGhlIHRoZW4gYXBwbGljYWJsZSBzdGFuZGFyZCB0ZXJtcyBhbmQgY29uZGl0aW9ucyBvZiB1c2UsIGNlcnRpZmljYXRlIHBvbGljeSBhbmQgY2VydGlmaWNhdGlvbiBwcmFjdGljZSBzdGF0ZW1lbnRzLjA2BggrBgEFBQcCARYqaHR0cDovL3d3dy5hcHBsZS5jb20vY2VydGlmaWNhdGVhdXRob3JpdHkvMDQGA1UdHwQtMCswKaAnoCWGI2h0dHA6Ly9jcmwuYXBwbGUuY29tL2FwcGxlYWljYTMuY3JsMA4GA1UdDwEB%5C%2FwQEAwIHgDAPBgkqhkiG92NkBh0EAgUAMAoGCCqGSM49BAMCA0kAMEYCIQDaHGOui%2BX2T44R6GVpN7m2nEcr6T6sMjOhZ5NuSo1egwIhAL1a%2B%5C%2Fhp88DKJ0sv3eT3FxWcs71xmbLKD%5C%2FQJ3mWagrJNMIIC7jCCAnWgAwIBAgIISW0vvzqY2pcwCgYIKoZIzj0EAwIwZzEbMBkGA1UEAwwSQXBwbGUgUm9vdCBDQSAtIEczMSYwJAYDVQQLDB1BcHBsZSBDZXJ0aWZpY2F0aW9uIEF1dGhvcml0eTETMBEGA1UECgwKQXBwbGUgSW5jLjELMAkGA1UEBhMCVVMwHhcNMTQwNTA2MjM0NjMwWhcNMjkwNTA2MjM0NjMwWjB6MS4wLAYDVQQDDCVBcHBsZSBBcHBsaWNhdGlvbiBJbnRlZ3JhdGlvbiBDQSAtIEczMSYwJAYDVQQLDB1BcHBsZSBDZXJ0aWZpY2F0aW9uIEF1dGhvcml0eTETMBEGA1UECgwKQXBwbGUgSW5jLjELMAkGA1UEBhMCVVMwWTATBgcqhkjOPQIBBggqhkjOPQMBBwNCAATwFxGEGddkhdUaXiWBB3bogKLv3nuuTeCN%5C%2FEuT4TNW1WZbNa4i0Jd2DSJOe7oI%5C%2FXYXzojLdrtmcL7I6CmE%5C%2F1RFo4H3MIH0MEYGCCsGAQUFBwEBBDowODA2BggrBgEFBQcwAYYqaHR0cDovL29jc3AuYXBwbGUuY29tL29jc3AwNC1hcHBsZXJvb3RjYWczMB0GA1UdDgQWBBQj8knET5Pk7yfmxPYobD%2Biu%5C%2F0uSzAPBgNVHRMBAf8EBTADAQH%5C%2FMB8GA1UdIwQYMBaAFLuw3qFYM4iapIqZ3r6966%5C%2FayySrMDcGA1UdHwQwMC4wLKAqoCiGJmh0dHA6Ly9jcmwuYXBwbGUuY29tL2FwcGxlcm9vdGNhZzMuY3JsMA4GA1UdDwEB%5C%2FwQEAwIBBjAQBgoqhkiG92NkBgIOBAIFADAKBggqhkjOPQQDAgNnADBkAjA6z3KDURaZsYb7NcNWymK%5C%2F9Bft2Q91TaKOvvGcgV5Ct4n4mPebWZ%2BY1UENj53pwv4CMDIt1UQhsKMFd2xd8zg7kGf9F3wsIW2WT8ZyaYISb1T4en0bmcubCYkhYQaZDwmSHQAAMYIBjDCCAYgCAQEwgYYwejEuMCwGA1UEAwwlQXBwbGUgQXBwbGljYXRpb24gSW50ZWdyYXRpb24gQ0EgLSBHMzEmMCQGA1UECwwdQXBwbGUgQ2VydGlmaWNhdGlvbiBBdXRob3JpdHkxEzARBgNVBAoMCkFwcGxlIEluYy4xCzAJBgNVBAYTAlVTAghoYPaZ2cynDzANBglghkgBZQMEAgEFAKCBlTAYBgkqhkiG9w0BCQMxCwYJKoZIhvcNAQcBMBwGCSqGSIb3DQEJBTEPFw0xODAxMDkxMDA4MzBaMCoGCSqGSIb3DQEJNDEdMBswDQYJYIZIAWUDBAIBBQChCgYIKoZIzj0EAwIwLwYJKoZIhvcNAQkEMSIEIKv6JokYaVxokx8hRexfY6BN50w5GqxPlYBWY3uY8ecuMAoGCCqGSM49BAMCBEcwRQIgEIeBUlgdfCA1BKPnf610elPWNxduPqSmL8voSeYT%5C%2FAcCIQCWEErWofLJEitKoLM78OqPro5zF7qO2Z6j85GdNAkq2wAAAAAAAA%3D%3D%22%2C%22header%22%3A%7B%22ephemeralPublicKey%22%3A%22MFkwEwYHKoZIzj0CAQYIKoZIzj0DAQcDQgAEtnNJJsEhW82JRG%5C%2Fpuh5A2KHWn6BN1M9dvT92gVtCgBZG3B2Kyx7K7c7gixxxnNT%2B0jMxTEANK8Si4H8TKzA8rg%3D%3D%22%2C%22publicKeyHash%22%3A%22zZPAYNrLOwPbRsav95FZTIYlKF6dULquEHppV6TRPmc%3D%22%2C%22transactionId%22%3A%2234652473133619ed0cdde75ea6ec86878d7474c106fea855d9acbdb1d62a5959%22%7D%7D';
$token = json_decode(urldecode($applePay),true);
$applePayHeader = new ApplePayTokenHeader(
    '',
    $token['header']['ephemeralPublicKey'],
    '',
    $token['header']['publicKeyHash'],
    $token['header']['transactionId']
);
$applePayToken = new ApplePayToken(
    $token['data'],
    $applePayHeader,
    $token['signature'],
    $token['version']
);

/**
 * Create new Request with params:
 *
 * Config object
 * Order object
 * Billing object
 * Delivery (or Billing object again, if you want to have the delivery address the same as the billing address)
 * User object
 * Api version - by default is used 'v3' for ALU v3
 */
$request = new Request($cfg, $order, $billing, $delivery, $user, PaymentsV4::API_VERSION_V4);

/**
 * Add the Card Token to the Request
 */
$request->setApplePayToken($applePayToken);

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

    echo $response->getReturnMessage() . ' ' . $response->getRefno();
} catch (ClientException $exception) {
    echo $exception->getErrorMessage();
}

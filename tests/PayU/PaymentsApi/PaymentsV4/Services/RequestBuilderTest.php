<?php

namespace PayU\PaymentsApi\PaymentsV4\Services;

use PayU\Alu\AirlineInfo;
use PayU\Alu\Billing;
use PayU\Alu\Card;
use PayU\Alu\Delivery;
use PayU\Alu\FX;
use PayU\Alu\Marketplace;
use PayU\Alu\MerchantConfig;
use PayU\Alu\Mpi;
use PayU\Alu\Order;
use PayU\Alu\Product;
use PayU\Alu\Request;
use PayU\Alu\StoredCredentials;
use PayU\Alu\ThreeDSecure;
use PayU\Alu\User;
use PayU\PaymentsApi\PaymentsV4\PaymentsV4;

class RequestBuilderTest extends \PHPUnit_Framework_TestCase
{
    const ORDER_DATE = '2020-04-10T15:15:08+00:00';
    const ORDER_REF = '1112';

    const AUTHORIZATION_NODE = 'authorization';
    const FX_NODE = 'fx';
    const AIRLINE_INFO_NODE = 'airlineInfo';
    const STORED_CREDENTIALS_NODE = 'storedCredentials';

    /**
     * @var RequestBuilder
     */
    private $requestBuilder;

    public function setUp()
    {
        $this->requestBuilder = new RequestBuilder();
    }

    /**
     * @return Request
     */
    private function createAluRequest()
    {
        $cfg = new MerchantConfig('CC5857', 'SECRET_KEY', 'RO');

        $user = new User('127.0.0.1');

        $order = new Order();

        $order->withBackRef('http://path/to/your/returnUrlScript')
            ->withOrderRef(self::ORDER_REF)
            ->withCurrency('RON')
            ->withOrderDate(self::ORDER_DATE)
            ->withOrderTimeout(1000)
            ->withPayMethod('CCVISAMC')
            ->withInstallmentsNumber(2)
            ->withCampaignType('EXTRA_INSTALLMENTS');

        $product = new Product();
        $product->withCode('PCODE01')
            ->withName('PNAME01')
            ->withPrice(100.0)
            ->withVAT(24.0)
            ->withQuantity(1);

        $order->addProduct($product);
        $marketplace = new Marketplace(
            'Market1',
            "7cff8d290e58-b4de-4c31-ac82-ac1ae54c",
            1.66,
            "EUR"
        );
        $product = new Product();
        $product->withCode('PCODE02')
            ->withName('PNAME02')
            ->withPrice(200.0)
            ->withVAT(24.0)
            ->withQuantity(1)
            ->withMarketplace($marketplace);

        $order->addProduct($product);

        $billing = new Billing();

        $billing->withAddressLine1('Address1')
            ->withAddressLine2('Address2')
            ->withCity('City')
            ->withCountryCode('RO')
            ->withEmail('john.doe@mail.com')
            ->withFirstName('FirstName')
            ->withLastName('LastName')
            ->withPhoneNumber('40123456789')
            ->withIdentityCardNumber('111222')
            ->withIdentityCardType(null);

        $delivery = new Delivery();
        $delivery->withAddressLine1('Address1')
            ->withAddressLine2('Address2')
            ->withCity('City')
            ->withCountryCode('RO')
            ->withEmail('john.doe@mail.com')
            ->withFirstName('FirstName')
            ->withLastName('LastName')
            ->withPhoneNumber('40123456789');


        $card = new Card('4111111111111111', '01', 2026, 123, 'Card Owner Name');

        $mpi = new Mpi();
        $mpi->withEci(5)
            ->withXid('75BCD15')
            ->withCavv('hmbTh+XZEf/cYwAAAH8kAlcAAAA=')
            ->withDsTransactionId('1jpe0dc0-i9t2-4067-bcb1-nmt866956sgd')
            ->withVersion(2);

        $threeDSecure = new ThreeDSecure($mpi);

        $request = new Request($cfg, $order, $billing, $delivery, $user, PaymentsV4::API_VERSION_V4);

        $request->setCard($card);
        $request->setThreeDSecure($threeDSecure);

        return $request;
    }

    /**
     * @return array
     */
    private function createRequestArray()
    {
        return [
            'merchantPaymentReference' => self::ORDER_REF,
            'currency' => 'RON',
            'returnUrl' => 'http://path/to/your/returnUrlScript',
            self::AUTHORIZATION_NODE => [
                'paymentMethod' => 'CCVISAMC',
                'cardDetails' => [
                    'number' => '4111111111111111',
                    'expiryMonth' => '01',
                    'expiryYear' => '2026',
                    'cvv' => '123',
                    'owner' => 'Card Owner Name',
                ],
                'merchantToken' => null,
                'applePayToken' => null,
                'usePaymentPage' => null,
                'installmentsNumber' => 2,
                'useLoyaltyPoints' => null,
                'loyaltyPointsAmount' => null,
                'campaignType' => 'EXTRA_INSTALLMENTS',
                self::FX_NODE => null
            ],
            'client' => [
                'billing' => [
                    'firstName' => 'FirstName',
                    'lastName' => 'LastName',
                    'email' => 'john.doe@mail.com',
                    'phone' => '40123456789',
                    'city' => 'City',
                    'countryCode' => 'RO',
                    'state' => null,
                    'companyName' => null,
                    'taxId' => null,
                    'addressLine1' => 'Address1',
                    'addressLine2' => 'Address2',
                    'zipCode' => null,
                    'identityDocument' => [
                        'number' => '111222',
                        'type' => null
                    ]
                ],
                'delivery' => [
                    'firstName' => 'FirstName',
                    'lastName' => 'LastName',
                    'phone' => '40123456789',
                    'addressLine1' => 'Address1',
                    'addressLine2' => 'Address2',
                    'zipCode' => null,
                    'city' => 'City',
                    'state' => null,
                    'countryCode' => 'RO',
                    'email' => 'john.doe@mail.com'
                ],
                'ip' => '127.0.0.1',
                'time' => '',
                'communicationLanguage' => null
            ],
            'products' => [
                0 => [
                    'name' => 'PNAME01',
                    'sku' => 'PCODE01',
                    'additionalDetails' => null,
                    'unitPrice' => 100.0,
                    'quantity' => 1,
                    'vat' => 24.0,
                    'marketplace' => null
                ],
                1 => [
                    'name' => 'PNAME02',
                    'sku' => 'PCODE02',
                    'additionalDetails' => null,
                    'unitPrice' => 200.0,
                    'quantity' => 1,
                    'vat' => 24.0,
                    'marketplace' => [
                        'id' => 'Market1',
                        "sellerId" => "7cff8d290e58-b4de-4c31-ac82-ac1ae54c",
                        "commissionAmount" => 1.66,
                        "commissionCurrency" => "EUR"
                    ]
                ]
            ],
            self::AIRLINE_INFO_NODE => null,
            'threeDSecure' => [
                'mpi' => [
                    'eci' => 5,
                    'xid' => '75BCD15',
                    'cavv' => 'hmbTh+XZEf/cYwAAAH8kAlcAAAA=',
                    'dsTransactionId' => '1jpe0dc0-i9t2-4067-bcb1-nmt866956sgd',
                    'version' => 2
                ]
            ],
            self::STORED_CREDENTIALS_NODE => null
        ];
    }

    public function testGetParams()
    {
        // Given
        $request = $this->createAluRequest();
        $result = $this->createRequestArray();

        // Then
        $this->assertEquals(
            $result,
            json_decode(
                $this->requestBuilder->buildAuthorizationRequest($request),
                true
            )
        );
    }

    public function testGetParamsWithFx()
    {
        // Given
        $request = $this->createAluRequest();
        $result = $this->createRequestArray();

        $fx = new FX('EUR', 0.2462);
        $request->setFx($fx);

        $result[self::AUTHORIZATION_NODE][self::FX_NODE]['currency'] = 'EUR';
        $result[self::AUTHORIZATION_NODE][self::FX_NODE]['exchangeRate'] = 0.2462;

        // Then
        $this->assertEquals(
            $result,
            json_decode(
                $this->requestBuilder->buildAuthorizationRequest($request),
                true
            )
        );
    }

    public function testWhenAirlineInfoIsSent()
    {
        // Given
        $airlineInfo = new AirlineInfo();

        $airlineInfo->setPassengerName('John Doe')
            ->setTicketNumber('TICKET_1234')
            ->setRestrictedRefund(0)
            ->setReservationSystem('DATS')
            ->setTravelAgencyCode('MYTRAVEL')
            ->setTravelAgencyName('My Travel Agency');

        $airlineInfo->addFlightSegment(
            '2017-01-10',
            'MOS',
            'SOF'
        );

        $airlineInfo->addFlightSegment(
            '2017-02-10',
            'ANK',
            'WDC',
            'XY',
            'XY_NAME',
            'B',
            1,
            'MAXY12',
            'F5512'
        );

        $airlineInfoArray = [
            'passengerName' => 'John Doe',
            'ticketNumber' => 'TICKET_1234',
            'refundPolicy' => 0,
            'reservationSystem' => 'DATS',
            'travelAgency' => [
                'code' => 'MYTRAVEL',
                'name' => 'My Travel Agency'
            ],
            'flightSegments' => [
                0 => [
                    'departureDate' => '2017-01-10',
                    'departureAirport' => 'MOS',
                    'destinationAirport' => 'SOF',
                    'airlineCode' => null,
                    'airlineName' => null,
                    'serviceClass' => null,
                    'stopover' => null,
                    'fareCode' => null,
                    'flightNumber' => null,
                ],
                1 => [
                    'departureDate' => '2017-02-10',
                    'departureAirport' => 'ANK',
                    'destinationAirport' => 'WDC',
                    'airlineCode' => 'XY',
                    'airlineName' => 'XY_NAME',
                    'serviceClass' => 'B',
                    'stopover' => 1,
                    'fareCode' => 'MAXY12',
                    'flightNumber' => 'F5512'
                ]
            ]
        ];

        $request = $this->createAluRequest();
        $request->getOrder()->withAirlineInfo($airlineInfo);

        $result = $this->createRequestArray();
        $result[self::AIRLINE_INFO_NODE] = $airlineInfoArray;

        // Then
        $this->assertEquals(
            $result,
            json_decode(
                $this->requestBuilder->buildAuthorizationRequest($request, $this->mockHashService),
                true
            )
        );
    }

    public function testWhenStoredCredentialsConsentTransaction()
    {
        // Given
        $storedCredentials = new StoredCredentials();
        $storedCredentials->setStoredCredentialsConsentType(StoredCredentials::CONSENT_TYPE_ON_DEMAND);

        $request = $this->createAluRequest();
        $request->setStoredCredentials($storedCredentials);

        $result = $this->createRequestArray();
        $result[self::STORED_CREDENTIALS_NODE]['consentType'] = StoredCredentials::CONSENT_TYPE_ON_DEMAND;
        $result[self::STORED_CREDENTIALS_NODE]['useType'] = null;
        $result[self::STORED_CREDENTIALS_NODE]['useId'] = null;

        // Then
        $this->assertEquals(
            $result,
            json_decode($this->requestBuilder->buildAuthorizationRequest($request), true)
        );

        $this->assertArrayNotHasKey(
            StoredCredentials::STORED_CREDENTIALS_USE_TYPE,
            json_decode($this->requestBuilder->buildAuthorizationRequest($request), true)
        );
    }

    public function testWhenStoredCredentialsRecurringConsentTransaction()
    {
        // Given
        $storedCredentials = new StoredCredentials();
        $storedCredentials->setStoredCredentialsConsentType(StoredCredentials::CONSENT_TYPE_RECURRING);

        $request = $this->createAluRequest();
        $request->setStoredCredentials($storedCredentials);

        $result = $this->createRequestArray();
        $result[self::STORED_CREDENTIALS_NODE]['consentType'] = StoredCredentials::CONSENT_TYPE_RECURRING;
        $result[self::STORED_CREDENTIALS_NODE]['useType'] = null;
        $result[self::STORED_CREDENTIALS_NODE]['useId'] = null;

        // Then
        $this->assertEquals(
            $result,
            json_decode($this->requestBuilder->buildAuthorizationRequest($request), true)
        );

        $this->assertArrayNotHasKey(
            StoredCredentials::STORED_CREDENTIALS_USE_TYPE,
            json_decode($this->requestBuilder->buildAuthorizationRequest($request), true)
        );
    }

    public function testWhenStoredCredentialsRecurringSubsequentTransaction()
    {
        // Given
        $storedCredentials = new StoredCredentials();
        $storedCredentials->setStoredCredentialsUseType(StoredCredentials::USE_TYPE_RECURRING);

        $request = $this->createAluRequest();
        $request->setStoredCredentials($storedCredentials);

        $result = $this->createRequestArray();
        $result[self::STORED_CREDENTIALS_NODE]['consentType'] = null;
        $result[self::STORED_CREDENTIALS_NODE]['useType'] = StoredCredentials::USE_TYPE_RECURRING;
        $result[self::STORED_CREDENTIALS_NODE]['useId'] = null;

        // Then
        $this->assertEquals(
            $result,
            json_decode($this->requestBuilder->buildAuthorizationRequest($request), true)
        );

        $this->assertArrayNotHasKey(
            StoredCredentials::STORED_CREDENTIALS_CONSENT_TYPE,
            json_decode($this->requestBuilder->buildAuthorizationRequest($request), true)
        );
    }

    public function testWhenStoredCredentialsCardOnFileCardholderInitiatedTransaction()
    {
        // Given
        $storedCredentials = new StoredCredentials();
        $storedCredentials->setStoredCredentialsUseType(StoredCredentials::USE_TYPE_CARDHOLDER);

        $request = $this->createAluRequest();
        $request->setStoredCredentials($storedCredentials);

        $result = $this->createRequestArray();
        $result[self::STORED_CREDENTIALS_NODE]['consentType'] = null;
        $result[self::STORED_CREDENTIALS_NODE]['useType'] = StoredCredentials::USE_TYPE_CARDHOLDER;
        $result[self::STORED_CREDENTIALS_NODE]['useId'] = null;

        // Then
        $this->assertEquals(
            $result,
            json_decode($this->requestBuilder->buildAuthorizationRequest($request), true)
        );

        $this->assertArrayNotHasKey(
            StoredCredentials::STORED_CREDENTIALS_CONSENT_TYPE,
            json_decode($this->requestBuilder->buildAuthorizationRequest($request), true)
        );
    }

    public function testWhenStoredCredentialsCardOnFileMerchantInitiatedTransaction()
    {
        // Given
        $storedCredentials = new StoredCredentials();
        $storedCredentials->setStoredCredentialsUseType(StoredCredentials::USE_TYPE_MERCHANT);

        $request = $this->createAluRequest();
        $request->setStoredCredentials($storedCredentials);

        $result = $this->createRequestArray();
        $result[self::STORED_CREDENTIALS_NODE]['consentType'] = null;
        $result[self::STORED_CREDENTIALS_NODE]['useType'] = StoredCredentials::USE_TYPE_MERCHANT;
        $result[self::STORED_CREDENTIALS_NODE]['useId'] = null;

        // Then
        $this->assertEquals(
            $result,
            json_decode($this->requestBuilder->buildAuthorizationRequest($request), true)
        );

        $this->assertArrayNotHasKey(
            StoredCredentials::STORED_CREDENTIALS_CONSENT_TYPE,
            json_decode($this->requestBuilder->buildAuthorizationRequest($request), true)
        );
    }
}

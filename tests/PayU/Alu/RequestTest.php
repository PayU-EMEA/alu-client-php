<?php

namespace PayU\Alu;

class RequestTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Request
     */
    private $request;

    /**
     * @var Order
     */
    private $order;

    public function setUp()
    {

        $cfg = new MerchantConfig('MERCHANT_CODE', 'SECRET_KEY', 'RO');

        $user = new User('127.0.0.1');

        $this->order = new Order();

        $this->order->withBackRef('http://path/to/your/returnUrlScript')
            ->withOrderRef('MerchantOrderRef')
            ->withCurrency('RON')
            ->withOrderDate('2014-09-19 10:00:00')
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

        $this->order->addProduct($product);

        $product = new Product();
        $product->withCode('PCODE02')
            ->withName('PNAME02')
            ->withPrice(200.0)
            ->withVAT(24.0)
            ->withQuantity(1);

        $this->order->addProduct($product);

        $billing = new Billing();

        $billing->withAddressLine1('ADDRESS1')
            ->withAddressLine2('ADDRESS2')
            ->withCity('Bucuresti')
            ->withCountryCode('RO')
            ->withEmail('john.doe@mail.com')
            ->withFirstName('John')
            ->withLastName('Doe')
            ->withPhoneNumber('0755167887')
            ->withIdentityCardNumber('324322');

        $delivery = new Delivery();
        $delivery->withAddressLine1('ADDRESS1')
            ->withAddressLine2('ADDRESS2')
            ->withCity('Istanbul')
            ->withCountryCode('RO')
            ->withEmail('john.doe@mail.com')
            ->withFirstName('John')
            ->withLastName('Doe')
            ->withPhoneNumber('0755167887');


        $card = new Card('5431210111111111', '11', 2016, 123, 'test');

        $this->request = new Request($cfg, $this->order, $billing, $delivery, $user);

        $this->request->setCard($card);
    }

    public function testGetParams()
    {
        $result = $this->createExpectedRequest();

        $this->assertEquals($result, $this->request->getRequestParams());
    }

    public function testGetParamsWithFx()
    {
        $result = $this->createExpectedRequest();

        $result['AUTHORIZATION_CURRENCY'] = 'EUR';
        $result['AUTHORIZATION_EXCHANGE_RATE'] = 0.2462;

        $fx = new FX('EUR', 0.2462);

        $this->request->setFx($fx);

        $this->assertEquals($result, $this->request->getRequestParams());
    }

    public function testWhenAirlineInfoIsSent()
    {

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
            'B',
            1,
            'MAXY12',
            'F5512'
        );
        $this->order->withAirlineInfo($airlineInfo);

        $airlineInfoResult = array(
            'AIRLINE_INFO' => array(
                'PASSENGER_NAME' => 'John Doe',
                'TICKET_NUMBER' => 'TICKET_1234',
                'RESTRICTED_REFUND' => 0,
                'RESERVATION_SYSTEM' => 'DATS',
                'TRAVEL_AGENCY_CODE' => 'MYTRAVEL',
                'TRAVEL_AGENCY_NAME' => 'My Travel Agency',
                'FLIGHT_SEGMENTS' => array(
                    array(
                        'DEPARTURE_DATE' => '2017-01-10',
                        'DEPARTURE_AIRPORT' => 'MOS',
                        'DESTINATION_AIRPORT' => 'SOF',
                        'AIRLINE_CODE' => null,
                        'SERVICE_CLASS' => null,
                        'STOPOVER' => null,
                        'FARE_CODE' => null,
                        'FLIGHT_NUMBER' => null,
                    ),
                    array(
                        'DEPARTURE_DATE' => '2017-02-10',
                        'DEPARTURE_AIRPORT' => 'ANK',
                        'DESTINATION_AIRPORT' => 'WDC',
                        'AIRLINE_CODE' => 'XY',
                        'SERVICE_CLASS' => 'B',
                        'STOPOVER' => 1,
                        'FARE_CODE' => 'MAXY12',
                        'FLIGHT_NUMBER' => 'F5512',
                    ),
                ),
            ),
        );

        $expectedRequest = $this->createExpectedRequest();

        $result = array_merge($airlineInfoResult, $expectedRequest);

        $this->assertEquals($result, $this->request->getRequestParams());
    }

    public function testWhenStoredCredentialsConsentTransaction()
    {

        $storedCredentials = new StoredCredentials();
        $storedCredentials->setStoredCredentialsConsentType(StoredCredentials::CONSENT_TYPE_ON_DEMAND);

        $this->order->setStoredCredentials($storedCredentials);

        $storedCredentialsResult = array(
            StoredCredentials::STORED_CREDENTIALS_CONSENT_TYPE => $storedCredentials->getStoredCredentialsConsentType()
        );

        $expectedRequest = $this->createExpectedRequest();

        $result = array_merge($storedCredentialsResult, $expectedRequest);

        $this->assertEquals($result, $this->request->getRequestParams());
        $this->assertArrayNotHasKey(StoredCredentials::STORED_CREDENTIALS_USE_TYPE, $this->request->getRequestParams());
    }

    public function testWhenStoredCredentialsRecurringConsentTransaction()
    {
        $storedCredentials = new StoredCredentials();
        $storedCredentials->setStoredCredentialsConsentType(StoredCredentials::CONSENT_TYPE_RECURRING);

        $this->order->setStoredCredentials($storedCredentials);

        $storedCredentialsResult = array(
            StoredCredentials::STORED_CREDENTIALS_CONSENT_TYPE => $storedCredentials->getStoredCredentialsConsentType()
        );

        $expectedRequest = $this->createExpectedRequest();

        $result = array_merge($storedCredentialsResult, $expectedRequest);

        $this->assertEquals($result, $this->request->getRequestParams());
        $this->assertArrayNotHasKey(StoredCredentials::STORED_CREDENTIALS_USE_TYPE, $this->request->getRequestParams());
    }

    public function testWhenStoredCredentialsRecurringSubsequentTransaction()
    {
        $storedCredentials = new StoredCredentials();
        $storedCredentials->setStoredCredentialsUseType(StoredCredentials::USE_TYPE_RECURRING);

        $this->order->setStoredCredentials($storedCredentials);

        $storedCredentialsResult = array(
            StoredCredentials::STORED_CREDENTIALS_USE_TYPE => $storedCredentials->getStoredCredentialsUseType()
        );

        $expectedRequest = $this->createExpectedRequest();

        $result = array_merge($storedCredentialsResult, $expectedRequest);

        $this->assertEquals($result, $this->request->getRequestParams());
        $this->assertArrayNotHasKey(StoredCredentials::STORED_CREDENTIALS_CONSENT_TYPE, $this->request->getRequestParams());
    }

    public function testWhenStoredCredentialsCardOnFileCardholderInitiatedTransaction()
    {
        $storedCredentials = new StoredCredentials();
        $storedCredentials->setStoredCredentialsUseType(StoredCredentials::USE_TYPE_CARDHOLDER);

        $this->order->setStoredCredentials($storedCredentials);

        $storedCredentialsResult = array(
            StoredCredentials::STORED_CREDENTIALS_USE_TYPE => $storedCredentials->getStoredCredentialsUseType()
        );

        $expectedRequest = $this->createExpectedRequest();

        $result = array_merge($storedCredentialsResult, $expectedRequest);

        $this->assertEquals($result, $this->request->getRequestParams());
        $this->assertArrayNotHasKey(StoredCredentials::STORED_CREDENTIALS_CONSENT_TYPE, $this->request->getRequestParams());
    }

    public function testWhenStoredCredentialsCardOnFileMerchantInitiatedTransaction()
    {
        $storedCredentials = new StoredCredentials();
        $storedCredentials->setStoredCredentialsUseType(StoredCredentials::USE_TYPE_MERCHANT);

        $this->order->setStoredCredentials($storedCredentials);

        $storedCredentialsResult = array(
            StoredCredentials::STORED_CREDENTIALS_USE_TYPE => $storedCredentials->getStoredCredentialsUseType()
        );

        $expectedRequest = $this->createExpectedRequest();

        $result = array_merge($storedCredentialsResult, $expectedRequest);

        $this->assertEquals($result, $this->request->getRequestParams());
        $this->assertArrayNotHasKey(StoredCredentials::STORED_CREDENTIALS_CONSENT_TYPE, $this->request->getRequestParams());
    }

    /**
     * @return array
     */
    public function createExpectedRequest()
    {
        $result = array(
            'ALIAS' => null,
            'BACK_REF' => 'http://path/to/your/returnUrlScript',
            'BILL_ADDRESS' => 'ADDRESS1',
            'BILL_ADDRESS2' => 'ADDRESS2',
            'BILL_BANK' => null,
            'BILL_BANKACCOUNT' => null,
            'BILL_CIISSUER' => null,
            'BILL_CINUMBER' => '324322',
            'BILL_CISERIAL' => null,
            'BILL_CITYPE' => null,
            'BILL_CITY' => 'Bucuresti',
            'BILL_CNP' => null,
            'BILL_COMPANY' => null,
            'BILL_COUNTRYCODE' => 'RO',
            'BILL_EMAIL' => 'john.doe@mail.com',
            'BILL_FAX' => null,
            'BILL_FISCALCODE' => null,
            'BILL_FNAME' => 'John',
            'BILL_LNAME' => 'Doe',
            'BILL_PHONE' => '0755167887',
            'BILL_REGNUMBER' => null,
            'BILL_STATE' => null,
            'BILL_ZIPCODE' => null,
            'CARD_PROGRAM_NAME' => null,
            'CC_CVV' => 123,
            'CC_NUMBER' => '5431210111111111',
            'CC_NUMBER_RECIPIENT' => null,
            'CC_OWNER' => 'test',
            'CLIENT_IP' => '127.0.0.1',
            'CLIENT_TIME' => '',
            'DELIVERY_ADDRESS' => 'ADDRESS1',
            'DELIVERY_ADDRESS2' => 'ADDRESS2',
            'DELIVERY_CITY' => 'Istanbul',
            'DELIVERY_COMPANY' => null,
            'DELIVERY_COUNTRYCODE' => 'RO',
            'DELIVERY_EMAIL' => 'john.doe@mail.com',
            'DELIVERY_FNAME' => 'John',
            'DELIVERY_LNAME' => 'Doe',
            'DELIVERY_PHONE' => '0755167887',
            'DELIVERY_STATE' => null,
            'DELIVERY_ZIPCODE' => null,
            'DISCOUNT' => null,
            'EXP_MONTH' => '11',
            'EXP_YEAR' => 2016,
            'MERCHANT' => 'MERCHANT_CODE',
            'ORDER_DATE' => '2014-09-19 10:00:00',
            'ORDER_MPLACE_MERCHANT' =>
                array(
                    0 => null,
                    1 => null,
                ),
            'ORDER_PCODE' =>
                array(
                    0 => 'PCODE01',
                    1 => 'PCODE02',
                ),
            'ORDER_PGROUP' =>
                array(
                    0 => null,
                    1 => null,
                ),
            'ORDER_PINFO' =>
                array(
                    0 => null,
                    1 => null,
                ),
            'ORDER_PNAME' =>
                array(
                    0 => 'PNAME01',
                    1 => 'PNAME02',
                ),
            'ORDER_PRICE' =>
                array(
                    0 => 100,
                    1 => 200,
                ),
            'ORDER_QTY' =>
                array(
                    0 => 1,
                    1 => 1,
                ),
            'ORDER_REF' => 'MerchantOrderRef',
            'ORDER_SHIPPING' => null,
            'ORDER_VER' =>
                array(
                    0 => null,
                    1 => null,
                ),
            'PAY_METHOD' => 'CCVISAMC',
            'PRICES_CURRENCY' => 'RON',
            'SELECTED_INSTALLMENTS_NUMBER' => '2',
            'USE_LOYALTY_POINTS' => null,
            'LOYALTY_POINTS_AMOUNT' => null,
            'CAMPAIGN_TYPE' => 'EXTRA_INSTALLMENTS',
            'ORDER_PRICE_TYPE' =>
                array(
                    0 => 'NET',
                    1 => 'NET',
                ),
            'ORDER_VAT' =>
                array(
                    0 => 24,
                    1 => 24,
                ),
        );
        return $result;
    }
}

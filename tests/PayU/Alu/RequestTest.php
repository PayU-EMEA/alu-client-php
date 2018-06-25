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

        $fx = new FX('EUR', 0.2462);

        $this->request = new Request($cfg, $this->order, $billing, $delivery, $user, $fx);

        $this->request->setCard($card);
    }

    public function testGetParams()
    {
        $result = array(
            'ALIAS' => NULL,
            'BACK_REF' => 'http://path/to/your/returnUrlScript',
            'BILL_ADDRESS' => 'ADDRESS1',
            'BILL_ADDRESS2' => 'ADDRESS2',
            'BILL_BANK' => NULL,
            'BILL_BANKACCOUNT' => NULL,
            'BILL_CIISSUER' => NULL,
            'BILL_CINUMBER' => '324322',
            'BILL_CISERIAL' => NULL,
            'BILL_CITYPE' => NULL,
            'BILL_CITY' => 'Bucuresti',
            'BILL_CNP' => NULL,
            'BILL_COMPANY' => NULL,
            'BILL_COUNTRYCODE' => 'RO',
            'BILL_EMAIL' => 'john.doe@mail.com',
            'BILL_FAX' => NULL,
            'BILL_FISCALCODE' => NULL,
            'BILL_FNAME' => 'John',
            'BILL_LNAME' => 'Doe',
            'BILL_PHONE' => '0755167887',
            'BILL_REGNUMBER' => NULL,
            'BILL_STATE' => NULL,
            'BILL_ZIPCODE' => NULL,
            'CARD_PROGRAM_NAME' => NULL,
            'CC_CVV' => 123,
            'CC_NUMBER' => '5431210111111111',
            'CC_NUMBER_RECIPIENT' => NULL,
            'CC_OWNER' => 'test',
            'CLIENT_IP' => '127.0.0.1',
            'CLIENT_TIME' => '',
            'DELIVERY_ADDRESS' => 'ADDRESS1',
            'DELIVERY_ADDRESS2' => 'ADDRESS2',
            'DELIVERY_CITY' => 'Istanbul',
            'DELIVERY_COMPANY' => NULL,
            'DELIVERY_COUNTRYCODE' => 'RO',
            'DELIVERY_EMAIL' => 'john.doe@mail.com',
            'DELIVERY_FNAME' => 'John',
            'DELIVERY_LNAME' => 'Doe',
            'DELIVERY_PHONE' => '0755167887',
            'DELIVERY_STATE' => NULL,
            'DELIVERY_ZIPCODE' => NULL,
            'DISCOUNT' => NULL,
            'EXP_MONTH' => '11',
            'EXP_YEAR' => 2016,
            'MERCHANT' => 'MERCHANT_CODE',
            'ORDER_DATE' => '2014-09-19 10:00:00',
            'ORDER_MPLACE_MERCHANT' =>
                array (
                    0 => NULL,
                    1 => NULL,
                ),
            'ORDER_PCODE' =>
                array (
                    0 => 'PCODE01',
                    1 => 'PCODE02',
                ),
            'ORDER_PGROUP' =>
                array (
                    0 => NULL,
                    1 => NULL,
                ),
            'ORDER_PINFO' =>
                array (
                    0 => NULL,
                    1 => NULL,
                ),
            'ORDER_PNAME' =>
                array (
                    0 => 'PNAME01',
                    1 => 'PNAME02',
                ),
            'ORDER_PRICE' =>
                array (
                    0 => 100,
                    1 => 200,
                ),
            'ORDER_QTY' =>
                array (
                    0 => 1,
                    1 => 1,
                ),
            'ORDER_REF' => 'MerchantOrderRef',
            'ORDER_SHIPPING' => NULL,
            'ORDER_VER' =>
                array (
                    0 => NULL,
                    1 => NULL,
                ),
            'PAY_METHOD' => 'CCVISAMC',
            'PRICES_CURRENCY' => 'RON',
            'SELECTED_INSTALLMENTS_NUMBER' => '2',
            'USE_LOYALTY_POINTS' => NULL,
            'LOYALTY_POINTS_AMOUNT' => NULL,
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
            'AUTHORIZATION_CURRENCY' => 'EUR',
            'AUTHORIZATION_EXCHANGE_RATE' => 0.2462,
        );
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
        
        $result = array(
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
            'ALIAS' => NULL,
            'BACK_REF' => 'http://path/to/your/returnUrlScript',
            'BILL_ADDRESS' => 'ADDRESS1',
            'BILL_ADDRESS2' => 'ADDRESS2',
            'BILL_BANK' => NULL,
            'BILL_BANKACCOUNT' => NULL,
            'BILL_CIISSUER' => NULL,
            'BILL_CINUMBER' => '324322',
            'BILL_CISERIAL' => NULL,
            'BILL_CITYPE' => NULL,
            'BILL_CITY' => 'Bucuresti',
            'BILL_CNP' => NULL,
            'BILL_COMPANY' => NULL,
            'BILL_COUNTRYCODE' => 'RO',
            'BILL_EMAIL' => 'john.doe@mail.com',
            'BILL_FAX' => NULL,
            'BILL_FISCALCODE' => NULL,
            'BILL_FNAME' => 'John',
            'BILL_LNAME' => 'Doe',
            'BILL_PHONE' => '0755167887',
            'BILL_REGNUMBER' => NULL,
            'BILL_STATE' => NULL,
            'BILL_ZIPCODE' => NULL,
            'CARD_PROGRAM_NAME' => NULL,
            'CC_CVV' => 123,
            'CC_NUMBER' => '5431210111111111',
            'CC_NUMBER_RECIPIENT' => NULL,
            'CC_OWNER' => 'test',
            'CLIENT_IP' => '127.0.0.1',
            'CLIENT_TIME' => '',
            'DELIVERY_ADDRESS' => 'ADDRESS1',
            'DELIVERY_ADDRESS2' => 'ADDRESS2',
            'DELIVERY_CITY' => 'Istanbul',
            'DELIVERY_COMPANY' => NULL,
            'DELIVERY_COUNTRYCODE' => 'RO',
            'DELIVERY_EMAIL' => 'john.doe@mail.com',
            'DELIVERY_FNAME' => 'John',
            'DELIVERY_LNAME' => 'Doe',
            'DELIVERY_PHONE' => '0755167887',
            'DELIVERY_STATE' => NULL,
            'DELIVERY_ZIPCODE' => NULL,
            'DISCOUNT' => NULL,
            'EXP_MONTH' => '11',
            'EXP_YEAR' => 2016,
            'MERCHANT' => 'MERCHANT_CODE',
            'ORDER_DATE' => '2014-09-19 10:00:00',
            'ORDER_MPLACE_MERCHANT' =>
                array (
                    0 => NULL,
                    1 => NULL,
                ),
            'ORDER_PCODE' =>
                array (
                    0 => 'PCODE01',
                    1 => 'PCODE02',
                ),
            'ORDER_PGROUP' =>
                array (
                    0 => NULL,
                    1 => NULL,
                ),
            'ORDER_PINFO' =>
                array (
                    0 => NULL,
                    1 => NULL,
                ),
            'ORDER_PNAME' =>
                array (
                    0 => 'PNAME01',
                    1 => 'PNAME02',
                ),
            'ORDER_PRICE' =>
                array (
                    0 => 100,
                    1 => 200,
                ),
            'ORDER_QTY' =>
                array (
                    0 => 1,
                    1 => 1,
                ),
            'ORDER_REF' => 'MerchantOrderRef',
            'ORDER_SHIPPING' => NULL,
            'ORDER_VER' =>
                array (
                    0 => NULL,
                    1 => NULL,
                ),
            'PAY_METHOD' => 'CCVISAMC',
            'PRICES_CURRENCY' => 'RON',
            'SELECTED_INSTALLMENTS_NUMBER' => '2',
            'USE_LOYALTY_POINTS' => NULL,
            'LOYALTY_POINTS_AMOUNT' => NULL,
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
        $this->assertEquals($result, $this->request->getRequestParams());
    }
}

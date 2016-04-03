<?php

namespace PayU\Alu;


class RequestTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Request
     */
    private $request;

    public function setUp()
    {

        $cfg = new MerchantConfig('MERCHANT_CODE', 'SECRET_KEY', 'RO');

        $user = new User('127.0.0.1');

        $order = new Order();

        $order->withBackRef('http://path/to/your/returnUrlScript')
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

        $order->addProduct($product);

        $product = new Product();
        $product->withCode('PCODE02')
            ->withName('PNAME02')
            ->withPrice(200.0)
            ->withVAT(24.0)
            ->withQuantity(1);

        $order->addProduct($product);

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
            ->withCity('Bucuresti')
            ->withCountryCode('RO')
            ->withEmail('john.doe@mail.com')
            ->withFirstName('John')
            ->withLastName('Doe')
            ->withPhoneNumber('0755167887');


        $card = new Card('5431210111111111', '11', 2016, 123, 'test');

        $this->request = new Request($cfg, $order, $billing, $delivery, $user);

        $this->request->setCard($card);

    }

    public function testGetParams()
    {
        $result = array (
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
            'DELIVERY_CITY' => NULL,
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
        );
        $this->assertEquals($result, $this->request->getRequestParams());
    }

}
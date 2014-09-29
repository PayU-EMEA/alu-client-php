<?php

namespace PayU\Alu;

class HashServiceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var HashService
     */
    private $hashService;

    /**
     * @var Request| \PHPUnit_Framework_MockObject_MockObject
     */
    private $requestMock;

    /**
     * @var Response| \PHPUnit_Framework_MockObject_MockObject
     */
    private $responseMock;

    public function setUp()
    {

        $this->hashService = new HashService('SECRET_KEY');

        $this->requestMock = $this->getMockBuilder('\PayU\Alu\Request')
            ->disableOriginalConstructor()
            ->getMock();

        $this->responseMock = $this->getMockBuilder('\PayU\Alu\Response')
            ->disableOriginalConstructor()
            ->getMock();

    }

    public function requestParamsProvider()
    {
        return array(
            array(
                 array(
                     'ALIAS' => NULL,
                     'BACK_REF' => 'http://path/to/your/returnUrlScript',
                     'BILL_ADDRESS' => 'ADDRESS1',
                     'BILL_ADDRESS2' => 'ADDRESS2',
                     'BILL_BANK' => NULL,
                     'BILL_BANKACCOUNT' => NULL,
                     'BILL_CIISSUER' => NULL,
                     'BILL_CINUMBER' => '324322',
                     'BILL_CISERIAL' => NULL,
                     'BILL_CITY' => 'Bucuresti',
                     'BILL_CNP' => NULL,
                     'BILL_COMPANY' => NULL,
                     'BILL_COUNTRYCODE' => 'RO',
                     'BILL_EMAIL' => 'john.doe@mail.com',
                     'BILL_FAX' => NULL,
                     'BILL_FISCALCODE' => NULL,
                     'BILL_FNAME' => 'John',
                     'BILL_LNAME' => 'Doe',
                     'BILL_PHONE' => '0751456789',
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
                     'DELIVERY_PHONE' => '0751456789',
                     'DELIVERY_STATE' => NULL,
                     'DELIVERY_ZIPCODE' => NULL,
                     'DISCOUNT' => NULL,
                     'EXP_MONTH' => '11',
                     'EXP_YEAR' => 2016,
                     'MERCHANT' => 'MERCHANT_CODE',
                     'ORDER_DATE' => '2014-09-19 08:07:57',
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
                     'ORDER_REF' => '90000',
                     'ORDER_SHIPPING' => NULL,
                     'ORDER_VER' =>
                         array (
                             0 => NULL,
                             1 => NULL,
                         ),
                     'PAY_METHOD' => 'CCVISAMC',
                     'PRICES_CURRENCY' => 'RON',
                     'SELECTED_INSTALLMENTS_NUMBER' => NULL,
                 ),
                'd6ebc94ef712b184c323f949633270c6'
            )
        );
    }

    /**
     * @dataProvider requestParamsProvider
     */
    public function testMakeRequestHash($requestParams, $expectedHash)
    {
        $this->requestMock->expects($this->once())->method('getRequestParams')
                    ->will($this->returnValue($requestParams));

        $this->assertEquals($expectedHash, $this->hashService->makeRequestHash($this->requestMock));
    }


    public function responseParamsProvider()
    {
        return array(
            array(
                array(
                    'REFNO' => '11844987',
                    'ALIAS' => '',
                    'STATUS' => 'FAILED',
                    'RETURN_CODE' => 'ALREADY_AUTHORIZED',
                    'RETURN_MESSAGE' => 'The payment for your order is already authorized.',
                    'DATE' => '2014-09-19 11:14:50',
                    'ORDER_REF' => '90000',
                    'AUTH_CODE' => '',
                    'RRN' => ''
                ),
                'c532e4077e20582afcc02d7c7c1fb316'
            )
        );
    }

    /**
     * @dataProvider responseParamsProvider
     */
    public function testValidateResponseHash($responseParams, $responseExpectedHash)
    {
        $this->responseMock->expects($this->once())->method('getResponseParams')
            ->will($this->returnValue($responseParams));

        $this->responseMock->expects($this->once())->method('getHash')
            ->will($this->returnValue($responseExpectedHash));

        $this->hashService->validateResponseHash($this->responseMock);
    }


    /**
     * @dataProvider responseParamsProvider
     * @expectedException \PayU\Alu\Exceptions\ClientException
     */
    public function testValidateResponseHashFail($responseParams, $responseExpectedHash)
    {
        $this->responseMock->expects($this->once())->method('getResponseParams')
            ->will($this->returnValue($responseParams));

        $this->responseMock->expects($this->once())->method('getHash')
            ->will($this->returnValue($responseExpectedHash . 'BREAK_THE_HASH'));

        $this->hashService->validateResponseHash($this->responseMock);
    }

}
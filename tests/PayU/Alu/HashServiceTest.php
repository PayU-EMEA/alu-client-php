<?php

namespace PayU\Alu;

use PayU\Alu\HashService;

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

        $this->requestMock = $this->getMockBuilder(\PayU\Alu\Request::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->responseMock = $this->getMockBuilder(\PayU\Alu\Response::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    public function requestParamsProvider()
    {
        return array(
            array(
                array(
                    'ALIAS' => null,
                    'BACK_REF' => 'http://path/to/your/returnUrlScript',
                    'BILL_ADDRESS' => 'ADDRESS1',
                    'BILL_ADDRESS2' => 'ADDRESS2',
                    'BILL_BANK' => null,
                    'BILL_BANKACCOUNT' => null,
                    'BILL_CIISSUER' => null,
                    'BILL_CINUMBER' => '324322',
                    'BILL_CISERIAL' => null,
                    'BILL_CITY' => 'Bucuresti',
                    'BILL_CNP' => null,
                    'BILL_COMPANY' => null,
                    'BILL_COUNTRYCODE' => 'RO',
                    'BILL_EMAIL' => 'john.doe@mail.com',
                    'BILL_FAX' => null,
                    'BILL_FISCALCODE' => null,
                    'BILL_FNAME' => 'John',
                    'BILL_LNAME' => 'Doe',
                    'BILL_PHONE' => '0751456789',
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
                    'DELIVERY_CITY' => null,
                    'DELIVERY_COMPANY' => null,
                    'DELIVERY_COUNTRYCODE' => 'RO',
                    'DELIVERY_EMAIL' => 'john.doe@mail.com',
                    'DELIVERY_FNAME' => 'John',
                    'DELIVERY_LNAME' => 'Doe',
                    'DELIVERY_PHONE' => '0751456789',
                    'DELIVERY_STATE' => null,
                    'DELIVERY_ZIPCODE' => null,
                    'DISCOUNT' => null,
                    'EXP_MONTH' => '11',
                    'EXP_YEAR' => 2016,
                    'MERCHANT' => 'MERCHANT_CODE',
                    'ORDER_DATE' => '2014-09-19 08:07:57',
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
                    'ORDER_REF' => '90000',
                    'ORDER_SHIPPING' => null,
                    'ORDER_VER' =>
                        array(
                            0 => null,
                            1 => null,
                        ),
                    'PAY_METHOD' => 'CCVISAMC',
                    'PRICES_CURRENCY' => 'RON',
                    'SELECTED_INSTALLMENTS_NUMBER' => null,
                    'USE_LOYALTY_POINTS' => 'YES',
                    'LOYALTY_POINTS_AMOUNT' => 50,
                    'CAMPAIGN_TYPE' => null,
                ),
                'a302862d9d9f883c95e2cb8351d1f3bb',
            )
        );
    }

    /**
     * @dataProvider requestParamsProvider
     */
    public function testMakeRequestHash($requestParams, $expectedHash)
    {
        // Then
        $this->assertEquals($expectedHash, $this->hashService->makeRequestHash($requestParams));
    }


    public function responseParamsProvider()
    {
        return array(
            array(
                array(
                    'REFNO' => '11844987',
                    'ALIAS' => '',
                    'STATUS' => 'SUCCESS',
                    'RETURN_CODE' => 'AUTHORIZED',
                    'RETURN_MESSAGE' => 'The payment for your order authorized.',
                    'DATE' => '2015-11-15 09:32:50',
                ),
                '3f112ab43ff5114957d086134d94ec60'
            ),
            array(
                array(
                    'REFNO' => '11844987',
                    'ALIAS' => '',
                    'STATUS' => 'SUCCESS',
                    'RETURN_CODE' => 'AUTHORIZED',
                    'RETURN_MESSAGE' => 'The payment for your order authorized.',
                    'DATE' => '2015-11-15 09:39:24',
                    'AMOUNT' => '199.8',
                    'CURRENCY' => 'TRY',
                    'INSTALLMENTS_NO' => '6',
                    'CARD_PROGRAM_NAME' => 'Advantage',
                ),
                'ece32d1ab3d0e2c2218131c80d0b0ba9'
            ),
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
            ),
            array(
                array(
                    'REFNO' => '11867687',
                    'ALIAS' => '0d4a0b58e9caeb07d1d43bf1ba8f4401',
                    'STATUS' => 'SUCCESS',
                    'RETURN_CODE' => 'PENDING_AUTHORIZATION',
                    'RETURN_MESSAGE' => 'Order saved and pending authorization.',
                    'DATE' => '2015-04-06 15:42:43',
                    'ORDER_REF' => 'EXT_2301428323957',
                    'AUTH_CODE' => '',
                    'RRN' => '',
                    'WIRE_ACCOUNTS' => array(
                        array(
                            'BANK_IDENTIFIER' => 'BANCA AGRICOLA-RAIFFEISEN S.A.',
                            'BANK_ACCOUNT' => 'a12c8c196b11afb9beb8fe6221540a4f',
                            'ROUTING_NUMBER' => '',
                            'IBAN_ACCOUNT' => '',
                            'BANK_SWIFT' => 'BANK7',
                            'COUNTRY' => 'Romania',
                            'WIRE_RECIPIENT_NAME' => 'GECAD ePayment International SA SRL',
                            'WIRE_RECIPIENT_VAT_ID' => 'RO16490162',
                        ),
                        array(
                            'BANK_IDENTIFIER' => 'BRD Groupe Societe Generale',
                            'BANK_ACCOUNT' => 'a82d196141b7a58b60c49c40afe9b90f',
                            'ROUTING_NUMBER' => '',
                            'IBAN_ACCOUNT' => '',
                            'BANK_SWIFT' => 'BRDEURBU',
                            'COUNTRY' => 'Romania',
                            'WIRE_RECIPIENT_NAME' => 'GECAD ePayment International SA SRL',
                            'WIRE_RECIPIENT_VAT_ID' => 'RO16490162',
                        ),
                        array(
                            'BANK_IDENTIFIER' => 'BCR',
                            'BANK_ACCOUNT' => 'd14cd64064813aacaac1ce9d55731af9',
                            'ROUTING_NUMBER' => '',
                            'IBAN_ACCOUNT' => '',
                            'BANK_SWIFT' => 'BANK7',
                            'COUNTRY' => 'Romania',
                            'WIRE_RECIPIENT_NAME' => 'GECAD ePayment International SA SRL',
                            'WIRE_RECIPIENT_VAT_ID' => 'RO16490162',
                        ),
                    ),
                ),
                '42489dd903731b8ff8c0cd50a4b2939c'
            )
        );
    }

    /**
     * @dataProvider responseParamsProvider
     */
    public function testValidateResponseHash($responseParams, $responseExpectedHash)
    {
        // Given
        $this->responseMock->expects($this->once())
            ->method('getResponseParams')
            ->willReturn($responseParams);

        $this->responseMock->expects($this->once())
            ->method('getHash')
            ->willReturn($responseExpectedHash);

        // When
        $this->hashService->validateResponseHash($this->responseMock);
    }


    /**
     * @dataProvider responseParamsProvider
     * @expectedException \PayU\Alu\Exceptions\ClientException
     */
    public function testValidateResponseHashFail($responseParams, $responseExpectedHash)
    {
        // Given
        $this->responseMock->expects($this->once())
            ->method('getResponseParams')
            ->willReturn($responseParams);

        $this->responseMock->expects($this->once())
            ->method('getHash')
            ->willReturn($responseExpectedHash . 'BREAK_THE_HASH');

        // When
        $this->hashService->validateResponseHash($this->responseMock);
    }
}

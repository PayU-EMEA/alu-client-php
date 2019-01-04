<?php
namespace PayU\Alu\Test;

use PayU\Alu\Component\Request;
use PayU\Alu\Component\Response;
use PayU\Alu\HashService;
use PayU\Alu\MerchantConfig;
use PayU\Alu\Platform;
use Payu\Alu\Transformer\RequestTransformer;
use PayU\Alu\Transformer\ResponseTransformer;
use PHPUnit\Framework\TestCase;

class HashServiceTest extends TestCase
{
    private $hashService;

    private $config;

    public function setUp()
    {
        $this->config = new MerchantConfig('CC5857', 'SECRET_KEY', Platform::ROMANIA);
        $this->hashService = new HashService($this->config);
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
                    'USE_LOYALTY_POINTS' => 'YES',
                    'LOYALTY_POINTS_AMOUNT' => 50,
                    'CAMPAIGN_TYPE' => NULL,
                ),
                'a302862d9d9f883c95e2cb8351d1f3bb',
            )
        );
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
                array (
                    'REFNO' => '11867687',
                    'ALIAS' => '0d4a0b58e9caeb07d1d43bf1ba8f4401',
                    'STATUS' => 'SUCCESS',
                    'RETURN_CODE' => 'PENDING_AUTHORIZATION',
                    'RETURN_MESSAGE' => 'Order saved and pending authorization.',
                    'DATE' => '2015-04-06 15:42:43',
                    'ORDER_REF' => 'EXT_2301428323957',
                    'AUTH_CODE' => '',
                    'RRN' => '',
                    'WIRE_ACCOUNTS' => array (
                        array (
                            'BANK_IDENTIFIER' => 'BANCA AGRICOLA-RAIFFEISEN S.A.',
                            'BANK_ACCOUNT' => 'a12c8c196b11afb9beb8fe6221540a4f',
                            'ROUTING_NUMBER' => '',
                            'IBAN_ACCOUNT' => '',
                            'BANK_SWIFT' => 'BANK7',
                            'COUNTRY' => 'Romania',
                            'WIRE_RECIPIENT_NAME' => 'GECAD ePayment International SA SRL',
                            'WIRE_RECIPIENT_VAT_ID' => 'RO16490162',
                        ),
                        array (
                            'BANK_IDENTIFIER' => 'BRD Groupe Societe Generale',
                            'BANK_ACCOUNT' => 'a82d196141b7a58b60c49c40afe9b90f',
                            'ROUTING_NUMBER' => '',
                            'IBAN_ACCOUNT' => '',
                            'BANK_SWIFT' => 'BRDEURBU',
                            'COUNTRY' => 'Romania',
                            'WIRE_RECIPIENT_NAME' => 'GECAD ePayment International SA SRL',
                            'WIRE_RECIPIENT_VAT_ID' => 'RO16490162',
                        ),
                        array (
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
     * @dataProvider requestParamsProvider
     * @param $requestData
     * @param $hash
     */
    public function testSign($requestData, $hash)
    {

        $requestTransformerMock = $this->getMockBuilder(RequestTransformer::class)
            ->setConstructorArgs(array($this->config))
            ->getMock();

        $requestTransformerMock->expects($this->once())
            ->method('transform')
            ->willReturn($requestData);

        $requestMock = $this->getMockBuilder(Request::class)->disableOriginalConstructor()->getMock();

        $reflectionHashService = new \ReflectionObject($this->hashService);

        $requestTransformerProperty = $reflectionHashService->getProperty('requestTransformer');
        $requestTransformerProperty->setAccessible(true);
        $requestTransformerProperty->setValue($this->hashService, $requestTransformerMock);

        $signMethod = $reflectionHashService->getMethod('sign');
        $signedRequest = $signMethod->invokeArgs($this->hashService, array($requestMock));
        $expected = $requestData;
        $expected['ORDER_HASH'] = $hash;
        $this->assertEquals($expected, $signedRequest);
    }

    /**
     * @dataProvider responseParamsProvider
     * @param $responseData
     * @param $hash
     */
    public function testValidateResponseSucces($responseData, $hash)
    {
        $responseTransformerMock = $this->getMockBuilder(ResponseTransformer::class)
            ->setConstructorArgs(array($this->config))
            ->getMock();

        $responseTransformerMock->expects($this->once())
            ->method("transform")
            ->willReturn($responseData);

        $responseMock = $this->getMockBuilder(Response::class)->disableOriginalConstructor()->getMock();
        $responseMock->expects($this->once())
            ->method("getHash")
            ->willReturn($hash);

        $reflectionHashService = new \ReflectionObject($this->hashService);
        $responseTransformerProperty = $reflectionHashService->getProperty("responseTransformer");
        $responseTransformerProperty->setAccessible(true);
        $responseTransformerProperty->setValue($this->hashService, $responseTransformerMock);

        $validateResponseMethod = $reflectionHashService->getMethod("validateResponse");
        $validateResponseMethod->invokeArgs($this->hashService, array($responseMock));
    }

    /**
     * @dataProvider responseParamsProvider
     * @expectedException \PayU\Alu\Exception\ClientException
     * @expectedExceptionMessage Response HASH mismatch
     * @param $responseData
     * @param $hash
     */
    public function testValidateResponseFail($responseData, $hash)
    {
        $responseTransformerMock = $this->getMockBuilder(ResponseTransformer::class)
            ->setConstructorArgs(array($this->config))
            ->getMock();

        $responseTransformerMock->expects($this->once())
            ->method("transform")
            ->willReturn($responseData);

        $responseMock = $this->getMockBuilder(Response::class)->disableOriginalConstructor()->getMock();
        $responseMock->expects($this->once())
            ->method("getHash")
            ->willReturn($hash . "BREAK_THE_HASH");

        $reflectionHashService = new \ReflectionObject($this->hashService);
        $responseTransformerProperty = $reflectionHashService->getProperty("responseTransformer");
        $responseTransformerProperty->setAccessible(true);
        $responseTransformerProperty->setValue($this->hashService, $responseTransformerMock);

        $validateResponseMethod = $reflectionHashService->getMethod("validateResponse");
        $validateResponseMethod->invokeArgs($this->hashService, array($responseMock));
    }
}

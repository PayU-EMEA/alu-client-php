<?php

namespace PayU\PaymentsApi\AluV3;

use PayU\Alu\Billing;
use PayU\Alu\HashService;
use PayU\Alu\HTTPClient;
use PayU\Alu\MerchantConfig;
use PayU\Alu\Order;
use PayU\Alu\Product;
use PayU\Alu\Request;
use PayU\Alu\Response;

class AluV3Test extends \PHPUnit_Framework_TestCase
{
    const HASH_STRING = "HASH";
    const REF_NO = "3244554";
    const STATUS = "SUCCESS";
    const RETURN_CODE = "AUTHORIZED";
    const RETURN_MESSAGE = "Authorized.";
    const ORDER_DATE = "2020-09-19 10:00:00";
    const ORDER_REF = "90003";

    /**
     * @var AluV3
     */
    private $aluV3;

    /**
     * @var HashService|\PHPUnit_Framework_MockObject_MockObject
     */
    private $hashServiceMock;

    /**
     * @var HTTPClient|\PHPUnit_Framework_MockObject_MockObject
     */
    private $httpClientMock;

    public function setUp()
    {
        $this->hashServiceMock = $this->getMockBuilder('PayU\Alu\HashService')
            ->disableOriginalConstructor()
            ->getMock();

        $this->httpClientMock = $this->getMockBuilder('PayU\Alu\HTTPClient')
            ->disableOriginalConstructor()
            ->getMock();

        $this->aluV3 = new AluV3(
            $this->httpClientMock,
            $this->hashServiceMock
        );
    }

    /**
     * @return Request
     */
    private function createAluRequest()
    {
        $cfg = new MerchantConfig('CC5857', 'SECRET_KEY', 'RO');

        $order = new Order();

        $order->withBackRef('http://path/to/your/returnUrlScript')
            ->withOrderRef('MerchantOrderRef')
            ->withCurrency('RON')
            ->withOrderDate(self::ORDER_DATE)
            ->withOrderTimeout(1000)
            ->withPayMethod('CCVISAMC');

        $product = new Product();
        $product->withCode('PCODE01')
            ->withName('PNAME01')
            ->withPrice(100.0)
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

        return new Request($cfg, $order, $billing);
    }

    /**
     * @return array
     */
    private function createRequestArray()
    {
        return [
            "BACK_REF" => "http://path/to/your/returnUrlScript",
            "BILL_ADDRESS" => "ADDRESS1",
            "BILL_ADDRESS2" => "ADDRESS2",
            "BILL_CINUMBER" => "324322",
            "BILL_CITY" => "Bucuresti",
            "BILL_COUNTRYCODE" => "RO",
            "BILL_EMAIL" => "john.doe@mail.com",
            "BILL_FNAME" => "John",
            "BILL_LNAME" => "Doe",
            "BILL_PHONE" => "0755167887",
            "MERCHANT" => "CC5857",
            "ORDER_DATE" => self::ORDER_DATE,
            "ORDER_PCODE" => ["PCODE01"],
            "ORDER_PNAME" => ["PNAME01"],
            "ORDER_PRICE" => [(float)100],
            "ORDER_QTY" => [1],
            "ORDER_REF" => "MerchantOrderRef",
            "ORDER_VAT" => [(float)24],
            "PAY_METHOD" => "CCVISAMC",
            "PRICES_CURRENCY" => "RON",
            'ALIAS' => null,
            'BILL_BANK' => null,
            'BILL_BANKACCOUNT' => null,
            'BILL_CIISSUER' => null,
            'BILL_CISERIAL' => null,
            'BILL_CITYPE' => null,
            'BILL_CNP' => null,
            'BILL_COMPANY' => null,
            'BILL_FAX' => null,
            'BILL_FISCALCODE' => null,
            'BILL_REGNUMBER' => null,
            'BILL_STATE' => null,
            'BILL_ZIPCODE' => null,
            'CAMPAIGN_TYPE' => null,
            'CARD_PROGRAM_NAME' => null,
            'CC_NUMBER_RECIPIENT' => null,
            'DISCOUNT' => null,
            'LOYALTY_POINTS_AMOUNT' => null,
            'ORDER_MPLACE_MERCHANT' => [null],
            'ORDER_PGROUP' => [null],
            'ORDER_PINFO' => [null],
            'ORDER_PRICE_TYPE' => ["NET"],
            'ORDER_SHIPPING' => null,
            'ORDER_VER' => [null],
            'SELECTED_INSTALLMENTS_NUMBER' => null,
            'USE_LOYALTY_POINTS' => null,
        ];
    }

    public function testAuthorizeWithSuccess()
    {
        // Given
        $requestArray = $this->createRequestArray();

        $this->hashServiceMock->expects($this->once())
            ->method('makeRequestHash')
            ->with($requestArray)
            ->willReturn(self::HASH_STRING);

        $requestArray['ORDER_HASH'] = self::HASH_STRING;

        $this->httpClientMock->expects($this->once())
            ->method('post')
            ->with('https://secure.payu.ro' . AluV3::ALU_URL_PATH, $requestArray)
            ->willReturn(
                '<?xml version="1.0"?>
                        <EPAYMENT>
                          <REFNO>' . self::REF_NO . '</REFNO>
                          <ALIAS/>
                          <STATUS>' . self::STATUS . '</STATUS>
                          <RETURN_CODE>' . self::RETURN_CODE . '</RETURN_CODE>
                          <RETURN_MESSAGE>' . self::RETURN_MESSAGE . '</RETURN_MESSAGE>
                          <DATE>' . self::ORDER_DATE . '</DATE>
                          <ORDER_REF>' . self::ORDER_REF . '</ORDER_REF>
                          <AUTH_CODE/>
                          <RRN/>
                          <HASH>' . self::HASH_STRING  . '</HASH>
                        </EPAYMENT>'
            );

        $this->hashServiceMock->expects($this->once())
            ->method('validateResponseHash');

        $expectedResponse = new Response();
        $expectedResponse->setRefno(self::REF_NO);
        $expectedResponse->setAlias('');
        $expectedResponse->setStatus(self::STATUS);
        $expectedResponse->setReturnCode(self::RETURN_CODE);
        $expectedResponse->setReturnMessage(self::RETURN_MESSAGE);
        $expectedResponse->setDate(self::ORDER_DATE);
        $expectedResponse->setHash(self::HASH_STRING);
        $expectedResponse->setOrderRef(self::ORDER_REF);
        $expectedResponse->setAuthCode('');
        $expectedResponse->setRrn('');

        // When
        $actualResponse = $this->aluV3->authorize($this->createAluRequest(), null);

        // Then
        $this->assertInstanceOf(Response::class, $actualResponse);
        $this->assertEquals($expectedResponse, $actualResponse);
    }

    /**
     * @expectedException \PayU\Alu\Exceptions\ClientException
     */
    public function testAuthorizeWillThrowClientException()
    {
        // Given
        $requestArray = $this->createRequestArray();

        $this->hashServiceMock->expects($this->once())
            ->method('makeRequestHash')
            ->with($requestArray)
            ->willReturn(self::HASH_STRING);

        $requestArray['ORDER_HASH'] = self::HASH_STRING;

        $this->httpClientMock->expects($this->once())
            ->method('post')
            ->with('https://secure.payu.ro' . AluV3::ALU_URL_PATH, $requestArray)
            ->willThrowException(new \Exception());

        // When
        $this->aluV3->authorize($this->createAluRequest(), null);
    }
}

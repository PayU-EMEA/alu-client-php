<?php

namespace PayU\PaymentsApi\AluV3;

use PayU\Alu\Billing;
use PayU\Alu\HashService;
use PayU\Alu\HTTPClient;
use PayU\Alu\MerchantConfig;
use PayU\Alu\Order;
use PayU\Alu\Product;
use PayU\Alu\Request;

class AluV3Test extends \PHPUnit_Framework_TestCase
{
    /**
     * @var HashService
     */
    private $hashService;

    /**
     * @var HTTPClient
     */
    private $httpClient;

    /**
     * @var AluV3
     */
    private $aluV3;
    /**
     * @var Request
     */
    private $request;
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $mockHashService;
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $mockHttpClient;

    public function setUp()
    {
        $cfg = new MerchantConfig('CC5857', 'SECRET_KEY', 'RO');

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

        $this->request = new Request($cfg, $order, $billing);

        $this->mockHashService = $this->getMockBuilder('PayU\Alu\HashService')
            ->disableOriginalConstructor()
            ->getMock();

        $this->mockHttpClient = $this->getMockBuilder('PayU\Alu\HTTPClient')
            ->disableOriginalConstructor()
            ->getMock();

        $this->aluV3 = new AluV3(
            $this->mockHttpClient,
            $this->mockHashService
        );

    }

    public function testAuthorize()
    {
        $this->mockHashService->expects($this->once())
            ->method('makeRequestHash')
            ->will($this->returnValue('HASH'));

        $this->mockHttpClient->expects($this->once())
            ->method('post')
            ->will(
                $this->returnValue(
                    '<?xml version="1.0"?>
                <EPAYMENT>
                  <REFNO>12022985</REFNO>
                  <ALIAS/>
                  <STATUS>FAILED</STATUS>
                  <RETURN_CODE>ALREADY_AUTHORIZED</RETURN_CODE>
                  <RETURN_MESSAGE>The payment for your order is already authorized.</RETURN_MESSAGE>
                  <DATE>2014-09-22 11:08:23</DATE>
                  <ORDER_REF>90003</ORDER_REF>
                  <AUTH_CODE/>
                  <RRN/>
                  <WIRE_ACCOUNTS>
                    <ITEM>
                      <BANK_IDENTIFIER>BANCA AGRICOLA-RAIFFEISEN S.A.</BANK_IDENTIFIER>
                      <BANK_ACCOUNT>a12c8c196b11afb9beb8fe6221540a4f</BANK_ACCOUNT>
                      <ROUTING_NUMBER></ROUTING_NUMBER>
                      <IBAN_ACCOUNT></IBAN_ACCOUNT>
                      <BANK_SWIFT>BANK7</BANK_SWIFT>
                      <COUNTRY>Romania</COUNTRY>
                      <WIRE_RECIPIENT_NAME>GECAD ePayment International SA SRL</WIRE_RECIPIENT_NAME>
                    <WIRE_RECIPIENT_VAT_ID>RO16490162</WIRE_RECIPIENT_VAT_ID>
                    </ITEM>
                  </WIRE_ACCOUNTS>
                  <HASH>1ef929de57a17b747c8b8569371f611e</HASH>
                </EPAYMENT>'
                )
            );

        $this->mockHashService->expects($this->once())
            ->method('validateResponseHash');

        $this->assertInstanceOf(
            'PayU\Alu\Response',
            $this->aluV3->authorize($this->request, null)
        );
    }
/*
    public function testPayWithCustomUrl()
    {
        $this->mockHashService->expects($this->once())
            ->method('makeRequestHash')
            ->will($this->returnValue('3444cd767df689bcd5034ead29aa08a711111'));

        $this->mockRequest->expects($this->once())
            ->method('setOrderHash');

        $this->mockRequest->expects($this->once())
            ->method('getRequestParams')
            ->will(
                $this->returnValue(
                    array(
                        'HASH' => '3444cd767df689bcd5034ead29aa08a711111'
                    )
                )
            );

        $this->mockRequest->method('getPaymentsApiVersion')
            ->willReturn('v3');

        $this->mockHTTPClient->expects($this->once())
            ->method('post')
            ->will(
                $this->returnValue(
                    '<?xml version="1.0"?>
                <EPAYMENT>
                  <REFNO>12022985</REFNO>
                  <ALIAS/>
                  <STATUS>FAILED</STATUS>
                  <RETURN_CODE>ALREADY_AUTHORIZED</RETURN_CODE>
                  <RETURN_MESSAGE>The payment for your order is already authorized.</RETURN_MESSAGE>
                  <DATE>2014-09-22 11:08:23</DATE>
                  <ORDER_REF>90003</ORDER_REF>
                  <AUTH_CODE/>
                  <RRN/>
                  <HASH>1ef929de57a17b747c8b8569371f611e</HASH>
                </EPAYMENT>'
                )
            );

        $this->mockHashService->expects($this->once())
            ->method('validateResponseHash');

        $this->client->setCustomUrl('http://www.example.com');
        $this->assertInstanceOf(
            'PayU\Alu\Response',
            $this->client->pay($this->mockRequest, $this->mockHTTPClient, $this->mockHashService)
        );
    }
*/
}

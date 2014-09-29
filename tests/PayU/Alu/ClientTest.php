<?php

namespace PayU\Alu;

class ClientTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @var HashService | \PHPUnit_Framework_MockObject_MockObject
     */
    private $mockHashService;

    /**
     * @var HTTPClient | \PHPUnit_Framework_MockObject_MockObject
     */
    private $mockHTTPClient;

    /**
     * @var Request | \PHPUnit_Framework_MockObject_MockObject
     */
    private $mockRequest;

    public function setUp()
    {
        $cfg = new MerchantConfig('CC5857', 'SECRET_KEY', 'RO');
        $this->client = new Client($cfg);

        $this->mockRequest = $this->getMockBuilder('PayU\Alu\Request')
            ->disableOriginalConstructor()
            ->getMock();

        $this->mockHashService = $this->getMockBuilder('PayU\Alu\HashService')
            ->disableOriginalConstructor()
            ->getMock();

        $this->mockHTTPClient = $this->getMockBuilder('PayU\Alu\HTTPClient')
            ->disableOriginalConstructor()
            ->getMock();

    }

    public function handleThreeDSReturnResponseProvider()
    {
        return array(
            array(
                array ( 'REFNO' => '11682233',
                        'ALIAS' => '90b94dd8981e1df1a7574c043cc829cd',
                        'STATUS' => 'SUCCESS',
                        'RETURN_CODE' => 'AUTHORIZED',
                        'RETURN_MESSAGE' => 'Authorized.',
                        'DATE' => '2014-09-19 15:55:56',
                        'ORDER_REF' => '900001111',
                        'AUTH_CODE' => '459527',
                        'RRN' => '151709267375',
                        'HASH' => '3444cd767df689bcd5034ead29aa08a7',
                )
            )
        );
    }

    public function handleThreeDSReturnResponseProviderMissingHash()
    {
        return array(
            array(
                array ( 'REFNO' => '11682233',
                    'ALIAS' => '90b94dd8981e1df1a7574c043cc829cd',
                    'STATUS' => 'SUCCESS',
                    'RETURN_CODE' => 'AUTHORIZED',
                    'RETURN_MESSAGE' => 'Authorized.',
                    'DATE' => '2014-09-19 15:55:56',
                    'ORDER_REF' => '900001111',
                    'AUTH_CODE' => '459527',
                    'RRN' => '151709267375',
                )
            )
        );
    }

    public function handleThreeDSReturnResponseProviderHashMisMatch()
    {
        return array(
            array(
                array ( 'REFNO' => '11682233',
                    'ALIAS' => '90b94dd8981e1df1a7574c043cc829cd',
                    'STATUS' => 'SUCCESS',
                    'RETURN_CODE' => 'AUTHORIZED',
                    'RETURN_MESSAGE' => 'Authorized.',
                    'DATE' => '2014-09-19 15:55:56',
                    'ORDER_REF' => '900001111',
                    'AUTH_CODE' => '459527',
                    'RRN' => '151709267375',
                    'HASH' => '3444cd767df689bcd5034ead29aa08a711111',
                )
            )
        );
    }

    /**
     * @dataProvider handleThreeDSReturnResponseProvider
     */
    public function testHandleThreeDSReturnResponseSuccess($data)
    {
        $this->assertInstanceOf('PayU\Alu\Response', $this->client->handleThreeDSReturnResponse($data));
    }

    /**
     * @dataProvider handleThreeDSReturnResponseProviderMissingHash
     * @expectedException \PayU\Alu\Exceptions\ClientException
     */
    public function testHandleThreeDSReturnResponseMissingHash($data)
    {
        $this->client->handleThreeDSReturnResponse($data);
    }

    /**
     * @dataProvider handleThreeDSReturnResponseProviderHashMismatch
     * @expectedException \PayU\Alu\Exceptions\ClientException
     */
    public function testHandleThreeDSReturnResponseHashMismatch($data)
    {
        $this->client->handleThreeDSReturnResponse($data);
    }

    public function testPay()
    {
        $this->mockHashService->expects($this->once())
            ->method('makeRequestHash')
            ->will($this->returnValue('3444cd767df689bcd5034ead29aa08a711111'));

        $this->mockRequest->expects($this->once())
            ->method('setOrderHash');

        $this->mockRequest->expects($this->once())
            ->method('getRequestParams')
            ->will($this->returnValue(array(
                'HASH' => '3444cd767df689bcd5034ead29aa08a711111'
            )));

        $this->mockHTTPClient->expects($this->once())
            ->method('post')
            ->will($this->returnValue(
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
            ));

        $this->mockHashService->expects($this->once())
            ->method('validateResponseHash');

        $this->assertInstanceOf(
            'PayU\Alu\Response',
            $this->client->pay($this->mockRequest, $this->mockHTTPClient, $this->mockHashService)
        );
    }

    public function testPayWithCustomUrl()
    {
        $this->mockHashService->expects($this->once())
            ->method('makeRequestHash')
            ->will($this->returnValue('3444cd767df689bcd5034ead29aa08a711111'));

        $this->mockRequest->expects($this->once())
            ->method('setOrderHash');

        $this->mockRequest->expects($this->once())
            ->method('getRequestParams')
            ->will($this->returnValue(array(
                'HASH' => '3444cd767df689bcd5034ead29aa08a711111'
            )));

        $this->mockHTTPClient->expects($this->once())
            ->method('post')
            ->will($this->returnValue(
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
            ));

        $this->mockHashService->expects($this->once())
            ->method('validateResponseHash');

        $this->client->setCustomUrl('http://www.example.com');
        $this->assertInstanceOf(
            'PayU\Alu\Response',
            $this->client->pay($this->mockRequest, $this->mockHTTPClient, $this->mockHashService)
        );
    }
}
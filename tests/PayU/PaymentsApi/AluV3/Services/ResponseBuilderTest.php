<?php


namespace PayU\PaymentsApi\AluV3\Services;

use PayU\Alu\Response;
use PayU\Alu\ResponseWireAccount;
use PayU\PaymentsApi\AluV3\Entities\AuthorizationResponse;

class ResponseBuilderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ResponseBuilder
     */
    private $responseBuilder;

    /**
     * @var HashService |\PHPUnit_Framework_MockObject_MockObject
     */
    private $mockHashService;

    /**
     * @var AuthorizationResponse
     */
    private $authorizationResponse;

    public function setUp()
    {
        $this->mockHashService = $this->getMockBuilder('PayU\Alu\HashService')
            ->disableOriginalConstructor()
            ->getMock();

        $this->responseBuilder = new ResponseBuilder();
    }

    public function testBuildResponse()
    {
        $result = $this->createExpectedResponse();

        $authorizationResponse = new AuthorizationResponse(
            new \SimpleXMLElement(
                '<?xml version="1.0"?>
                <EPAYMENT>
                  <REFNO>12022985</REFNO>
                  <ALIAS>9592b7736c9e277fea8cc79c2e5b5a23</ALIAS>
                  <STATUS>FAILED</STATUS>
                  <RETURN_CODE>ALREADY_AUTHORIZED</RETURN_CODE>
                  <RETURN_MESSAGE>The payment for your order is already authorized.</RETURN_MESSAGE>
                  <DATE>2014-09-22 11:08:23</DATE>
                  <URL_3DS>https://secure.payu.ro/order/alu_return_3ds.php?request_id=2Xrl85eakbSBr3WtcbixYQ%3D%3D</URL_3DS>
                  <URL_REDIRECT>https://secure.payu.ro/order/pbl/redirect.php?hash=acf28e4ea9dafd77c4ca6de16f2e6cbd</URL_REDIRECT>
                  <ORDER_REF>90003</ORDER_REF>
                  <AUTH_CODE>465321</AUTH_CODE>
                  <RRN>1234</RRN>
                  <HASH>1ef929de57a17b747c8b8569371f611e</HASH>
                  <TOKEN_HASH>123456789</TOKEN_HASH>
                </EPAYMENT>'
            )
        );

        $this->mockHashService->expects($this->once())
            ->method('validateResponseHash');

        $this->assertEquals(
            $result,
            $this->responseBuilder->buildResponse(
                $authorizationResponse,
                $this->mockHashService
            )
        );
    }

    public function testBuildWireAccountResponse()
    {
        $result = $this->createWireAccountExpectedResponse();

        $authorizationResponse = new AuthorizationResponse(
            new \SimpleXMLElement(
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

        $this->assertEquals(
            $result,
            $this->responseBuilder->buildResponse(
                $authorizationResponse,
                $this->mockHashService
            )
        );
    }

    private function createWireAccountExpectedResponse()
    {
        $response = new Response();
        $response->setRefno(12022985);
        $response->setStatus('FAILED');
        $response->setReturnCode('ALREADY_AUTHORIZED');
        $response->setReturnMessage('The payment for your order is already authorized.');
        $response->setDate('2014-09-22 11:08:23');
        $response->setOrderRef(90003);

        $wireAccount = new ResponseWireAccount();

        $wireAccount->setBankIdentifier('BANCA AGRICOLA-RAIFFEISEN S.A.');
        $wireAccount->setBankAccount('a12c8c196b11afb9beb8fe6221540a4f');
        $wireAccount->setRoutingNumber('');
        $wireAccount->setIbanAccount('');
        $wireAccount->setBankSwift('BANK7');
        $wireAccount->setCountry('Romania');
        $wireAccount->setWireRecipientName('GECAD ePayment International SA SRL');
        $wireAccount->setWireRecipientVatId('RO16490162');

        $wireAccountArr[0] = $wireAccount;
        $response->setWireAccounts($wireAccountArr);

        $response->setHash('1ef929de57a17b747c8b8569371f611e');

        return $response;
    }

    private function createExpectedResponse()
    {
        $response = new Response();
        $response->setRefno(12022985);
        $response->setStatus('FAILED');
        $response->setAlias('9592b7736c9e277fea8cc79c2e5b5a23');
        $response->setReturnCode('ALREADY_AUTHORIZED');
        $response->setReturnMessage('The payment for your order is already authorized.');
        $response->setDate('2014-09-22 11:08:23');
        $response->setOrderRef(90003);

        $response->setThreeDsUrl(
            'https://secure.payu.ro/order/alu_return_3ds.php?request_id=2Xrl85eakbSBr3WtcbixYQ%3D%3D'
        );
        $response->setUrlRedirect(
            'https://secure.payu.ro/order/pbl/redirect.php?hash=acf28e4ea9dafd77c4ca6de16f2e6cbd'
        );
        $response->setAuthCode(465321);
        $response->setRrn('1234');
        $response->setTokenHash('123456789');
        $response->setHash('1ef929de57a17b747c8b8569371f611e');

        return $response;
    }
}

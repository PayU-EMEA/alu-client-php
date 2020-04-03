<?php


namespace PayU\PaymentsApi\AluV3\Services;

use PayU\Alu\Response;
use PayU\Alu\ResponseWireAccount;
use PayU\PaymentsApi\AluV3\Entities\AuthorizationResponse;

class ResponseBuilderTest extends \PHPUnit_Framework_TestCase
{
    const HASH_STRING = "HASH";
    const REF_NO = "12022985";
    const STATUS = "FAILED";
    const RETURN_CODE = 'ALREADY_AUTHORIZED';
    const RETURN_MESSAGE = 'The payment for your order is already authorized.';
    const ORDER_DATE = '2014-09-22 11:08:23';
    const ORDER_REF = "90003";

    /**
     * @var ResponseBuilder
     */
    private $responseBuilder;

    /**
     * @var HashService |\PHPUnit_Framework_MockObject_MockObject
     */
    private $mockHashService;


    public function setUp()
    {
        $this->mockHashService = $this->getMockBuilder('PayU\Alu\HashService')
            ->disableOriginalConstructor()
            ->getMock();

        $this->responseBuilder = new ResponseBuilder();
    }

    private function createWireAccountExpectedResponse()
    {
        $response = new Response();
        $response->setRefno(self::REF_NO);
        $response->setStatus(self::STATUS);
        $response->setReturnCode(self::RETURN_CODE);
        $response->setReturnMessage(self::RETURN_MESSAGE);
        $response->setDate(self::ORDER_DATE);
        $response->setOrderRef(self::ORDER_REF);

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

        $response->setHash(self::HASH_STRING);

        return $response;
    }

    private function createExpectedResponse()
    {
        $response = new Response();
        $response->setRefno(self::REF_NO);
        $response->setStatus(self::STATUS);
        $response->setAlias('9592b7736c9e277fea8cc79c2e5b5a23');
        $response->setReturnCode(self::RETURN_CODE);
        $response->setReturnMessage(self::RETURN_MESSAGE);
        $response->setDate(self::ORDER_DATE);
        $response->setOrderRef(self::ORDER_REF);

        $response->setThreeDsUrl(
            'https://secure.payu.ro/order/alu_return_3ds.php?request_id=2Xrl85eakbSBr3WtcbixYQ%3D%3D'
        );
        $response->setUrlRedirect(
            'https://secure.payu.ro/order/pbl/redirect.php?hash=acf28e4ea9dafd77c4ca6de16f2e6cbd'
        );
        $response->setAuthCode(465321);
        $response->setRrn('1234');
        $response->setTokenHash('123456789');
        $response->setHash(self::HASH_STRING);

        return $response;
    }

    public function testBuildResponse()
    {
        //Given
        $result = $this->createExpectedResponse();

        $authorizationResponse = new AuthorizationResponse(
            new \SimpleXMLElement(
                '<?xml version="1.0"?>
                <EPAYMENT>
                  <REFNO>' . self::REF_NO . '</REFNO>
                  <ALIAS>9592b7736c9e277fea8cc79c2e5b5a23</ALIAS>
                  <STATUS>' . self::STATUS . '</STATUS>
                  <RETURN_CODE>' . self::RETURN_CODE . '</RETURN_CODE>
                  <RETURN_MESSAGE>' . self::RETURN_MESSAGE . '</RETURN_MESSAGE>
                  <DATE>' . self::ORDER_DATE . '</DATE>
                  <URL_3DS>https://secure.payu.ro/order/alu_return_3ds.php?request_id=2Xrl85eakbSBr3WtcbixYQ%3D%3D</URL_3DS>
                  <URL_REDIRECT>https://secure.payu.ro/order/pbl/redirect.php?hash=acf28e4ea9dafd77c4ca6de16f2e6cbd</URL_REDIRECT>
                  <ORDER_REF>' . self::ORDER_REF . '</ORDER_REF>
                  <AUTH_CODE>465321</AUTH_CODE>
                  <RRN>1234</RRN>
                  <HASH>' . self::HASH_STRING  . '</HASH>
                  <TOKEN_HASH>123456789</TOKEN_HASH>
                </EPAYMENT>'
            )
        );

        // When
        $this->mockHashService->expects($this->once())
            ->method('validateResponseHash')
            ->with($result);

        // Then
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
        // Given
        $result = $this->createWireAccountExpectedResponse();

        $authorizationResponse = new AuthorizationResponse(
            new \SimpleXMLElement(
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
                  <HASH>' . self::HASH_STRING  . '</HASH>
                </EPAYMENT>'
            )
        );

        // When
        $this->mockHashService->expects($this->once())
            ->method('validateResponseHash')
            ->with($result);

        // Then
        $this->assertEquals(
            $result,
            $this->responseBuilder->buildResponse(
                $authorizationResponse,
                $this->mockHashService
            )
        );
    }
}

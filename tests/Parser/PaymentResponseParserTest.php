<?php
namespace PayU\Alu\Test;

use PayU\Alu\Component\Response;
use PayU\Alu\HashService;
use PayU\Alu\Parser\PaymentResponseParser;
use PHPUnit\Framework\TestCase;

class PaymentResponseParserTest extends TestCase
{
    /** @var \PHPUnit_Framework_MockObject_MockObject | HashService */
    private $hashServiceMock;

    public function setUp()
    {
        $this->hashServiceMock = $this->getMockBuilder(HashService::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    public function testParse()
    {
        $this->hashServiceMock->expects($this->once())
            ->method("validateResponse")
            ->willReturn(true);

        $parser = new PaymentResponseParser($this->hashServiceMock);

        $response = $parser->parse(file_get_contents(__DIR__ . '/../given/FailedPaymentResponseWithWiredAccount.xml'));
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals("12022985", $response->getRefno());
        $this->assertEquals("FAILED", $response->getStatus());
        $this->assertEquals("The payment for your order is already authorized.", $response->getReturnMessage());
        $this->assertEquals("2014-09-22 11:08:23", $response->getDate());
        $this->assertEquals("90003", $response->getOrderRef());
        $this->assertEquals("BANCA AGRICOLA-RAIFFEISEN S.A.", $response->getWireAccounts()[0]->getBankIdentifier());
        $this->assertEquals("a12c8c196b11afb9beb8fe6221540a4f", $response->getWireAccounts()[0]->getBankAccount());
        $this->assertEquals("Romania", $response->getWireAccounts()[0]->getCountry());
        $this->assertEquals("GECAD ePayment International SA SRL", $response->getWireAccounts()[0]->getWireRecipientName());
        $this->assertEquals("RO16490162", $response->getWireAccounts()[0]->getWireRecipientVatId());
        $this->assertEquals("1ef929de57a17b747c8b8569371f611e", $response->getHash());
    }

    /**
     * @expectedException \Payu\Alu\Exception\ClientException
     * @expectedExceptionMessage String could not be parsed as XML
     */
    public function testParseBadInput()
    {
        $parser = new PaymentResponseParser($this->hashServiceMock);
        $parser->parse("BAD_INPUT");
    }
}
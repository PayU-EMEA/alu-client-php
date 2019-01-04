<?php
namespace PayU\Alu\Test;

use PayU\Alu\Component\Response;
use PayU\Alu\HashService;
use PayU\Alu\Parser\ThreeDSecureResponseParser;
use PHPUnit\Framework\TestCase;

class ThreeDSecureResponseParserTest extends TestCase
{
    /** @var \PHPUnit_Framework_MockObject_MockObject | HashService */
    private $hashServiceMock;

    public function setUp()
    {
        $this->hashServiceMock = $this->getMockBuilder(HashService::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    public function handleThreeDSReturnResponseProvider()
    {
        return array(
            array(
                array (
                    'REFNO' => '11682233',
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
                    'HASH' => 'e47fe8af799f5df0b867c2d47cc2a8c4'
                )
            ),
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


    /**
     * @dataProvider handleThreeDSReturnResponseProvider
     * @param $data
     */
    public function testPArseSuccess(array $data) {
        $this->hashServiceMock->expects($this->any())->method("validateResponse")->willReturn(true);
        $parser = new ThreeDSecureResponseParser($this->hashServiceMock);
        $response = $parser->parse($data);
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals($data['REFNO'], $response->getRefno());
        $this->assertEquals($data['ALIAS'], $response->getAlias());
        $this->assertEquals($data['STATUS'], $response->getStatus());
        $this->assertEquals($data['RETURN_CODE'], $response->getReturnCode());
        $this->assertEquals($data['RETURN_MESSAGE'], $response->getReturnMessage());
        $this->assertEquals($data['DATE'], $response->getDate());
        $this->assertEquals($data['ORDER_REF'], $response->getOrderRef());
        $this->assertEquals($data['AUTH_CODE'], $response->getAuthCode());
        $this->assertEquals($data['RRN'], $response->getRrn());
        $this->assertEquals($data['HASH'], $response->getHash());
        $this->assertEquals(count($data['WIRE_ACCOUNTS']), count($response->getWireAccounts()));
        if(!empty($response->getWireAccounts())) {
            foreach ($data['WIRE_ACCOUNTS'] as $index => $account) {
                $this->assertEquals($account['BANK_IDENTIFIER'], $response->getWireAccounts()[$index]->getBankIdentifier());
                $this->assertEquals($account['BANK_ACCOUNT'], $response->getWireAccounts()[$index]->getBankAccount());
                $this->assertEquals($account['ROUTING_NUMBER'], $response->getWireAccounts()[$index]->getRoutingNumber());
                $this->assertEquals($account['IBAN_ACCOUNT'], $response->getWireAccounts()[$index]->getIbanAccount());
                $this->assertEquals($account['BANK_SWIFT'], $response->getWireAccounts()[$index]->getBankSwift());
                $this->assertEquals($account['COUNTRY'], $response->getWireAccounts()[$index]->getCountry());
                $this->assertEquals($account['WIRE_RECIPIENT_NAME'], $response->getWireAccounts()[$index]->getWireRecipientName());
                $this->assertEquals($account['WIRE_RECIPIENT_VAT_ID'], $response->getWireAccounts()[$index]->getWireRecipientVatId());
            }
        }
    }

    /**
     * @dataProvider handleThreeDSReturnResponseProviderMissingHash
     * @expectedException \Payu\Alu\Exception\ClientException
     * @expectedExceptionMessage Missing HASH
     * @param $data
     */
    public function testParseHashMismatchError(array $data)
    {
        $parser = new ThreeDSecureResponseParser($this->hashServiceMock);
        $parser->parse($data);
    }
}
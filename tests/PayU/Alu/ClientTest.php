<?php


namespace PayU\Alu;

class ClientTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Client
     */
    private $client;

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
    }

    public function handleThreeDSReturnResponseProvider()
    {
        return array(
            array(
                array(
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
                    'HASH' => 'e47fe8af799f5df0b867c2d47cc2a8c4'
                )
            ),
        );
    }

    public function handleThreeDSReturnResponseProviderMissingHash()
    {
        return array(
            array(
                array(
                    'REFNO' => '11682233',
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
                array(
                    'REFNO' => '11682233',
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
}

<?php


namespace PayU\PaymentsApi\PaymentsV4\Services;

use PayU\PaymentsApi\PaymentsV4\Entities\AuthorizationResponse;
use ReflectionClass;

class ResponseParserTest extends \PHPUnit_Framework_TestCase
{
    const CODE = '200';
    const STATUS = 'SUCCESS';
    const MESSAGE = 'A text message with details about processing result or with some message error';
    const PAYU_PAYMENT_REFERENCE = '896782';
    const AMOUNT = '10.5';

    /** @var  AluResponseMapper |\PHPUnit_Framework_MockObject_MockObject */
    private $mockAluResponseMapper;

    /** @var ResponseParser */
    private $responseParser;

    public function setUp()
    {
        $this->mockAluResponseMapper = $this->getMockBuilder('AluResponseMapper')
            ->disableOriginalConstructor()
            ->setMethods(array('processResponse'))
            ->getMock();

        $this->responseParser = new ResponseParser();
    }

    private function setAluResponseParserMock()
    {
        $reflection = new ReflectionClass($this->responseParser);
        $reflectionResponseMapper = $reflection->getProperty('aluResponseMapper');
        $reflectionResponseMapper->setAccessible(true);
        $reflectionResponseMapper->setValue($this->responseParser, $this->mockAluResponseMapper);
    }

    private function createJsonResponse()
    {
        return '{
            "code": ' . self::CODE . ',
            "status": "' . self::STATUS . '",
            "message": "' . self::MESSAGE . '",
            "payuPaymentReference": ' . self::PAYU_PAYMENT_REFERENCE . ',
            "merchantPaymentReference": "34dfsd-sdgds",
            "amount": ' . self::AMOUNT . ',
            "currency": "EUR",
            "paymentResult": {
            "payuResponseCode": "GWERROR_41",
            "authCode": 324534,
            "rrn": 43534654,
            "installmentsNumber": 2,
            "cardProgramName": "AXESS",
            "bankResponseDetails": {
            "terminalId": "A123456789",
            "response": {
            "code": 0,
            "message": "Payment authorized",
            "status": "Authorized"
            },
            "hostRefNum": 80001719289129,
            "merchantId": "A891911",
            "shortName": "UGBI",
            "txRefNo": "O176721881",
            "oid": "A9891919911",
            "transId": "example"
            },
            "cardDetails": {
            "pan": "411111******1111",
            "expiryYear": 2026,
            "expiryMonth": 7
            },
            "3dsDetails": {
            "mdStatus": "Y",
            "errorMessage": "Some error message",
            "txStatus": "Authorized",
            "xid": "78199a88871e0f00",
            "eci": 0,
            "cavv": 123
            },
            "type": "redirect",
            "url": "http://acquirer_url/test"
            }
            }';
    }

    private function createWireAccountJsonResponse()
    {
        return '{
            "code": ' . self::CODE . ',
            "status": "' . self::STATUS . '",
            "message": "' . self::MESSAGE . '",
            "payuPaymentReference": ' . self::PAYU_PAYMENT_REFERENCE . ',
            "merchantPaymentReference": "34dfsd-sdgds",
            "amount": ' . self::AMOUNT . ',
            "currency": "EUR",
            "paymentResult": {
            "payuResponseCode": "GWERROR_41",
            "authCode": 324534,
            "rrn": 43534654,
            "installmentsNumber": 2,
            "cardProgramName": "AXESS",
            "bankResponseDetails": {
            "terminalId": "A123456789",
            "response": {
            "code": 0,
            "message": "Payment authorized",
            "status": "Authorized"
            },
            "hostRefNum": 80001719289129,
            "merchantId": "A891911",
            "shortName": "UGBI",
            "txRefNo": "O176721881",
            "oid": "A9891919911",
            "transId": "example"
            },
            "cardDetails": {
            "pan": "411111******1111",
            "expiryYear": 2026,
            "expiryMonth": 7
            },
            "3dsDetails": {
            "mdStatus": "Y",
            "errorMessage": "Some error message",
            "txStatus": "Authorized",
            "xid": "78199a88871e0f00",
            "eci": 0,
            "cavv": 123
            },
            "type": "redirect",
            "url": "http://acquirer_url/test",
            "wireAccounts": [{
            "bankIdentifier": "BANN",
            "bankAccount": 678819991,
            "routingNumber": 263181368,
            "ibanAccount": "RO49AAAA1B31007593840000",
            "bankSwift": "UGBIROBU",
            "country": "RO",
            "recipientName": "My Company",
            "recipientVatId": 1234567890
            }
            ]
            }
            }';
    }

    private function createExpectedResponseArray()
    {
        return [
            'CODE' => self::CODE,
            'STATUS' => self::STATUS,
            'RETURN_MESSAGE' => self::MESSAGE,
            'REFNO' => self::PAYU_PAYMENT_REFERENCE,
            'ORDER_REF' => '34dfsd-sdgds',
            'AMOUNT' => self::AMOUNT,
            'CURRENCY' => 'EUR',
            'RETURN_CODE' => 'GWERROR_41',
            'AUTH_CODE' => 324534,
            'RRN' => 43534654,
            'INSTALLMENTS_NO' => 2,
            'CARD_PROGRAM_NAME' => 'AXESS',
            'CLIENTID' => 'A123456789',
            'PROCRETURNCODE' => 0,
            'ERRORMESSAGE' => 'Payment authorized',
            'RESPONSE' => 'Authorized',
            'HOSTREFNUM' => 80001719289129,
            'BANK_MERCHANT_ID' => 'A891911',
            'TERMINAL_BANK' => 'UGBI',
            'TX_REFNO' => 'O176721881',
            'OID' => 'A9891919911',
            'TRANSID' => 'example',
            'PAN' => '411111******1111',
            'EXPYEAR' => 2026,
            'EXPMONTH' => 7,
            'MDSTATUS' => 'Y',
            'MDERRORMSG' => 'Some error message',
            'TXSTATUS' => 'Authorized',
            'XID' => '78199a88871e0f00',
            'ECI' => 0,
            'CAVV' => 123,
            'URL_REDIRECT' => 'http://acquirer_url/test',
            'TYPE' => 'redirect'
        ];
    }

    public function createJsonResponseWithoutStatus()
    {
        return [
            [
                '{
            "code": 200,
            "message": "A text message with details about processing result or with some message error",
            "payuPaymentReference": 896782,
            "merchantPaymentReference": "34dfsd-sdgds",
            "amount": 10.5,
            "currency": "EUR"
            }'
            ],
            [
                '{
            "code": 200,
            "status": null,
            "message": "A text message with details about processing result or with some message error",
            "payuPaymentReference": 896782,
            "merchantPaymentReference": "34dfsd-sdgds",
            "amount": 10.5,
            "currency": "EUR"
            }'
            ]
        ];
    }

    public function createRequestFailedResponse()
    {
        return [
            [
                '{
                    "code": 400,
                    "status": "INVALID_PAYMENT_INFO",
                    "message": "A text message with details about processing result or with some message error"
                }',
                [
                    'CODE' => 400,
                    'STATUS' => 'INVALID_PAYMENT_INFO',
                    'RETURN_MESSAGE' => self::MESSAGE,
                ]
            ],
            [
                '{
                    "code": 429,
                    "message": "API calls limit reached. (0/0)",
                    "status": "LIMIT_CALLS_EXCEEDED"
                }',
                [
                    'CODE' => 429,
                    'STATUS' => 'LIMIT_CALLS_EXCEEDED',
                    'RETURN_MESSAGE' => 'API calls limit reached. (0/0)',
                ]
            ],
            [
                '{
                    "code": 500,
                    "message": "' . self::MESSAGE . '",
                    "status": "INTERNAL_ERROR",
                    "payuPaymentReference": 896782
                }',
                [
                    'CODE' => 500,
                    'STATUS' => 'INTERNAL_ERROR',
                    'RETURN_MESSAGE' => self::MESSAGE,
                    'REFNO' => 896782
                ]
            ],

        ];
    }

    public function testParseResponse()
    {
        // Given
        $jsonResponse = $this->createJsonResponse();
        $expectedResponse = $this->createExpectedResponseArray();

        $decodedResponse = json_decode($jsonResponse, true);

        // When
        $this->setAluResponseParserMock();

        $this->mockAluResponseMapper->expects($this->once())
            ->method('processResponse')
            ->with($decodedResponse)
            ->willReturn($expectedResponse);

        $authorizationResponse = new AuthorizationResponse($expectedResponse);

        // Then
        $this->assertEquals($authorizationResponse, $this->responseParser->parseJsonResponse($jsonResponse));
    }

    public function testBuildWireAccountResponse()
    {
        // Given
        $jsonResponse = $this->createWireAccountJsonResponse();
        $expectedResponse = $this->createExpectedResponseArray();

        $expectedResponse['WIRE_ACCOUNTS'] = [
            0 => [
                'BANK_IDENTIFIER' => 'BANN',
                'BANK_ACCOUNT' => 678819991,
                'ROUTING_NUMBER' => 263181368,
                'IBAN_ACCOUNT' => 'RO49AAAA1B31007593840000',
                'BANK_SWIFT' => 'UGBIROBU',
                'COUNTRY' => 'RO',
                'WIRE_RECIPIENT_NAME' => 'My Company',
                'WIRE_RECIPIENT_VAT_ID' => 1234567890,
            ]
        ];

        $decodedResponse = json_decode($jsonResponse, true);

        // When
        $this->setAluResponseParserMock();

        $this->mockAluResponseMapper->expects($this->once())
            ->method('processResponse')
            ->with($decodedResponse)
            ->willReturn($expectedResponse);

        $authorizationResponse = new AuthorizationResponse($expectedResponse);

        // Then
        $this->assertEquals($authorizationResponse, $this->responseParser->parseJsonResponse($jsonResponse));
    }

    /**
     * @dataProvider createRequestFailedResponse
     */
    public function testParseRequestFailedResponse($jsonResponse, $expectedResponse)
    {
        $decodedResponse = json_decode($jsonResponse, true);

        // When
        $this->setAluResponseParserMock();

        $this->mockAluResponseMapper->expects($this->once())
            ->method('processResponse')
            ->with($decodedResponse)
            ->willReturn($expectedResponse);

        $authorizationResponse = new AuthorizationResponse($expectedResponse);

        // Then
        $this->assertEquals($authorizationResponse, $this->responseParser->parseJsonResponse($jsonResponse));
    }

    /**
     * @expectedException PayU\PaymentsApi\PaymentsV4\Exceptions\AuthorizationResponseException
     */
    public function testParseJsonResponseWillThrowAuthorizationResponseException()
    {
        // Given
        $jsonResponse = $this->createJsonResponse();
        $jsonResponse = substr($jsonResponse, 0, -1);

        // When
        $this->responseParser->parseJsonResponse($jsonResponse);
    }

    /**
     * @dataProvider createJsonResponseWithoutStatus
     * @expectedException PayU\PaymentsApi\PaymentsV4\Exceptions\ResponseBuilderException
     */
    public function testParseJsonResponseWillThrowRequestBuilderException($jsonResponse)
    {
        // When
        $this->responseParser->parseJsonResponse($jsonResponse);
    }
}

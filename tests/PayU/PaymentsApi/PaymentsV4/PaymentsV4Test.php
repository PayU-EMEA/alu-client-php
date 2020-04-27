<?php

namespace PayU\PaymentsApi\PaymentsV4;

use PayU\Alu\Billing;
use PayU\Alu\Card;
use PayU\Alu\Delivery;
use PayU\Alu\MerchantConfig;
use PayU\Alu\Order;
use PayU\Alu\Product;
use PayU\Alu\Request;
use PayU\Alu\Response;
use PayU\Alu\User;
use PayU\PaymentsApi\PaymentsV4\Entities\AuthorizationResponse;
use PayU\PaymentsApi\PaymentsV4\Exceptions\AuthorizationResponseException;
use PayU\PaymentsApi\PaymentsV4\Exceptions\ConnectionException;
use PayU\PaymentsApi\PaymentsV4\Exceptions\ResponseBuilderException;
use PayU\PaymentsApi\PaymentsV4\Services\HTTPClient;
use PayU\PaymentsApi\PaymentsV4\Services\RequestBuilder;
use PayU\PaymentsApi\PaymentsV4\Services\ResponseBuilder;
use PayU\PaymentsApi\PaymentsV4\Services\ResponseParser;
use ReflectionClass;
use PHPUnit\Framework\TestCase;

class PaymentsV4Test extends TestCase
{
    const PAYU_PAYMENT_REFERENCE = '3244554';
    const STATUS = 'SUCCESS';
    const RETURN_CODE = 'AUTHORIZED';
    const RETURN_MESSAGE = 'Authorized.';
    const ORDER_DATE = '2020-04-24T14:12:29+00:00';
    const MERCHANT_PAYMENT_REFERENCE = '90005';

    const AUTHORIZATION_NODE = 'authorization';
    const FX_NODE = 'fx';
    const AIRLINE_INFO_NODE = 'airlineInfo';
    const STORED_CREDENTIALS_NODE = 'storedCredentials';


    /** @var PaymentsV4 */
    private $paymentsV4;

    /** @var HTTPClient |\PHPUnit_Framework_MockObject_MockObject */
    private $mockHttpClient;

    /** @var ResponseBuilder |\PHPUnit_Framework_MockObject_MockObject */
    private $mockResponseBuilder;

    /** @var ResponseParser |\PHPUnit_Framework_MockObject_MockObject */
    private $mockResponseParser;

    /** @var RequestBuilder |\PHPUnit_Framework_MockObject_MockObject */
    private $mockRequestBuilder;

    public function setUp()
    {
        $this->mockRequestBuilder = $this->getMockBuilder(RequestBuilder::class)
            ->disableOriginalConstructor()
            ->setMethods(array('buildAuthorizationRequest'))
            ->getMock();

        $this->mockResponseParser = $this->getMockBuilder(ResponseParser::class)
            ->disableOriginalConstructor()
            ->setMethods(array('parseJsonResponse'))
            ->getMock();

        $this->mockResponseBuilder = $this->getMockBuilder(ResponseBuilder::class)
            ->disableOriginalConstructor()
            ->setMethods(array('buildResponse'))
            ->getMock();

        $this->mockHttpClient = $this->getMockBuilder(HTTPClient::class)
            ->disableOriginalConstructor()
            ->setMethods(array('post'))
            ->getMock();

        $this->paymentsV4 = new PaymentsV4();
    }

    private function addMockObjectsToPaymentsV4()
    {
        $reflection = new ReflectionClass($this->paymentsV4);

        $reflectionHttpClient = $reflection->getProperty('httpClient');
        $reflectionHttpClient->setAccessible(true);
        $reflectionHttpClient->setValue($this->paymentsV4, $this->mockHttpClient);

        $reflectionRequestBuilder = $reflection->getProperty('requestBuilder');
        $reflectionRequestBuilder->setAccessible(true);
        $reflectionRequestBuilder->setValue($this->paymentsV4, $this->mockRequestBuilder);

        $reflectionResponseParser = $reflection->getProperty('responseParser');
        $reflectionResponseParser->setAccessible(true);
        $reflectionResponseParser->setValue($this->paymentsV4, $this->mockResponseParser);

        $reflectionResponseBuilder = $reflection->getProperty('responseBuilder');
        $reflectionResponseBuilder->setAccessible(true);
        $reflectionResponseBuilder->setValue($this->paymentsV4, $this->mockResponseBuilder);
    }

    /**
     * @return Request
     */
    private function createAluRequest()
    {
        $cfg = new MerchantConfig('PAYU_2', 'SECRET_KEY', 'RO');

        $user = new User('127.0.0.1');

        $order = new Order();

        $order->withBackRef('http://path/to/your/returnUrlScript')
            ->withOrderRef(self::MERCHANT_PAYMENT_REFERENCE)
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

        $product = new Product();
        $product->withCode('PCODE02')
            ->withName('PNAME02')
            ->withPrice(200.0)
            ->withVAT(24.0)
            ->withQuantity(1);

        $order->addProduct($product);

        $billing = new Billing();

        $billing->withAddressLine1('Address1')
            ->withAddressLine2('Address2')
            ->withCity('City')
            ->withCountryCode('RO')
            ->withEmail('john.doe@mail.com')
            ->withFirstName('FirstName')
            ->withLastName('LastName')
            ->withPhoneNumber('40123456789')
            ->withIdentityCardNumber('111222')
            ->withIdentityCardType(null);

        $delivery = new Delivery();
        $delivery->withAddressLine1('Address1')
            ->withAddressLine2('Address2')
            ->withCity('City')
            ->withCountryCode('RO')
            ->withEmail('john.doe@mail.com')
            ->withFirstName('FirstName')
            ->withLastName('LastName')
            ->withPhoneNumber('40123456789');


        $card = new Card('4111111111111111', '01', '2026', 123, 'Card Owner Name');

        $request = new Request($cfg, $order, $billing, $delivery, $user, PaymentsV4::API_VERSION_V4);

        $request->setCard($card);

        return $request;
    }

    private function createJsonRequest()
    {
        $requestArray = [
            'merchantPaymentReference' => self::MERCHANT_PAYMENT_REFERENCE,
            'currency' => 'RON',
            'returnUrl' => 'http://path/to/your/returnUrlScript',
            self::AUTHORIZATION_NODE => [
                'paymentMethod' => 'CCVISAMC',
                'cardDetails' => [
                    'number' => '4111111111111111',
                    'expiryMonth' => '01',
                    'expiryYear' => '2026',
                    'cvv' => 123,
                    'owner' => 'Card Owner Name',
                ],
                'merchantToken' => null,
                //'applePayToken' => json_encode($this->applePayToken),
                'usePaymentPage' => null,
                'installmentsNumber' => null,
                'useLoyaltyPoints' => null,
                'loyaltyPointsAmount' => null,
                'campaignType' => null,
                self::FX_NODE => null
            ],
            'client' => [
                'billing' => [
                    'firstName' => 'FirstName',
                    'lastName' => 'LastName',
                    'email' => 'john.doe@mail.com',
                    'phone' => '40123456789',
                    'city' => 'City',
                    'countryCode' => 'RO',
                    'state' => null,
                    'companyName' => null,
                    'taxId' => null,
                    'addressLine1' => 'Address1',
                    'addressLine2' => 'Address2',
                    'zipCode' => null,
                    'identityDocument' => [
                        'number' => '111222',
                        'type' => null
                    ]
                ],
                'delivery' => [
                    'firstName' => 'FirstName',
                    'lastName' => 'LastName',
                    'phone' => '40123456789',
                    'addressLine1' => 'Address1',
                    'addressLine2' => 'Address2',
                    'zipCode' => null,
                    'city' => 'City',
                    'state' => null,
                    'countryCode' => 'RO',
                    'email' => 'john.doe@mail.com'
                ],
                'ip' => '127.0.0.1',
                'time' => '',
                'communicationLanguage' => null
            ],
            'merchant' => null,
            'products' => [
                0 => [
                    'name' => 'PNAME01',
                    'sku' => 'PCODE01',
                    'additionalDetails' => null,
                    'unitPrice' => 100.0,
                    'quantity' => 1,
                    'vat' => 24.0
                ],
                1 => [
                    'name' => 'PNAME02',
                    'sku' => 'PCODE02',
                    'additionalDetails' => null,
                    'unitPrice' => 200.0,
                    'quantity' => 1,
                    'vat' => 24.0
                ]
            ],
            self::AIRLINE_INFO_NODE => null,
            'threeDSecure' => null,
            self::STORED_CREDENTIALS_NODE => null
        ];

        return json_encode($requestArray);
    }

    private function createJsonResponse()
    {
        return '{
        "payuPaymentReference":"' . self::PAYU_PAYMENT_REFERENCE . '",
        "status":"' . self::STATUS . '",
        "paymentResult":{
        "payuResponseCode":"AUTHORIZED",
        "authCode":"449912",
        "rrn":"269880175488",
        "antifraud":{"skipAntiFraudCheck":"YES"}},
        "message":"Authorized.",
        "merchantPaymentReference":"' . self::MERCHANT_PAYMENT_REFERENCE . '",
        "code":200,
        "amount":"300"
        }';
    }

    private function createArrayResponse()
    {
        return [
            'CODE' => 200,
            'STATUS' => self::STATUS,
            'RETURN_MESSAGE' => 'Authorized.',
            'REFNO' => self::PAYU_PAYMENT_REFERENCE,
            'ORDER_REF' => self::MERCHANT_PAYMENT_REFERENCE,
            'AMOUNT' => '300',
            'RETURN_CODE' => 'AUTHORIZED',
            'AUTH_CODE' => 449912,
            'RRN' => 269880175488
        ];
    }

    private function createAluResponse()
    {
        $response = new Response();

        $response->setCode(200);
        $response->setRefno(self::PAYU_PAYMENT_REFERENCE);
        $response->setStatus(self::STATUS);
        $response->setReturnCode('AUTHORIZED');
        $response->setReturnMessage('Authorized.');
        $response->setOrderRef(self::MERCHANT_PAYMENT_REFERENCE);
        $response->setAmount('300');
        $response->setAuthCode(449912);
        $response->setRrn(269880175488);

        return $response;
    }

    public function testAuthorize()
    {
        // Given
        $aluRequest = $this->createAluRequest();
        $jsonRequest = $this->createJsonRequest();
        $jsonResponse = $this->createJsonResponse();
        $arrayResponse = $this->createArrayResponse();
        $aluResponse = $this->createAluResponse();

        // When
        $this->addMockObjectsToPaymentsV4();

        $this->mockRequestBuilder->expects($this->once())
            ->method('buildAuthorizationRequest')
            ->with($aluRequest)
            ->willReturn($jsonRequest);

        $this->mockHttpClient->expects($this->once())
            ->method('post')
            ->with(
                'http://ro.payu.local/api/v4/payments/authorize',
                $aluRequest->getMerchantConfig(),
                self::ORDER_DATE,
                $jsonRequest
            )
            ->willReturn($jsonResponse);

        $authorizationResponse = new AuthorizationResponse($arrayResponse);

        $this->mockResponseParser->expects($this->once())
            ->method('parseJsonResponse')
            ->with($jsonResponse)
            ->willReturn($authorizationResponse);

        $this->mockResponseBuilder->expects($this->once())
            ->method('buildResponse')
            ->with($authorizationResponse)
            ->willReturn($aluResponse);

        $actualResponse = $this->paymentsV4->authorize($aluRequest);

        // Then
        $this->assertInstanceOf(Response::class, $actualResponse);
        $this->assertEquals($aluResponse, $actualResponse);
    }

    /**
     * @expectedException \PayU\PaymentsApi\Exceptions\AuthorizationException
     */
    public function testHttpClientThrowsConnectionException()
    {
        // Given
        $aluRequest = $this->createAluRequest();
        $jsonRequest = $this->createJsonRequest();

        // When
        $this->addMockObjectsToPaymentsV4();

        $this->mockRequestBuilder->expects($this->once())
            ->method('buildAuthorizationRequest')
            ->with($aluRequest)
            ->willReturn($jsonRequest);

        $this->mockHttpClient->expects($this->once())
            ->method('post')
            ->with(
                'http://ro.payu.local/api/v4/payments/authorize',
                $aluRequest->getMerchantConfig(),
                self::ORDER_DATE,
                $jsonRequest
            )
            ->willThrowException(new ConnectionException());

        // Then
        $this->paymentsV4->authorize($aluRequest);
    }

    /**
     * @expectedException \PayU\PaymentsApi\Exceptions\AuthorizationException
     */
    public function testResponseParserThrowsAuthorizationResponseException()
    {
        // Given
        $aluRequest = $this->createAluRequest();
        $jsonRequest = $this->createJsonRequest();
        $jsonResponse = $this->createJsonResponse();

        // When
        $this->addMockObjectsToPaymentsV4();

        $this->mockRequestBuilder->expects($this->once())
            ->method('buildAuthorizationRequest')
            ->with($aluRequest)
            ->willReturn($jsonRequest);

        $this->mockHttpClient->expects($this->once())
            ->method('post')
            ->with(
                'http://ro.payu.local/api/v4/payments/authorize',
                $aluRequest->getMerchantConfig(),
                self::ORDER_DATE,
                $jsonRequest
            )
            ->willReturn($jsonResponse);

        $this->mockResponseParser->expects($this->once())
            ->method('parseJsonResponse')
            ->with($jsonResponse)
            ->willThrowException(new AuthorizationResponseException());

        $this->paymentsV4->authorize($aluRequest);
    }

    /**
     * @expectedException \PayU\PaymentsApi\Exceptions\AuthorizationException
     */
    public function testResponseParserThrowsResponseBuilderException()
    {
        // Given
        $aluRequest = $this->createAluRequest();
        $jsonRequest = $this->createJsonRequest();
        $jsonResponse = $this->createJsonResponse();

        // When
        $this->addMockObjectsToPaymentsV4();

        $this->mockRequestBuilder->expects($this->once())
            ->method('buildAuthorizationRequest')
            ->with($aluRequest)
            ->willReturn($jsonRequest);

        $this->mockHttpClient->expects($this->once())
            ->method('post')
            ->with(
                'http://ro.payu.local/api/v4/payments/authorize',
                $aluRequest->getMerchantConfig(),
                self::ORDER_DATE,
                $jsonRequest
            )
            ->willReturn($jsonResponse);

        $this->mockResponseParser->expects($this->once())
            ->method('parseJsonResponse')
            ->with($jsonResponse)
            ->willThrowException(new ResponseBuilderException());

        $this->paymentsV4->authorize($aluRequest);
    }
}

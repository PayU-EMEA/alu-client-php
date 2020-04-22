<?php


namespace PayU\PaymentsApi\PaymentsV4\Services;


use PayU\Alu\MerchantConfig;

class HashServiceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var HashService
     */
    private $hashService;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $merchantConfigMock;

    public function setUp()
    {
        $this->hashService = new HashService();

        $this->merchantConfigMock = $this->getMockBuilder(MerchantConfig::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    public function generateSignatureProvider()
    {
        return array(
            array(
                '2020-04-10T15:15:08+00:00',
                '{"merchantPaymentReference":"1112","currency":"RON","returnUrl":"http:\/\/path\/to\/your\/returnUrlScript","authorization":{"paymentMethod":"CCVISAMC","installmentsNumber":null,"cardDetails":{"number":"4111111111111111","expiryMonth":"01","expiryYear":"2026","cvv":"123","owner":"Card Owner Name"}},"client":{"billing":{"firstName":"FirstName","lastName":"LastName","email":"john.doe@mail.com","phone":"40123456789","city":"City","countryCode":"RO","state":null,"companyName":null,"taxId":null,"addressLine1":"Address1","addressLine2":"Address2","zipCode":null,"identityDocument":{"number":"111222","type":null}},"delivery":{"firstName":"FirstName","lastName":"LastName","phone":"40123456789","addressLine1":"Address1","addressLine2":"Address2","zipCode":null,"city":"City","state":null,"countryCode":"RO","email":"john.doe@mail.com"},"ip":"127.0.0.1","time":""},"products":[{"name":"PNAME01","sku":"PCODE01","unitPrice":100,"quantity":1,"additionalDetails":null,"vat":24},{"name":"PNAME02","sku":"PCODE02","unitPrice":200,"quantity":1,"additionalDetails":null,"vat":24}]}',
                '4ad6a9925c94bb41afa79f2ad44b5b4ae307ab356b5e005f371a8c3bb53cc3fc'
            ),
        );
    }

    /**
     * @dataProvider generateSignatureProvider
     */
    public function testGenerateSignature($orderDate, $jsonRequest, $expectedHash)
    {
        // When
        $this->merchantConfigMock->expects($this->once())
            ->method('getMerchantCode')
            ->willReturn('PAYU_2');

        $this->merchantConfigMock->expects($this->once())
            ->method('getSecretKey')
            ->willReturn('SECRET_KEY');

        // Then

        $this->assertEquals(
            $expectedHash,
            $this->hashService->generateSignature($this->merchantConfigMock, $orderDate, $jsonRequest)
        );
    }
}

<?php
namespace PayU\Alu\Test\Transformer;

use PayU\Alu\Component\Billing;
use PayU\Alu\Component\Delivery;
use PayU\Alu\Transformer\DeliveryTransformer;

class DeliveryTransformerTest extends BaseTransformerTestCase
{
    public function testTransformSuccess()
    {
        $transformer = new DeliveryTransformer($this->config);

        $delivery = new Delivery();
        $delivery->withLastName("Doe");
        $delivery->withFirstName("John");
        $delivery->withCompany("Some Company");
        $delivery->withEmail("test@test.net");
        $delivery->withPhoneNumber("+40755123456");
        $delivery->withAddressLine1("address line 1");
        $delivery->withAddressLine2("address line 2");
        $delivery->withZipCode("000000");
        $delivery->withCity("Bucharest");
        $delivery->withState("Some City State");
        $delivery->withCountryCode("RO");

        $expected = array(
            'DELIVERY_LNAME'        => "Doe",
            'DELIVERY_FNAME'        => "John",
            'DELIVERY_COMPANY'      => "Some Company",
            'DELIVERY_PHONE'        => "+40755123456",
            'DELIVERY_ADDRESS'      => "address line 1",
            'DELIVERY_ADDRESS2'     => "address line 2",
            'DELIVERY_ZIPCODE'      => "000000",
            'DELIVERY_CITY'         => "Bucharest",
            'DELIVERY_STATE'        => "Some City State",
            'DELIVERY_COUNTRYCODE'  => "RO",
            'DELIVERY_EMAIL'        => "test@test.net",
        );

        $this->assertEquals($expected, $transformer->transform($delivery));
    }

    /**
     * @expectedException \PayU\Alu\Exception\InvalidArgumentException
     */
    public function testTransformFailWithBadInput()
    {
        $transformer = new DeliveryTransformer($this->config);
        $transformer->transform(new Billing());
    }
}

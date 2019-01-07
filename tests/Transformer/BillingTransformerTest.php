<?php
namespace PayU\Alu\Test\Transformer;

use PayU\Alu\Component\Billing;
use PayU\Alu\Component\Order;
use PayU\Alu\Transformer\BillingTransformer;

class BillingTransformerTest extends BaseTransformerTestCase
{
    public function testTransformSuccess()
    {
        $transformer = new BillingTransformer($this->config);

        $billing = new Billing();
        $billing->withLastName("Doe");
        $billing->withFirstName("John");
        $billing->withIdentityCardSeries("S1");
        $billing->withIdentityCardNumber("1234567");
        $billing->withIdentityCardIssuer("Someone");
        $billing->withIdentityCardType("SOME_TYPE");
        $billing->withPersonalNumericCode("123456");
        $billing->withCompany("Some Company");
        $billing->withCompanyFiscalCode("CF Code");
        $billing->withCompanyRegistrationNumber("CR123456");
        $billing->withCompanyBank("C Bank");
        $billing->withCompanyBankAccountNumber("CB123456");
        $billing->withEmail("test@test.net");
        $billing->withPhoneNumber("+40755123456");
        $billing->withFaxNumber("+40311234567");
        $billing->withAddressLine1("address line 1");
        $billing->withAddressLine2("address line 2");
        $billing->withZipCode("000000");
        $billing->withCity("Bucharest");
        $billing->withState("Some City State");
        $billing->withCountryCode("RO");

        $expected = array(
            'BILL_LNAME'        => "Doe",
            'BILL_FNAME'        => "John",
            'BILL_CISERIAL'     => "S1",
            'BILL_CINUMBER'     => "1234567",
            'BILL_CIISSUER'     => "Someone",
            'BILL_CITYPE'       => "SOME_TYPE",
            'BILL_CNP'          => "123456",
            'BILL_COMPANY'      => "Some Company",
            'BILL_FISCALCODE'   => "CF Code",
            'BILL_REGNUMBER'    => "CR123456",
            'BILL_BANK'         => "C Bank",
            'BILL_BANKACCOUNT'  => "CB123456",
            'BILL_EMAIL'        => "test@test.net",
            'BILL_PHONE'        => "+40755123456",
            'BILL_FAX'          => "+40311234567",
            'BILL_ADDRESS'      => "address line 1",
            'BILL_ADDRESS2'     => "address line 2",
            'BILL_ZIPCODE'      => "000000",
            'BILL_CITY'         => "Bucharest",
            'BILL_STATE'        => "Some City State",
            'BILL_COUNTRYCODE'  => "RO",
        );
        $this->assertEquals($expected, $transformer->transform($billing));
    }

    /**
     * @expectedException \PayU\Alu\Exception\InvalidArgumentException
     */
    public function testTransformFailWithBadInput()
    {
        $transformer = new BillingTransformer($this->config);
        $transformer->transform(new Order());
    }
}

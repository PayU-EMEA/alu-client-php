<?php
namespace PayU\Alu\Transformer;

use PayU\Alu\Component\Billing;
use PayU\Alu\Component\Component;
use PayU\Alu\Exception\InvalidArgumentException;

class BillingTransformer extends Transformer
{
    /**
     * @param Component $component
     * @return array
     */
    public function transform(Component $component)
    {
        if (!$component instanceof Billing) {
            throw new InvalidArgumentException("Unexpected type: " . get_class($component));
        }

        /** @var Billing $billing */
        $billing = $component;

        return array(
            'BILL_LNAME'        => $billing->getLastName(),
            'BILL_FNAME'        => $billing->getFirstName(),
            'BILL_CISERIAL'     => $billing->getIdentityCardSeries(),
            'BILL_CINUMBER'     => $billing->getIdentityCardNumber(),
            'BILL_CIISSUER'     => $billing->getIdentityCardIssuer(),
            'BILL_CITYPE'       => $billing->getIdentityCardType(),
            'BILL_CNP'          => $billing->getPersonalNumericCode(),
            'BILL_COMPANY'      => $billing->getCompany(),
            'BILL_FISCALCODE'   => $billing->getCompanyFiscalCode(),
            'BILL_REGNUMBER'    => $billing->getCompanyRegistrationNumber(),
            'BILL_BANK'         => $billing->getCompanyBank(),
            'BILL_BANKACCOUNT'  => $billing->getCompanyBankAccountNumber(),
            'BILL_EMAIL'        => $billing->getEmail(),
            'BILL_PHONE'        => $billing->getPhoneNumber(),
            'BILL_FAX'          => $billing->getFaxNumber(),
            'BILL_ADDRESS'      => $billing->getAddressLine1(),
            'BILL_ADDRESS2'     => $billing->getAddressLine2(),
            'BILL_ZIPCODE'      => $billing->getZipCode(),
            'BILL_CITY'         => $billing->getCity(),
            'BILL_STATE'        => $billing->getState(),
            'BILL_COUNTRYCODE'  => $billing->getCountryCode(),
        );
    }
}
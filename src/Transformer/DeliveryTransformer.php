<?php
namespace PayU\Alu\Transformer;

use PayU\Alu\Component\Component;
use PayU\Alu\Component\Delivery;
use PayU\Alu\Exception\InvalidArgumentException;

class DeliveryTransformer extends Transformer
{
    /**
     * @param Component $component
     * @return array
     */
    public function transform(Component $component)
    {
        if (!$component instanceof Delivery) {
            throw new InvalidArgumentException("Unexpected type: " . get_class($component));
        }

        /** @var Delivery $delivery */
        $delivery = $component;

        return array(
            'DELIVERY_LNAME'        => $delivery->getLastName(),
            'DELIVERY_FNAME'        => $delivery->getFirstName(),
            'DELIVERY_COMPANY'      => $delivery->getCompany(),
            'DELIVERY_PHONE'        => $delivery->getPhoneNumber(),
            'DELIVERY_ADDRESS'      => $delivery->getAddressLine1(),
            'DELIVERY_ADDRESS2'     => $delivery->getAddressLine2(),
            'DELIVERY_ZIPCODE'      => $delivery->getZipCode(),
            'DELIVERY_CITY'         => $delivery->getCity(),
            'DELIVERY_STATE'        => $delivery->getState(),
            'DELIVERY_COUNTRYCODE'  => $delivery->getCountryCode(),
            'DELIVERY_EMAIL'        => $delivery->getEmail(),
        );
    }
}

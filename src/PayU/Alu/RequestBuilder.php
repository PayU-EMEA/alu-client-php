<?php


namespace PayU\Alu;

class RequestBuilder
{
    /**
     * @param Request $requestV3
     * @return false|string
     */
    public function buildAuthorizationRequestAsJson($requestV3)
    {
        $internalArray = [];
        $internalArray["merchantPaymentReference"] = $requestV3->getOrder()->getOrderRef();
        $internalArray["currency"] = $requestV3->getOrder()->getCurrency();
        $internalArray["returnUrl"] = $requestV3->getOrder()->getBackRef();

        $internalArray["authorization"] = [
            "paymentMethod" => $requestV3->getOrder()->getPayMethod(),
            "installmentsNumber" => $requestV3->getOrder()->getInstallmentsNumber(),
            /*"merchantToken" => [
                "tokenHash" =>
                "cvv" =>
                "owner" =>
            ]
            "usePaymentPage"
            "loyaltyPointsAmount"
            "campaignType"
            */
        ];

        if (!is_null($requestV3->getCard()) && is_null($requestV3->getCardToken())) {
            $internalArray["authorization"]["cardDetails"] = [
                "number" => $requestV3->getCard()->getCardNumber(),
                "expiryMonth" => $requestV3->getCard()->getCardExpirationMonth(),
                "expiryYear" => $requestV3->getCard()->getCardExpirationYear(),
                "cvv" => $requestV3->getCard()->getCardCVV(),
                "owner" => $requestV3->getCard()->getCardOwnerName()
            ];

            if ($requestV3->getCard()->hasTimeSpentTypingNumber()) {
                $internalArray["authorization"]["cardDetails"]["timeSpentTypingNumber"] = $requestV3->getCard()->getTimeSpentTypingNumber();
            }

            if ($requestV3->getCard()->hasTimeSpentTypingOwner()) {
                $internalArray["authorization"]["cardDetails"]["timeSpentTypingOwner"] = $requestV3->getCard()->getTimeSpentTypingOwner();
            }
        }

        if (!is_null($requestV3->getFx())) {
            $internalArray["authorization"]["fx"] = $requestV3->getFx()->getAuthorizationCurrency();
            $internalArray["authorization"]["fx"] = $requestV3->getFx()->getAuthorizationExchangeRate();
        }

        $internalArray["client"] = [
            "billing" => [
                "firstName" => $requestV3->getBillingData()->getFirstName(),
                "lastName" => $requestV3->getBillingData()->getLastName(),
                "email" => $requestV3->getBillingData()->getEmail(),
                "phone" => $requestV3->getBillingData()->getPhoneNumber(),
                "city" => $requestV3->getBillingData()->getCity(),
                "countryCode" => $requestV3->getBillingData()->getCountryCode(),
                "state" => $requestV3->getBillingData()->getState(),
                "companyName" => $requestV3->getBillingData()->getCompany(),
                "taxId" => $requestV3->getBillingData()->getCompanyFiscalCode(),
                "addressLine1" => $requestV3->getBillingData()->getAddressLine1(),
                "addressLine2" => $requestV3->getBillingData()->getAddressLine2(),
                "zipCode" => $requestV3->getBillingData()->getZipCode(),
                "identityDocument" => [
                    'number' => $requestV3->getBillingData()->getIdentityCardNumber(),
                    'type' => $requestV3->getBillingData()->getIdentityCardType()
                ]
            ]
        ];

        if (!empty($requestV3->getDeliveryData())) {
            $internalArray["client"]["delivery"] = [
                "firstName" => $requestV3->getDeliveryData()->getFirstName(),
                "lastName" => $requestV3->getDeliveryData()->getLastName(),
                "phone" => $requestV3->getDeliveryData()->getPhoneNumber(),
                "addressLine1" => $requestV3->getDeliveryData()->getAddressLine1(),
                "addressLine2" => $requestV3->getDeliveryData()->getAddressLine2(),
                "zipCode" => $requestV3->getDeliveryData()->getZipCode(),
                "city" => $requestV3->getDeliveryData()->getCity(),
                "state" => $requestV3->getDeliveryData()->getState(),
                "countryCode" => $requestV3->getDeliveryData()->getCountryCode(),
                "email" => $requestV3->getDeliveryData()->getEmail()
            ];
        }

        if (!empty($requestV3->getUser())) {
            $internalArray["client"]["ip"] = $requestV3->getUser()->getUserIPAddress();
            $internalArray["client"]["time"] = $requestV3->getUser()->getClientTime();
            //"communicationLanguage"
        }

        /*
        if (!empty($requestV3->getMerchant())) {
            $internalArray["merchant"] = $requestV3->getMerchant->getPosCode();
        }
        */

        $internalArray["products"] = $this->getProductArray($requestV3);
//        if (airlineInfo){}
//        if (threeDSecure){}
//        if (storedCredentials){}

        return json_encode($internalArray);
    }

    /**
     * @param $requestV3
     * @return array
     */
    private function getProductArray($requestV3)
    {
        $cnt = 0;
        $productsArray = [];
        /**
         * @var Request $requestV3
         * @var Product $product
         */
        foreach ($requestV3->getOrder()->getProducts() as $product) {
            $productsArray[$cnt] =
                [
                    "name" => $product->getName(),
                    "sku" => $product->getCode(),
                    "unitPrice" => $product->getPrice(),
                    "quantity" => $product->getQuantity(),
                    "additionalDetails" => $product->getInfo(),
                    "vat" => $product->getVAT()
                ];
            $cnt++;
        }
        return $productsArray;
    }

    /**
     * @param Request $requestV3
     * @param string $apiSignature
     * @return array
     */
    public function buildRequestHeaders(Request $requestV3, $apiSignature)
    {
        return [
            "Accept: application/json",
            "X-Header-Signature:" . $apiSignature,
            "X-Header-Merchant:" . $requestV3->getMerchantConfig()->getMerchantCode(),
            "X-Header-Date:" . $requestV3->getOrder()->getOrderDate(),
            "Content-Type: application/json;charset=utf-8"
        ];
    }
}

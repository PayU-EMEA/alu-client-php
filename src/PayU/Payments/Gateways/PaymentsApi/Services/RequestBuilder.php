<?php


namespace PaymentsApi\Services;


use PayU\Alu\Request;
use PaymentsApi\Entities\AuthorizationData;
use PaymentsApi\Entities\AuthorizationRequest;
use PaymentsApi\Entities\BillingData;
use PaymentsApi\Entities\CardDetails;
use PaymentsApi\Entities\ClientData;
use PaymentsApi\Entities\DeliveryData;
use PaymentsApi\Entities\FxData;
use PaymentsApi\Entities\IdentityDocumentData;
use PaymentsApi\Entities\MerchantTokenData;
use PaymentsApi\Entities\ProductData;

class RequestBuilder
{
    /**
     * @param Request $request
     * @return false|string
     */
    public function buildAuthorizationRequest($request)
    {
        $authorizationData = new AuthorizationData($request->getOrder()->getPayMethod());
        $authorizationData->setInstallmentsNumber($request->getOrder()->getInstallmentsNumber());
        /*
         *      applePayToken object
                "usePaymentPage"
                "loyaltyPointsAmount"
                "campaignType"
         */

        if (!is_null($request->getCard()) && is_null($request->getCardToken())) {
            $cardDetails = new CardDetails(
                $request->getCard()->getCardNumber(),
                $request->getCard()->getCardExpirationMonth(),
                $request->getCard()->getCardExpirationYear()
            );

            $cardDetails->setCvv($request->getCard()->getCardCVV());
            $cardDetails->setOwner($request->getCard()->getCardOwnerName());

            if ($request->getCard()->hasTimeSpentTypingNumber()) {
                $cardDetails->setTimeSpentTypingNumber($request->getCard()->getTimeSpentTypingNumber());
            }

            if ($request->getCard()->hasTimeSpentTypingOwner()) {
                $cardDetails->setTimeSpentTypingOwner($request->getCard()->getTimeSpentTypingOwner());
            }

            $authorizationData->setCardDetails($cardDetails);
        }


        if (is_null($request->getCard()) && !is_null($request->getCardToken())) {
            $merchantToken = new MerchantTokenData($request->getCardToken()->getToken());

            if ($request->getCardToken()->hasCvv()) {
                $merchantToken->setCvv($request->getCardToken()->getCvv());
            }

            if ($request->getCardToken()->hasOwner()) {
                $merchantToken->setOwner($request->getCardToken()->getOwner());
            }

            $authorizationData->setMerchantToken($merchantToken);
        }

        if (!is_null($request->getFx())) {
            $fxData = new FxData(
                $request->getFx()->getAuthorizationCurrency(),
                $request->getFx()->getAuthorizationExchangeRate()
            );

            $authorizationData->setFx($fxData);
        }

        $billingData = new BillingData(
            $request->getBillingData()->getFirstName(),
            $request->getBillingData()->getLastName(),
            $request->getBillingData()->getEmail(),
            $request->getBillingData()->getPhoneNumber(),
            $request->getBillingData()->getCity(),
            $request->getBillingData()->getCountryCode()
        );

        $billingData->setState($request->getBillingData()->getState());
        $billingData->setCompanyName($request->getBillingData()->getCompany());
        $billingData->setTaxId($request->getBillingData()->getCompanyFiscalCode());
        $billingData->setAddressLine1($request->getBillingData()->getAddressLine1());
        $billingData->setAddressLine2($request->getBillingData()->getAddressLine2());
        $billingData->setZipCode($request->getBillingData()->getZipCode());

        if ($request->getBillingData()->getIdentityCardNumber() != null ||
            $request->getBillingData()->getIdentityCardType() != null
        ) {
            $identityDocumentData = new IdentityDocumentData();

            $identityDocumentData->setNumber($request->getBillingData()->getIdentityCardNumber());
            $identityDocumentData->setType($request->getBillingData()->getIdentityCardType());

            $billingData->setIdentityDocument($identityDocumentData);
        }


        $clientData = new ClientData($billingData);

        if (!empty($request->getDeliveryData())) {
            $deliveryData = new DeliveryData();

            $deliveryData->setFirstName($request->getDeliveryData()->getFirstName());
            $deliveryData->setLastName($request->getDeliveryData()->getLastName());
            $deliveryData->setPhone($request->getDeliveryData()->getPhoneNumber());
            $deliveryData->setAddressLine1($request->getDeliveryData()->getAddressLine1());
            $deliveryData->setAddressLine2($request->getDeliveryData()->getAddressLine2());
            $deliveryData->setZipCode($request->getDeliveryData()->getZipCode());
            $deliveryData->setCity($request->getDeliveryData()->getCity());
            $deliveryData->setState($request->getDeliveryData()->getState());
            $deliveryData->setCountryCode($request->getDeliveryData()->getCountryCode());
            $deliveryData->setEmail($request->getDeliveryData()->getEmail());

            $clientData->setDeliveryData($deliveryData);
        }

        if (!empty($request->getUser())) {
            $clientData->setIp($request->getUser()->getUserIPAddress());
            $clientData->setTime($request->getUser()->getClientTime());
            //"communicationLanguage"
        }

        $authorizationRequest = new AuthorizationRequest(
            $request->getOrder()->getOrderRef(),
            $request->getOrder()->getCurrency(),
            $request->getOrder()->getBackRef(),
            $authorizationData,
            $clientData,
            $this->getProductArray($request)
        );

        /*
         * no PosCode object in Request
        if (!empty($request->getMerchant())){
            $merchantData = new MerchantData($request->getMerchant());

            $authorizationRequest->setMerchant($merchantData);
        }
        */

//        if (airlineInfo){}
//        if (threeDSecure){}
//        if (storedCredentials){}

        return json_encode($authorizationRequest);
    }

    /**
     * @param Request $request
     * @return ProductData[]
     */
    private function getProductArray($request)
    {
        $cnt = 0;
        $productsArray = [];

        /**
         * @var Request $request
         * @var Product $product
         */
        foreach ($request->getOrder()->getProducts() as $product) {
            $productData = new ProductData(
                $product->getName(),
                $product->getCode(),
                $product->getPrice(),
                $product->getQuantity()
            );

            $productData->setAdditionalDetails($product->getInfo());
            $productData->setVat($product->getVAT());

            $productsArray[$cnt++] = $productData;
        }

        return $productsArray;
    }


}

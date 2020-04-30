<?php


namespace PayU\PaymentsApi\PaymentsV4\Services;

use PayU\Alu\Product;
use PayU\Alu\Request;
use PayU\PaymentsApi\PaymentsV4\Entities\ApplePayToken;
use PayU\PaymentsApi\PaymentsV4\Entities\ApplePayTokenHeader;
use PayU\PaymentsApi\PaymentsV4\Entities\AuthorizationData;
use PayU\PaymentsApi\PaymentsV4\Entities\AuthorizationRequest;
use PayU\PaymentsApi\PaymentsV4\Entities\BillingData;
use PayU\PaymentsApi\PaymentsV4\Entities\CardDetails;
use PayU\PaymentsApi\PaymentsV4\Entities\ClientData;
use PayU\PaymentsApi\PaymentsV4\Entities\DeliveryData;
use PayU\PaymentsApi\PaymentsV4\Entities\FxData;
use PayU\PaymentsApi\PaymentsV4\Entities\IdentityDocumentData;
use PayU\PaymentsApi\PaymentsV4\Entities\Marketplace;
use PayU\PaymentsApi\PaymentsV4\Entities\MerchantTokenData;
use PayU\PaymentsApi\PaymentsV4\Entities\MpiData;
use PayU\PaymentsApi\PaymentsV4\Entities\ProductData;
use PayU\PaymentsApi\PaymentsV4\Entities\AirlineInfoData;
use PayU\PaymentsApi\PaymentsV4\Entities\FlightSegments;
use PayU\PaymentsApi\PaymentsV4\Entities\StoredCredentialsData;
use PayU\PaymentsApi\PaymentsV4\Entities\ThreeDSecure;
use PayU\PaymentsApi\PaymentsV4\Entities\TravelAgency;

class RequestBuilder
{
    /**
     * @param Request $request
     * @return false|string
     */
    public function buildAuthorizationRequest($request)
    {
        $authorizationData = new AuthorizationData($request->getOrder()->getPayMethod());

        //HOSTED_PAGE in V3
        //$authorizationData->setUsePaymentPage($request->getOrder()->getHostedPage());

        $authorizationData->setInstallmentsNumber($request->getOrder()->getInstallmentsNumber());
        $authorizationData->setUseLoyaltyPoints($request->getOrder()->getUseLoyaltyPoints());
        $authorizationData->setLoyaltyPointsAmount($request->getOrder()->getLoyaltyPointsAmount());
        $authorizationData->setCampaignType($request->getOrder()->getCampaignType());

        if ($request->getCard() !== null) {
            $cardDetails = new CardDetails(
                $request->getCard()->getCardNumber(),
                $request->getCard()->getCardExpirationMonth(),
                $request->getCard()->getCardExpirationYear()
            );

            $cardDetails->setCvv($request->getCard()->getCardCVV());
            $cardDetails->setOwner($request->getCard()->getCardOwnerName());

            $authorizationData->setCardDetails($cardDetails);
        }
        if ($request->getCardToken() !== null) {
            $merchantToken = new MerchantTokenData($request->getCardToken()->getToken());

            if ($request->getCardToken()->hasCvv()) {
                $merchantToken->setCvv($request->getCardToken()->getCvv());
            }

            $authorizationData->setMerchantToken($merchantToken);
        }
        if ($request->getApplePayToken() !== null) {
            $applePayHeader = new ApplePayTokenHeader(
                $request->getApplePayToken()->getHeader()->getApplicationData(),
                $request->getApplePayToken()->getHeader()->getEphemeralPublicKey(),
                $request->getApplePayToken()->getHeader()->getWrappedKey(),
                $request->getApplePayToken()->getHeader()->getPublicKeyHash(),
                $request->getApplePayToken()->getHeader()->getTransactionId()
            );

            $applePayToken = new ApplePayToken(
                $request->getApplePayToken()->getData(),
                $applePayHeader,
                $request->getApplePayToken()->getSignature(),
                $request->getApplePayToken()->getVersion()
            );
            $authorizationData->setApplePayToken($applePayToken);
        }

        if ($request->getFx() !== null) {
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

        if ($request->getBillingData()->getIdentityCardNumber() !== null ||
            $request->getBillingData()->getIdentityCardType() !== null
        ) {
            $identityDocumentData = new IdentityDocumentData();

            $identityDocumentData->setNumber($request->getBillingData()->getIdentityCardNumber());
            $identityDocumentData->setType($request->getBillingData()->getIdentityCardType());

            $billingData->setIdentityDocument($identityDocumentData);
        }


        $clientData = new ClientData($billingData);

        if ($request->getDeliveryData() !== null) {
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

        if ($request->getUser() !== null) {
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

        if ($request->getOrder()->getAirlineInfo() !== null) {
            $flightSegments = $this->getFlightSegmentsArray($request);

            $airlineInfo = new AirlineInfoData(
                $request->getOrder()->getAirlineInfo()->getPassengerName(),
                $flightSegments
            );

            $airlineInfo->setTicketNumber($request->getOrder()->getAirlineInfo()->getTicketNumber());
            $airlineInfo->setRefundPolicy($request->getOrder()->getAirlineInfo()->getRestrictedRefund());
            $airlineInfo->setReservationSystem($request->getOrder()->getAirlineInfo()->getReservationSystem());

            if ($request->getOrder()->getAirlineInfo()->getTravelAgencyCode() !== null ||
                $request->getOrder()->getAirlineInfo()->getTravelAgencyName() !== null
            ) {
                $travelAgency = new TravelAgency();
                $travelAgency->setCode($request->getOrder()->getAirlineInfo()->getTravelAgencyCode());
                $travelAgency->setName($request->getOrder()->getAirlineInfo()->getTravelAgencyName());

                $airlineInfo->setTravelAgency($travelAgency);
            }

            $authorizationRequest->setAirlineInfoData($airlineInfo);
        }

        if ($request->getThreeDSecure() !== null) {
            $mpi = new MpiData();

            $mpi->setEci($request->getThreeDSecure()->getMpi()->getEci());
            $mpi->setXid($request->getThreeDSecure()->getMpi()->getXid());
            $mpi->setCavv($request->getThreeDSecure()->getMpi()->getCavv());
            $mpi->setDsTransactionId($request->getThreeDSecure()->getMpi()->getDsTransactionId());
            $mpi->setVersion($request->getThreeDSecure()->getMpi()->getVersion());

            $threeDSecure = new ThreeDSecure($mpi);
            $authorizationRequest->setThreeDSecure($threeDSecure);
        }

        if ($request->getStoredCredentials() !== null) {
            $storedCredentials = new StoredCredentialsData();

            $storedCredentials->setConsentType($request->getStoredCredentials()->getStoredCredentialsConsentType());
            $storedCredentials->setUseType($request->getStoredCredentials()->getStoredCredentialsUseType());
            $storedCredentials->setUseId($request->getStoredCredentials()->getStoredCredentialsUseId());

            $authorizationRequest->setStoredCredentialsData($storedCredentials);
        }

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
            if ($product->getMarketplace() !== null) {
                $marketplace = new Marketplace(
                    $product->getMarketplace()->getId(),
                    $product->getMarketplace()->getSellerId(),
                    $product->getMarketplace()->getCommissionAmount(),
                    $product->getMarketplace()->getCommissionCurrency()
                );

                $productData->setMarketplace($marketplace);
            }

            $productsArray[$cnt++] = $productData;
        }

        return $productsArray;
    }

    /**
     * @param $request
     * @return FlightSegments[]
     */
    private function getFlightSegmentsArray($request)
    {
        $cnt = 0;
        $flightSegmentsArray = [];

        /**
         * @var Request $request
         */
        foreach ($request->getOrder()->getAirlineInfo()->getFlightSegments() as $flightSegmentArray) {
            $flightSegment = new FlightSegments(
                $flightSegmentArray['DEPARTURE_DATE'],
                $flightSegmentArray['DEPARTURE_AIRPORT'],
                $flightSegmentArray['DESTINATION_AIRPORT']
            );

            $flightSegment->setAirlineCode($flightSegmentArray['AIRLINE_CODE']);
            //$flightSegment->setAirlineName($flightSegmentArray['AIRLINE_NAME']);
            $flightSegment->setServiceClass($flightSegmentArray['SERVICE_CLASS']);
            $flightSegment->setStopover($flightSegmentArray['STOPOVER']);
            $flightSegment->setFareCode($flightSegmentArray['FARE_CODE']);
            $flightSegment->setFlightNumber($flightSegmentArray['FLIGHT_NUMBER']);

            $flightSegmentsArray[$cnt++] = $flightSegment;
        }

        return $flightSegmentsArray;
    }
}

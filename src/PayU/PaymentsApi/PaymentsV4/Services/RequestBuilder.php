<?php


namespace PayU\PaymentsApi\PaymentsV4\Services;

use PayU\Alu\Product;
use PayU\Alu\Request;
use PayU\PaymentsApi\PaymentsV4\Entities\Request\Authorization\ApplePayToken;
use PayU\PaymentsApi\PaymentsV4\Entities\Request\Authorization\ApplePayToken\ApplePayTokenHeader;
use PayU\PaymentsApi\PaymentsV4\Entities\Request\AuthorizationData;
use PayU\PaymentsApi\PaymentsV4\Entities\Request\AuthorizationRequest;
use PayU\PaymentsApi\PaymentsV4\Entities\Request\Client\BillingData;
use PayU\PaymentsApi\PaymentsV4\Entities\Request\Authorization\CardDetails;
use PayU\PaymentsApi\PaymentsV4\Entities\Request\ClientData;
use PayU\PaymentsApi\PaymentsV4\Entities\Request\Client\DeliveryData;
use PayU\PaymentsApi\PaymentsV4\Entities\Request\Authorization\FxData;
use PayU\PaymentsApi\PaymentsV4\Entities\Request\Client\Billing\IdentityDocumentData;
use PayU\PaymentsApi\PaymentsV4\Entities\Request\Product\Marketplace;
use PayU\PaymentsApi\PaymentsV4\Entities\Request\Authorization\MerchantTokenData;
use PayU\PaymentsApi\PaymentsV4\Entities\Request\ThreeDSecure\MpiData;
use PayU\PaymentsApi\PaymentsV4\Entities\Request\ProductData;
use PayU\PaymentsApi\PaymentsV4\Entities\Request\AirlineInfoData;
use PayU\PaymentsApi\PaymentsV4\Entities\Request\AirlineInfo\FlightSegments;
use PayU\PaymentsApi\PaymentsV4\Entities\Request\StoredCredentialsData;
use PayU\PaymentsApi\PaymentsV4\Entities\Request\ThreeDSecure;
use PayU\PaymentsApi\PaymentsV4\Entities\Request\AirlineInfo\TravelAgency;
use PayU\PaymentsApi\PaymentsV4\Exceptions\RequestBuilderException;

class RequestBuilder
{
    /**
     * @param Request $request
     * @return false|string
     * @throws RequestBuilderException
     */
    public function buildAuthorizationRequest($request)
    {
        $authorizationData = new AuthorizationData($request->getOrder()->getPayMethod());

        $authorizationData->setUsePaymentPage($request->getOrder()->getUsePaymentPage());
        $authorizationData->setInstallmentsNumber($request->getOrder()->getInstallmentsNumber());
        $authorizationData->setUseLoyaltyPoints($request->getOrder()->getUseLoyaltyPoints());
        $authorizationData->setLoyaltyPointsAmount($request->getOrder()->getLoyaltyPointsAmount());
        $authorizationData->setCampaignType($request->getOrder()->getCampaignType());

        $this->setPaymentInstrument($request, $authorizationData);

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
            $clientData->setCommunicationLanguage($request->getUser()->getCommunicationLanguage());
        }

        $authorizationRequest = new AuthorizationRequest(
            $request->getOrder()->getOrderRef(),
            $request->getOrder()->getCurrency(),
            $request->getOrder()->getBackRef(),
            $authorizationData,
            $clientData,
            $this->getProductArray($request)
        );

        if ($request->getOrder()->getAirlineInfo() !== null) {
            $authorizationRequest->setAirlineInfoData($this->getAirlineInfoData($request));
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

        $jsonRequest = json_encode($authorizationRequest);
        if ($jsonRequest === false) {
            throw new RequestBuilderException('Failed json encoding the request!');
        }

        return $jsonRequest;
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
            $flightSegment->setAirlineName($flightSegmentArray['AIRLINE_NAME']);
            $flightSegment->setServiceClass($flightSegmentArray['SERVICE_CLASS']);
            $flightSegment->setStopover($flightSegmentArray['STOPOVER']);
            $flightSegment->setFareCode($flightSegmentArray['FARE_CODE']);
            $flightSegment->setFlightNumber($flightSegmentArray['FLIGHT_NUMBER']);

            $flightSegmentsArray[$cnt++] = $flightSegment;
        }

        return $flightSegmentsArray;
    }

    /**
     * @param Request $request
     * @param AuthorizationData $authorizationData
     * @throws RequestBuilderException
     */
    private function setPaymentInstrument($request, &$authorizationData)
    {
        if ($request->getCard() !== null &&
            $request->getCardToken() === null &&
            $request->getApplePayToken() === null
        ) {
            $cardDetails = new CardDetails(
                $request->getCard()->getCardNumber(),
                $request->getCard()->getCardExpirationMonth(),
                $request->getCard()->getCardExpirationYear()
            );

            $cardDetails->setCvv($request->getCard()->getCardCVV());
            $cardDetails->setOwner($request->getCard()->getCardOwnerName());

            $authorizationData->setCardDetails($cardDetails);
            return;
        }

        if ($request->getCard() === null &&
            $request->getCardToken() !== null &&
            $request->getApplePayToken() === null
        ) {
            $merchantToken = new MerchantTokenData($request->getCardToken()->getToken());

            if ($request->getCardToken()->hasCvv()) {
                $merchantToken->setCvv($request->getCardToken()->getCvv());
            }

            $authorizationData->setMerchantToken($merchantToken);
            return;
        }

        if ($request->getCard() === null &&
            $request->getCardToken() === null &&
            $request->getApplePayToken() !== null
        ) {
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
            return;
        }

        throw new RequestBuilderException('Only one payment instrument can be sent');
    }

    /**
     * @param Request $request
     * @return AirlineInfoData
     */
    private function getAirlineInfoData($request)
    {
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

        return $airlineInfo;
    }
}

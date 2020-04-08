<?php


namespace PayU\PaymentsApi\AluV3\Services;

use PayU\Alu\AirlineInfo;
use PayU\Alu\Product;
use PayU\Alu\Request;
use PayU\Alu\StoredCredentials;
use PayU\Alu\StrongCustomerAuthentication;

final class RequestBuilder
{

    /**
     * @param Request $request
     * @return array
     */
    private function transformObject2Array(Request $request)
    {
        $requestArray = array();
        $requestArray['MERCHANT'] = $request->getMerchantConfig()->getMerchantCode();
        $requestArray['ORDER_REF'] = $request->getOrder()->getOrderRef();
        $requestArray['ORDER_DATE'] = $request->getOrder()->getOrderDate();

        $cnt = 0;
        /**
         * @var Product $product
         */
        foreach ($request->getOrder()->getProducts() as $product) {
            $requestArray['ORDER_PNAME'][$cnt] = $product->getName();
            $requestArray['ORDER_PGROUP'][$cnt] = $product->getProductGroup();
            $requestArray['ORDER_PCODE'][$cnt] = $product->getCode();
            $requestArray['ORDER_PINFO'][$cnt] = $product->getInfo();
            $requestArray['ORDER_PRICE'][$cnt] = $product->getPrice();
            $requestArray['ORDER_VAT'][$cnt] = $product->getVAT();
            $requestArray['ORDER_PRICE_TYPE'][$cnt] = $product->getPriceType();
            $requestArray['ORDER_QTY'][$cnt] = $product->getQuantity();
            $requestArray['ORDER_MPLACE_MERCHANT'][$cnt] = $product->getMarketPlaceMerchantCode();
            $requestArray['ORDER_VER'][$cnt] = $product->getProductVersion();
            $cnt++;
        }

        $requestArray['ORDER_SHIPPING'] = $request->getOrder()->getShippingCost();
        $requestArray['PRICES_CURRENCY'] = $request->getOrder()->getCurrency();
        $requestArray['DISCOUNT'] = $request->getOrder()->getDiscount();
        $requestArray['PAY_METHOD'] = $request->getOrder()->getPayMethod();

        if ($request->getCard() !== null && $request->getCardToken() === null) {
            $requestArray['CC_NUMBER'] = $request->getCard()->getCardNumber();
            $requestArray['EXP_MONTH'] = $request->getCard()->getCardExpirationMonth();
            $requestArray['EXP_YEAR'] = $request->getCard()->getCardExpirationYear();
            $requestArray['CC_CVV'] = $request->getCard()->getCardCVV();
            $requestArray['CC_OWNER'] = $request->getCard()->getCardOwnerName();
            if ($request->getCard()->isEnableTokenCreation()) {
                $requestArray['LU_ENABLE_TOKEN'] = '1';
            }
        }

        if ($request->getStoredCredentials() !== null) {
            if ($request->getStoredCredentials()->getStoredCredentialsConsentType() !== null) {
                $requestArray[StoredCredentials::STORED_CREDENTIALS_CONSENT_TYPE] =
                    $request->getStoredCredentials()->getStoredCredentialsConsentType();
            }

            if ($request->getStoredCredentials()->getStoredCredentialsUseType() !== null) {
                $requestArray[StoredCredentials::STORED_CREDENTIALS_USE_TYPE] =
                    $request->getStoredCredentials()->getStoredCredentialsUseType();

                if ($request->getStoredCredentials()->getStoredCredentialsUseId() !== null) {
                    $requestArray[StoredCredentials::STORED_CREDENTIALS_USE_ID] =
                        $request->getStoredCredentials()->getStoredCredentialsUseId();
                }
            }
        }


        $requestArray['SELECTED_INSTALLMENTS_NUMBER'] = $request->getOrder()->getInstallmentsNumber();
        $requestArray['CARD_PROGRAM_NAME'] = $request->getOrder()->getCardProgramName();

        if ($request->getCard() === null && $request->getCardToken() !== null) {
            $requestArray['CC_TOKEN'] = $request->getCardToken()->getToken();
            if ($request->getCardToken()->hasCvv()) {
                $requestArray['CC_CVV'] = $request->getCardToken()->getCvv();
            } else {
                $requestArray['CC_CVV'] = '';
            }
        }

        $requestArray['BACK_REF'] = $request->getOrder()->getBackRef();
        $requestArray['ALIAS'] = $request->getOrder()->getAlias();

        if ($request->getUser() !== null) {
            $requestArray['CLIENT_IP'] = $request->getUser()->getUserIPAddress();
            $requestArray['CLIENT_TIME'] = $request->getUser()->getClientTime();
        }

        $requestArray['BILL_LNAME'] = $request->getBillingData()->getLastName();
        $requestArray['BILL_FNAME'] = $request->getBillingData()->getFirstName();
        $requestArray['BILL_CISERIAL'] = $request->getBillingData()->getIdentityCardSeries();
        $requestArray['BILL_CINUMBER'] = $request->getBillingData()->getIdentityCardNumber();
        $requestArray['BILL_CIISSUER'] = $request->getBillingData()->getIdentityCardIssuer();
        $requestArray['BILL_CITYPE'] = $request->getBillingData()->getIdentityCardType();
        $requestArray['BILL_CNP'] = $request->getBillingData()->getPersonalNumericCode();
        $requestArray['BILL_COMPANY'] = $request->getBillingData()->getCompany();
        $requestArray['BILL_FISCALCODE'] = $request->getBillingData()->getCompanyFiscalCode();
        $requestArray['BILL_REGNUMBER'] = $request->getBillingData()->getCompanyRegistrationNumber();
        $requestArray['BILL_BANK'] = $request->getBillingData()->getCompanyBank();
        $requestArray['BILL_BANKACCOUNT'] = $request->getBillingData()->getCompanyBankAccountNumber();
        $requestArray['BILL_EMAIL'] = $request->getBillingData()->getEmail();
        $requestArray['BILL_PHONE'] = $request->getBillingData()->getPhoneNumber();
        $requestArray['BILL_FAX'] = $request->getBillingData()->getFaxNumber();
        $requestArray['BILL_ADDRESS'] = $request->getBillingData()->getAddressLine1();
        $requestArray['BILL_ADDRESS2'] = $request->getBillingData()->getAddressLine2();
        $requestArray['BILL_ZIPCODE'] = $request->getBillingData()->getZipCode();
        $requestArray['BILL_CITY'] = $request->getBillingData()->getCity();
        $requestArray['BILL_STATE'] = $request->getBillingData()->getState();
        $requestArray['BILL_COUNTRYCODE'] = $request->getBillingData()->getCountryCode();

        if ($request->getDeliveryData() !== null) {
            $requestArray['DELIVERY_LNAME'] = $request->getDeliveryData()->getLastName();
            $requestArray['DELIVERY_FNAME'] = $request->getDeliveryData()->getFirstName();
            $requestArray['DELIVERY_COMPANY'] = $request->getDeliveryData()->getCompany();
            $requestArray['DELIVERY_PHONE'] = $request->getDeliveryData()->getPhoneNumber();
            $requestArray['DELIVERY_ADDRESS'] = $request->getDeliveryData()->getAddressLine1();
            $requestArray['DELIVERY_ADDRESS2'] = $request->getDeliveryData()->getAddressLine2();
            $requestArray['DELIVERY_ZIPCODE'] = $request->getDeliveryData()->getZipCode();
            $requestArray['DELIVERY_CITY'] = $request->getDeliveryData()->getCity();
            $requestArray['DELIVERY_STATE'] = $request->getDeliveryData()->getState();
            $requestArray['DELIVERY_COUNTRYCODE'] = $request->getDeliveryData()->getCountryCode();
            $requestArray['DELIVERY_EMAIL'] = $request->getDeliveryData()->getEmail();
        }


        $requestArray['CC_NUMBER_RECIPIENT'] = $request->getOrder()->getCcNumberRecipient();

        $requestArray['USE_LOYALTY_POINTS'] = $request->getOrder()->getUseLoyaltyPoints();
        $requestArray['LOYALTY_POINTS_AMOUNT'] = $request->getOrder()->getLoyaltyPointsAmount();

        $requestArray['CAMPAIGN_TYPE'] = $request->getOrder()->getCampaignType();

        $airlineInfoInstance = $request->getOrder()->getAirlineInfo();

        if ($airlineInfoInstance instanceof AirlineInfo) {
            $requestArray['AIRLINE_INFO'] = array(
                'PASSENGER_NAME' => $request->getOrder()->getAirlineInfo()->getPassengerName(),
                'TICKET_NUMBER' => $request->getOrder()->getAirlineInfo()->getTicketNumber(),
                'RESTRICTED_REFUND' => $request->getOrder()->getAirlineInfo()->getRestrictedRefund(),
                'RESERVATION_SYSTEM' => $request->getOrder()->getAirlineInfo()->getReservationSystem(),
                'TRAVEL_AGENCY_CODE' => $request->getOrder()->getAirlineInfo()->getTravelAgencyCode(),
                'TRAVEL_AGENCY_NAME' => $request->getOrder()->getAirlineInfo()->getTravelAgencyName(),
                'FLIGHT_SEGMENTS' => $request->getOrder()->getAirlineInfo()->getFlightSegments(),
            );
        }

        if ($request->getFx() !== null) {
            $requestArray['AUTHORIZATION_CURRENCY'] = $request->getFx()->getAuthorizationCurrency();
            $requestArray['AUTHORIZATION_EXCHANGE_RATE'] = $request->getFx()->getAuthorizationExchangeRate();
        }


        if (is_array($request->getOrder()->getCustomParams())) {
            foreach ($request->getOrder()->getCustomParams() as $paramName => $paramValue) {
                $requestArray[$paramName] = $paramValue;
            }
        }

        $threeDsTwoZeroParams = $request->getStrongCustomerAuthentication();
        if ($threeDsTwoZeroParams instanceof StrongCustomerAuthentication) {
            $requestArray = array_merge(
                $requestArray,
                $this->threeDsTwoParams($request->getStrongCustomerAuthentication())
            );
        }

        ksort($requestArray);
        return $requestArray;
    }

    /**
     * @param StrongCustomerAuthentication $strongCustomerAuthentication
     * @return array
     */
    private function threeDsTwoParams(StrongCustomerAuthentication $strongCustomerAuthentication)
    {
        return array(
            'STRONG_CUSTOMER_AUTHENTICATION' => $strongCustomerAuthentication->getStrongCustomerAuthentication(),
            'ADDRESS_MATCH' => $strongCustomerAuthentication->getAddressMatch(),
            'BROWSER_ACCEPT_HEADER' => $strongCustomerAuthentication->getBrowserAcceptHeaders(),
            'BROWSER_IP' => $strongCustomerAuthentication->getBrowserIP(),
            'BROWSER_JAVA_ENABLED' => $strongCustomerAuthentication->getBrowserJavaEnabled(),
            'BROWSER_LANGUAGE' => $strongCustomerAuthentication->getBrowserLanguage(),
            'BROWSER_COLOR_DEPTH' => $strongCustomerAuthentication->getBrowserColorDepth(),
            'BROWSER_SCREEN_HEIGHT' => $strongCustomerAuthentication->getBrowserScreenHeight(),
            'BROWSER_SCREEN_WIDTH' => $strongCustomerAuthentication->getBrowserScreenWidth(),
            'BROWSER_TIMEZONE' => $strongCustomerAuthentication->getBrowserTimezone(),
            'BROWSER_USER_AGENT' => $strongCustomerAuthentication->getBrowserUserAgent(),
            'BILL_ADDRESS3' => $strongCustomerAuthentication->getBillAddress3(),
            'BILL_STATE_CODE' => $strongCustomerAuthentication->getBillStateCode(),
            'HOME_PHONE_COUNTRY_PREFIX' => $strongCustomerAuthentication->getHomePhoneCountryPrefix(),
            'HOME_PHONE_SUBSCRIBER' => $strongCustomerAuthentication->getHomePhoneSubscriber(),
            'MOBILE_PHONE_COUNTRY_PREFIX' => $strongCustomerAuthentication->getMobilePhoneCountryPrefix(),
            'MOBILE_PHONE_SUBSCRIBER' => $strongCustomerAuthentication->getMobilePhoneSubscriber(),
            'WORK_PHONE_COUNTRY_PREFIX' => $strongCustomerAuthentication->getWorkPhoneCountryPrefix(),
            'WORK_PHONE_SUBSCRIBER' => $strongCustomerAuthentication->getWorkPhoneSubscriber(),
            'DELIVERY_ADDRESS3' => $strongCustomerAuthentication->getDeliveryAddress3(),
            'DELIVERY_STATE_CODE' => $strongCustomerAuthentication->getDeliveryStateCode(),
            'CARDHOLDER_FRAUD_ACTIVITY' => $strongCustomerAuthentication->getCardHolderFraudActivity(),
            'DEVICE_CHANNEL' => $strongCustomerAuthentication->getDeviceChannel(),
            'CHALLENGE_INDICATOR' => $strongCustomerAuthentication->getChallengeIndicator(),
            'CHALLENGE_WINDOW_SIZE' => $strongCustomerAuthentication->getChallengeWindowSize(),
            'ACCOUNT_ADDITIONAL_INFORMATION' => $strongCustomerAuthentication->getAccountAdditionalInformation(),
            'SDK_REFERENCE_NUMBER' => $strongCustomerAuthentication->getSdkReferenceNumber(),
            'SDK_MAXIMUM_TIMEOUT' => $strongCustomerAuthentication->getSdkMaximumTimeout(),
            'SDK_APPLICATION_ID' => $strongCustomerAuthentication->getSdkApplicationId(),
            'SDK_ENC_DATA' => $strongCustomerAuthentication->getSdkEncData(),
            'SDK_TRANS_ID' => $strongCustomerAuthentication->getSdkTransId(),
            'SDK_EPHEMERAL_PUB_KEY' => $strongCustomerAuthentication->getSdkEphemeralPubKey(),
            'SDK_UI_TYPE' => $strongCustomerAuthentication->getSdkUiType(),
            'SDK_INTERFACE' => $strongCustomerAuthentication->getSdkInterface(),
            'TRANSACTION_TYPE' => $strongCustomerAuthentication->getTransactionType(),
            'SHIPPING_INDICATOR' => $strongCustomerAuthentication->getShippingIndicator(),
            'PREORDER_INDICATOR' => $strongCustomerAuthentication->getPreOrderIndicator(),
            'PREORDER_DATE' => $strongCustomerAuthentication->getPreOrderDate(),
            'DELIVERY_TIME_FRAME' => $strongCustomerAuthentication->getDeliveryTimeFrame(),
            'REORDER_INDICATOR' => $strongCustomerAuthentication->getReOrderIndicator(),
            'MERCHANT_FUNDS_AMOUNT' => $strongCustomerAuthentication->getMerchantFundsAmount(),
            'MERCHANT_FUNDS_CURRENCY' => $strongCustomerAuthentication->getMerchantFundsCurrency(),
            'RECURRING_FREQUENCY_DAYS' => $strongCustomerAuthentication->getRecurringFrequencyDays(),
            'RECURRING_EXPIRY_DATE' => $strongCustomerAuthentication->getRecurringExpiryDate(),
            'ACCOUNT_CREATE_DATE' => $strongCustomerAuthentication->getAccountCreateDate(),
            'ACCOUNT_DELIVERY_ADDRESS_FIRST_USED_DATE' =>
                $strongCustomerAuthentication->getAccountCreateDate(),
            'ACCOUNT_DELIVERY_ADDRESS_USAGE_INDICATOR' =>
                $strongCustomerAuthentication->getAccountDeliveryAddressUsageIndicator(),
            'ACCOUNT_NUMBER_OF_TRANSACTIONS_LAST_YEAR' =>
                $strongCustomerAuthentication->getAccountNumberOfTransactionsLastYear(),
            'ACCOUNT_NUMBER_OF_TRANSACTIONS_LAST_DAY' =>
                $strongCustomerAuthentication->getAccountNumberOfTransactionsLastDay(),
            'ACCOUNT_NUMBER_OF_PURCHASES_LAST_SIX_MONTHS' =>
                $strongCustomerAuthentication->getAccountNumberOfPurchasesLastSixMonths(),
            'ACCOUNT_CHANGE_DATE' => $strongCustomerAuthentication->getAccountChangeDate(),
            'ACCOUNT_CHANGE_INDICATOR' =>
                $strongCustomerAuthentication->getAccountChangeIndicator(),
            'ACCOUNT_AGE_INDICATOR' => $strongCustomerAuthentication->getAccountAgeIndicator(),
            'ACCOUNT_PASSWORD_CHANGED_DATE' => $strongCustomerAuthentication->getAccountPasswordChangedDate(),
            'ACCOUNT_PASSWORD_CHANGED_INDICATOR' =>
                $strongCustomerAuthentication->getAccountPasswordChangedIndicator(),
            'ACCOUNT_NAME_TO_RECIPIENT_MATCH' => $strongCustomerAuthentication->getAccountNameToRecipientMatch(),
            'ACCOUNT_ADD_CARD_ATTEMPTS_DAY' => $strongCustomerAuthentication->getAccountAddCardAttemptsDay(),
            'ACCOUNT_AUTH_METHOD' => $strongCustomerAuthentication->getAccountAuthMethod(),
            'ACCOUNT_AUTH_DATETIME' => $strongCustomerAuthentication->getAccountAuthDateTime(),
            'REQUESTOR_AUTHENTICATION_DATA' => $strongCustomerAuthentication->getRequestorAuthenticationData(),
            'ACCOUNT_CARD_ADDED_INDICATOR' => $strongCustomerAuthentication->getAccountCardAddedIndicator(),
            'ACCOUNT_CARD_ADDED_DATE' => $strongCustomerAuthentication->getAccountCardAddedDate()
        );
    }

    /**
     * @param Request $request
     * @return array
     */
    public function buildAuthorizationRequest(Request $request, \PayU\Alu\HashService $hashService)
    {
        $requestArray = $this->transformObject2Array($request);
        $requestArray['ORDER_HASH'] = $hashService->makeRequestHash($requestArray);

        return $requestArray;
    }
}

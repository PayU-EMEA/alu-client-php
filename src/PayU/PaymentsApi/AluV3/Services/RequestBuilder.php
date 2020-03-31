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
        $array = array();
        $array['MERCHANT'] = $request->getMerchantConfig()->getMerchantCode();
        $array['ORDER_REF'] = $request->getOrder()->getOrderRef();
        $array['ORDER_DATE'] = $request->getOrder()->getOrderDate();

        $cnt = 0;
        /**
         * @var Product $product
         */
        foreach ($request->getOrder()->getProducts() as $product) {
            $array['ORDER_PNAME'][$cnt] = $product->getName();
            $array['ORDER_PGROUP'][$cnt] = $product->getProductGroup();
            $array['ORDER_PCODE'][$cnt] = $product->getCode();
            $array['ORDER_PINFO'][$cnt] = $product->getInfo();
            $array['ORDER_PRICE'][$cnt] = $product->getPrice();
            $array['ORDER_VAT'][$cnt] = $product->getVAT();
            $array['ORDER_PRICE_TYPE'][$cnt] = $product->getPriceType();
            $array['ORDER_QTY'][$cnt] = $product->getQuantity();
            //duplicated line ??
            //$this->internalArray['ORDER_VAT'][$cnt] = $product->getVAT();
            $array['ORDER_MPLACE_MERCHANT'][$cnt] = $product->getMarketPlaceMerchantCode();
            //removed
            $array['ORDER_VER'][$cnt] = $product->getProductVersion();
            $cnt++;
        }

        // removed
        $array['ORDER_SHIPPING'] = $request->getOrder()->getShippingCost();
        $array['PRICES_CURRENCY'] = $request->getOrder()->getCurrency();
        //removed
        $array['DISCOUNT'] = $request->getOrder()->getDiscount();
        $array['PAY_METHOD'] = $request->getOrder()->getPayMethod();

        if (!is_null($request->getCard()) && is_null($request->getCardToken())) {
            $array['CC_NUMBER'] = $request->getCard()->getCardNumber();
            $array['EXP_MONTH'] = $request->getCard()->getCardExpirationMonth();
            $array['EXP_YEAR'] = $request->getCard()->getCardExpirationYear();
            $array['CC_CVV'] = $request->getCard()->getCardCVV();
            $array['CC_OWNER'] = $request->getCard()->getCardOwnerName();
            if ($request->getCard()->isEnableTokenCreation()) {
                $array['LU_ENABLE_TOKEN'] = '1';
            }
        }

        if (!is_null($request->getStoredCredentials())) {
            if (!is_null($request->getStoredCredentials()->getStoredCredentialsConsentType())) {
                $array[StoredCredentials::STORED_CREDENTIALS_CONSENT_TYPE] =
                    $request->getStoredCredentials()->getStoredCredentialsConsentType();
            }

            if (!is_null($request->getStoredCredentials()->getStoredCredentialsUseType())) {
                $array[StoredCredentials::STORED_CREDENTIALS_USE_TYPE] =
                    $request->getStoredCredentials()->getStoredCredentialsUseType();

                if (!is_null($request->getStoredCredentials()->getStoredCredentialsUseId())) {
                    $array[StoredCredentials::STORED_CREDENTIALS_USE_ID] =
                        $request->getStoredCredentials()->getStoredCredentialsUseId();
                }
            }
        }


        $array['SELECTED_INSTALLMENTS_NUMBER'] = $request->getOrder()->getInstallmentsNumber();
        //remove
        $array['CARD_PROGRAM_NAME'] = $request->getOrder()->getCardProgramName();

        if (is_null($request->getCard()) && !is_null($request->getCardToken())) {
            $array['CC_TOKEN'] = $request->getCardToken()->getToken();
            if ($request->getCardToken()->hasCvv()) {
                $array['CC_CVV'] = $request->getCardToken()->getCvv();
            } else {
                $array['CC_CVV'] = '';
            }
        }

        $array['BACK_REF'] = $request->getOrder()->getBackRef();
        //removed
        $array['ALIAS'] = $request->getOrder()->getAlias();

        if (!empty($request->getUser())) {
            $array['CLIENT_IP'] = $request->getUser()->getUserIPAddress();
            $array['CLIENT_TIME'] = $request->getUser()->getClientTime();
        }

        $array['BILL_LNAME'] = $request->getBillingData()->getLastName();
        $array['BILL_FNAME'] = $request->getBillingData()->getFirstName();
        //removed
        $array['BILL_CISERIAL'] = $request->getBillingData()->getIdentityCardSeries();
        //removed
        $array['BILL_CINUMBER'] = $request->getBillingData()->getIdentityCardNumber();
        //removed
        $array['BILL_CIISSUER'] = $request->getBillingData()->getIdentityCardIssuer();
        //removed
        $array['BILL_CITYPE'] = $request->getBillingData()->getIdentityCardType();
        //removed
        $array['BILL_CNP'] = $request->getBillingData()->getPersonalNumericCode();
        $array['BILL_COMPANY'] = $request->getBillingData()->getCompany();
        $array['BILL_FISCALCODE'] = $request->getBillingData()->getCompanyFiscalCode();
        //removed
        $array['BILL_REGNUMBER'] = $request->getBillingData()->getCompanyRegistrationNumber();
        //removed
        $array['BILL_BANK'] = $request->getBillingData()->getCompanyBank();
        //removed
        $array['BILL_BANKACCOUNT'] = $request->getBillingData()->getCompanyBankAccountNumber();
        $array['BILL_EMAIL'] = $request->getBillingData()->getEmail();
        $array['BILL_PHONE'] = $request->getBillingData()->getPhoneNumber();
        //removed
        $array['BILL_FAX'] = $request->getBillingData()->getFaxNumber();
        $array['BILL_ADDRESS'] = $request->getBillingData()->getAddressLine1();
        $array['BILL_ADDRESS2'] = $request->getBillingData()->getAddressLine2();
        $array['BILL_ZIPCODE'] = $request->getBillingData()->getZipCode();
        $array['BILL_CITY'] = $request->getBillingData()->getCity();
        $array['BILL_STATE'] = $request->getBillingData()->getState();
        $array['BILL_COUNTRYCODE'] = $request->getBillingData()->getCountryCode();

        if (!empty($request->getDeliveryData())) {
            $array['DELIVERY_LNAME'] = $request->getDeliveryData()->getLastName();
            $array['DELIVERY_FNAME'] = $request->getDeliveryData()->getFirstName();
            $array['DELIVERY_COMPANY'] = $request->getDeliveryData()->getCompany();
            $array['DELIVERY_PHONE'] = $request->getDeliveryData()->getPhoneNumber();
            $array['DELIVERY_ADDRESS'] = $request->getDeliveryData()->getAddressLine1();
            $array['DELIVERY_ADDRESS2'] = $request->getDeliveryData()->getAddressLine2();
            $array['DELIVERY_ZIPCODE'] = $request->getDeliveryData()->getZipCode();
            $array['DELIVERY_CITY'] = $request->getDeliveryData()->getCity();
            $array['DELIVERY_STATE'] = $request->getDeliveryData()->getState();
            $array['DELIVERY_COUNTRYCODE'] = $request->getDeliveryData()->getCountryCode();
            $array['DELIVERY_EMAIL'] = $request->getDeliveryData()->getEmail();
        }


        $array['CC_NUMBER_RECIPIENT'] = $request->getOrder()->getCcNumberRecipient();

        $array['USE_LOYALTY_POINTS'] = $request->getOrder()->getUseLoyaltyPoints();
        $array['LOYALTY_POINTS_AMOUNT'] = $request->getOrder()->getLoyaltyPointsAmount();

        $array['CAMPAIGN_TYPE'] = $request->getOrder()->getCampaignType();

        $airlineInfoInstance = $request->getOrder()->getAirlineInfo();

        if ($airlineInfoInstance instanceof AirlineInfo) {
            $array['AIRLINE_INFO'] = array(
                'PASSENGER_NAME' => $request->getOrder()->getAirlineInfo()->getPassengerName(),
                'TICKET_NUMBER' => $request->getOrder()->getAirlineInfo()->getTicketNumber(),
                'RESTRICTED_REFUND' => $request->getOrder()->getAirlineInfo()->getRestrictedRefund(),
                'RESERVATION_SYSTEM' => $request->getOrder()->getAirlineInfo()->getReservationSystem(),
                'TRAVEL_AGENCY_CODE' => $request->getOrder()->getAirlineInfo()->getTravelAgencyCode(),
                'TRAVEL_AGENCY_NAME' => $request->getOrder()->getAirlineInfo()->getTravelAgencyName(),
                'FLIGHT_SEGMENTS' => $request->getOrder()->getAirlineInfo()->getFlightSegments(),
            );
        }

        if (!is_null($request->getFx())) {
            $array['AUTHORIZATION_CURRENCY'] = $request->getFx()->getAuthorizationCurrency();
            $array['AUTHORIZATION_EXCHANGE_RATE'] = $request->getFx()->getAuthorizationExchangeRate();
        }


        if (is_array($request->getOrder()->getCustomParams())) {
            foreach ($request->getOrder()->getCustomParams() as $paramName => $paramValue) {
                $array[$paramName] = $paramValue;
            }
        }

        $threeDsTwoZeroParams = $request->getStrongCustomerAuthentication();
        if ($threeDsTwoZeroParams instanceof StrongCustomerAuthentication) {
            $array = array_merge(
                $array,
                $this->threeDsTwoParams($request->getStrongCustomerAuthentication())
            );
        }

        ksort($array);
        return $array;
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

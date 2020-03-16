<?php


namespace AluV3\Services;



use PayU\Alu\AirlineInfo;
use PayU\Alu\Product;
use PayU\Alu\Request;
use PayU\Alu\StoredCredentials;
use PayU\Alu\StrongCustomerAuthentication;

class RequestBuilder
{
    /**
     * @var array
     */
    private $internalArray;

    /**
     * @param Request $request
     * @return array
     */
    private function transformObject2Array(Request $request)
    {
        $this->internalArray = array();
        $this->internalArray['MERCHANT'] = $request->getMerchantConfig()->getMerchantCode();
        $this->internalArray['ORDER_REF'] = $request->getOrder()->getOrderRef();
        $this->internalArray['ORDER_DATE'] = $request->getOrder()->getOrderDate();

        $cnt = 0;
        /**
         * @var Product $product
         */
        foreach ($request->getOrder()->getProducts() as $product) {
            $this->internalArray['ORDER_PNAME'][$cnt] = $product->getName();
            $this->internalArray['ORDER_PGROUP'][$cnt] = $product->getProductGroup();
            $this->internalArray['ORDER_PCODE'][$cnt] = $product->getCode();
            $this->internalArray['ORDER_PINFO'][$cnt] = $product->getInfo();
            $this->internalArray['ORDER_PRICE'][$cnt] = $product->getPrice();
            $this->internalArray['ORDER_VAT'][$cnt] = $product->getVAT();
            $this->internalArray['ORDER_PRICE_TYPE'][$cnt] = $product->getPriceType();
            $this->internalArray['ORDER_QTY'][$cnt] = $product->getQuantity();
            //duplicated line ??
            //$this->internalArray['ORDER_VAT'][$cnt] = $product->getVAT();
            $this->internalArray['ORDER_MPLACE_MERCHANT'][$cnt] = $product->getMarketPlaceMerchantCode();
            //removed
            $this->internalArray['ORDER_VER'][$cnt] = $product->getProductVersion();
            $cnt++;
        }

        // removed
        $this->internalArray['ORDER_SHIPPING'] = $request->getOrder()->getShippingCost();
        $this->internalArray['PRICES_CURRENCY'] = $request->getOrder()->getCurrency();
        //removed
        $this->internalArray['DISCOUNT'] = $request->getOrder()->getDiscount();
        $this->internalArray['PAY_METHOD'] = $request->getOrder()->getPayMethod();

        if (!is_null($request->getCard()) && is_null($request->getCardToken())) {
            $this->internalArray['CC_NUMBER'] = $request->getCard()->getCardNumber();
            $this->internalArray['EXP_MONTH'] = $request->getCard()->getCardExpirationMonth();
            $this->internalArray['EXP_YEAR'] = $request->getCard()->getCardExpirationYear();
            $this->internalArray['CC_CVV'] = $request->getCard()->getCardCVV();
            $this->internalArray['CC_OWNER'] = $request->getCard()->getCardOwnerName();
            if ($request->getCard()->isEnableTokenCreation()) {
                $this->internalArray['LU_ENABLE_TOKEN'] = '1';
            }
        }

        if (!is_null($request->getStoredCredentials())) {
            if (!is_null($request->getStoredCredentials()->getStoredCredentialsConsentType())) {
                $this->internalArray[StoredCredentials::STORED_CREDENTIALS_CONSENT_TYPE] =
                    $request->getStoredCredentials()->getStoredCredentialsConsentType();
            }

            if (!is_null($request->getStoredCredentials()->getStoredCredentialsUseType())) {
                $this->internalArray[StoredCredentials::STORED_CREDENTIALS_USE_TYPE] =
                    $request->getStoredCredentials()->getStoredCredentialsUseType();

                if (!is_null($request->getStoredCredentials()->getStoredCredentialsUseId())) {
                    $this->internalArray[StoredCredentials::STORED_CREDENTIALS_USE_ID] =
                        $request->getStoredCredentials()->getStoredCredentialsUseId();
                }
            }
        }


        $this->internalArray['SELECTED_INSTALLMENTS_NUMBER'] = $request->getOrder()->getInstallmentsNumber();
        //remove
        $this->internalArray['CARD_PROGRAM_NAME'] = $request->getOrder()->getCardProgramName();

        if (is_null($request->getCard()) && !is_null($request->getCardToken())) {
            $this->internalArray['CC_TOKEN'] = $request->getCardToken()->getToken();
            if ($request->getCardToken()->hasCvv()) {
                $this->internalArray['CC_CVV'] = $request->getCardToken()->getCvv();
            } else {
                $this->internalArray['CC_CVV'] = '';
            }
        }

        $this->internalArray['BACK_REF'] = $request->getOrder()->getBackRef();
        //removed
        $this->internalArray['ALIAS'] = $request->getOrder()->getAlias();

        if (!empty($request->getUser())) {
            $this->internalArray['CLIENT_IP'] = $request->getUser()->getUserIPAddress();
            $this->internalArray['CLIENT_TIME'] = $request->getUser()->getClientTime();
        }

        $this->internalArray['BILL_LNAME'] = $request->getBillingData()->getLastName();
        $this->internalArray['BILL_FNAME'] = $request->getBillingData()->getFirstName();
        //removed
        $this->internalArray['BILL_CISERIAL'] = $request->getBillingData()->getIdentityCardSeries();
        //removed
        $this->internalArray['BILL_CINUMBER'] = $request->getBillingData()->getIdentityCardNumber();
        //removed
        $this->internalArray['BILL_CIISSUER'] = $request->getBillingData()->getIdentityCardIssuer();
        //removed
        $this->internalArray['BILL_CITYPE'] = $request->getBillingData()->getIdentityCardType();
        //removed
        $this->internalArray['BILL_CNP'] = $request->getBillingData()->getPersonalNumericCode();
        $this->internalArray['BILL_COMPANY'] = $request->getBillingData()->getCompany();
        $this->internalArray['BILL_FISCALCODE'] = $request->getBillingData()->getCompanyFiscalCode();
        //removed
        $this->internalArray['BILL_REGNUMBER'] = $request->getBillingData()->getCompanyRegistrationNumber();
        //removed
        $this->internalArray['BILL_BANK'] = $request->getBillingData()->getCompanyBank();
        //removed
        $this->internalArray['BILL_BANKACCOUNT'] = $request->getBillingData()->getCompanyBankAccountNumber();
        $this->internalArray['BILL_EMAIL'] = $request->getBillingData()->getEmail();
        $this->internalArray['BILL_PHONE'] = $request->getBillingData()->getPhoneNumber();
        //removed
        $this->internalArray['BILL_FAX'] = $request->getBillingData()->getFaxNumber();
        $this->internalArray['BILL_ADDRESS'] = $request->getBillingData()->getAddressLine1();
        $this->internalArray['BILL_ADDRESS2'] = $request->getBillingData()->getAddressLine2();
        $this->internalArray['BILL_ZIPCODE'] = $request->getBillingData()->getZipCode();
        $this->internalArray['BILL_CITY'] = $request->getBillingData()->getCity();
        $this->internalArray['BILL_STATE'] = $request->getBillingData()->getState();
        $this->internalArray['BILL_COUNTRYCODE'] = $request->getBillingData()->getCountryCode();

        if (!empty($request->getDeliveryData())) {
            $this->internalArray['DELIVERY_LNAME'] = $request->getDeliveryData()->getLastName();
            $this->internalArray['DELIVERY_FNAME'] = $request->getDeliveryData()->getFirstName();
            $this->internalArray['DELIVERY_COMPANY'] = $request->getDeliveryData()->getCompany();
            $this->internalArray['DELIVERY_PHONE'] = $request->getDeliveryData()->getPhoneNumber();
            $this->internalArray['DELIVERY_ADDRESS'] = $request->getDeliveryData()->getAddressLine1();
            $this->internalArray['DELIVERY_ADDRESS2'] = $request->getDeliveryData()->getAddressLine2();
            $this->internalArray['DELIVERY_ZIPCODE'] = $request->getDeliveryData()->getZipCode();
            $this->internalArray['DELIVERY_CITY'] = $request->getDeliveryData()->getCity();
            $this->internalArray['DELIVERY_STATE'] = $request->getDeliveryData()->getState();
            $this->internalArray['DELIVERY_COUNTRYCODE'] = $request->getDeliveryData()->getCountryCode();
            $this->internalArray['DELIVERY_EMAIL'] = $request->getDeliveryData()->getEmail();
        }


        $this->internalArray['CC_NUMBER_RECIPIENT'] = $request->getOrder()->getCcNumberRecipient();

        $this->internalArray['USE_LOYALTY_POINTS'] = $request->getOrder()->getUseLoyaltyPoints();
        $this->internalArray['LOYALTY_POINTS_AMOUNT'] = $request->getOrder()->getLoyaltyPointsAmount();

        $this->internalArray['CAMPAIGN_TYPE'] = $request->getOrder()->getCampaignType();

        $airlineInfoInstance = $request->getOrder()->getAirlineInfo();

        if ($airlineInfoInstance instanceof AirlineInfo) {
            $this->internalArray['AIRLINE_INFO'] = array(
                'PASSENGER_NAME' => $request->getOrder()->getAirlineInfo()->getPassengerName(),
                'TICKET_NUMBER' => $request->getOrder()->getAirlineInfo()->getTicketNumber(),
                'RESTRICTED_REFUND' => $request->getOrder()->getAirlineInfo()->getRestrictedRefund(),
                'RESERVATION_SYSTEM' => $request->getOrder()->getAirlineInfo()->getReservationSystem(),
                'TRAVEL_AGENCY_CODE' => $request->getOrder()->getAirlineInfo()->getTravelAgencyCode(),
                'TRAVEL_AGENCY_NAME' => $request->getOrder()->getAirlineInfo()->getTravelAgencyName(),
                'FLIGHT_SEGMENTS' => $request->getOrder()->getAirlineInfo()->getFlightSegments(),
            );
        }

        if (isset($this->fx)) {
            $this->internalArray['AUTHORIZATION_CURRENCY'] = $this->fx->getAuthorizationCurrency();
            $this->internalArray['AUTHORIZATION_EXCHANGE_RATE'] = $this->fx->getAuthorizationExchangeRate();
        }


        if (is_array($request->getOrder()->getCustomParams())) {
            foreach ($request->getOrder()->getCustomParams() as $paramName => $paramValue) {
                $this->internalArray[$paramName] = $paramValue;
            }
        }

        $threeDsTwoZeroParams = $request->getStrongCustomerAuthentication();
        if ($threeDsTwoZeroParams instanceof StrongCustomerAuthentication) {
            $this->internalArray = array_merge(
                $this->internalArray,
                $this->threeDsTwoParams($request->getStrongCustomerAuthentication())
            );
        }

        ksort($this->internalArray);
        return $this->internalArray;
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
    public function buildAuthorizationRequest(Request $request)
    {
        if (empty($this->internalArray)) {
            return $this->transformObject2Array($request);
        }
        return $this->internalArray;
    }

    /**
     * @param string $hash
     */
    public function setOrderHash($hash)
    {
        $this->internalArray['ORDER_HASH'] = $hash;
    }
}

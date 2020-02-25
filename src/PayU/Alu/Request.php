<?php

namespace PayU\Alu;

/**
 * Class Request
 * @package PayU\Alu
 */
class Request
{
    /**
     * @return Order
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * @return Card
     */
    public function getCard()
    {
        return $this->card;
    }

    /**
     * @return CardToken
     */
    public function getCardToken()
    {
        return $this->cardToken;
    }

    /**
     * @return StoredCredentials
     */
    public function getStoredCredentials()
    {
        return $this->storedCredentials;
    }

    /**
     * @return StrongCustomerAuthentication
     */
    public function getStrongCustomerAuthentication()
    {
        return $this->strongCustomerAuthentication;
    }

    /**
     * @return Billing
     */
    public function getBillingData()
    {
        return $this->billingData;
    }

    /**
     * @return AbstractCommonAddress
     */
    public function getDeliveryData()
    {
        return $this->deliveryData;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @return FX
     */
    public function getFx()
    {
        return $this->fx;
    }

    /**
     * @var MerchantConfig
     */
    private $merchantConfig;

    /**
     * @var Order
     */
    private $order;

    /**
     * @var Card
     */
    private $card = null;

    /**
     * @var CardToken
     */
    private $cardToken = null;

    /**
     * @var StoredCredentials
     */
    private $storedCredentials = null;


    /** @var $threeDSTwoZero StrongCustomerAuthentication */
    private $strongCustomerAuthentication;

    /**
     * @var Billing
     */
    private $billingData;

    /**
     * @var AbstractCommonAddress
     */
    private $deliveryData = null;

    /**
     * @var User
     */
    private $user;

    /**
     * @var FX
     */
    private $fx;

    /**
     * @var array
     */
    private $internalArray;
    /**
     * @var String
     */
    private $paymentsApiVersion;

    /**
     * @param MerchantConfig $merchantConfig
     * @param Order $order
     * @param Billing $billing
     * @param AbstractCommonAddress $delivery
     * @param User $user
     * @param String $paymentsApiVersion
     */
    public function __construct(
        MerchantConfig $merchantConfig,
        Order $order,
        Billing $billing,
        AbstractCommonAddress $delivery = null,
        User $user = null,
        $paymentsApiVersion = 'v3'
    ) {
        $this->merchantConfig = $merchantConfig;
        $this->order = $order;
        $this->billingData = $billing;
        $this->deliveryData = $delivery;
        $this->user = $user;
        $this->paymentsApiVersion = $paymentsApiVersion;
    }

    /**
     * @return MerchantConfig
     */
    public function getMerchantConfig()
    {
        return $this->merchantConfig;
    }

    /**
     * @return String
     */
    public function getPaymentsApiVersion()
    {
        return $this->paymentsApiVersion;
    }

    /**
     * @param Card $card
     */
    public function setCard(Card $card)
    {
        $this->card = $card;
    }

    /**
     * @param CardToken $cardToken
     */
    public function setCardToken(CardToken $cardToken)
    {
        $this->cardToken = $cardToken;
    }

    /**
     * @param StoredCredentials $storedCredentials
     */
    public function setStoredCredentials(StoredCredentials $storedCredentials)
    {
        $this->storedCredentials = $storedCredentials;
    }

    /**
     * @param StrongCustomerAuthentication $strongCustomerAuthentication
     */
    public function setStrongCustomerAuthentication(StrongCustomerAuthentication $strongCustomerAuthentication)
    {
        $this->strongCustomerAuthentication = $strongCustomerAuthentication;
    }

    public function setFx(FX $fx)
    {
        $this->fx = $fx;
    }

    /**
     * @return array
     */
    private function transformObject2Array()
    {
        $this->internalArray = array();
        $this->internalArray['MERCHANT'] = $this->merchantConfig->getMerchantCode();
        $this->internalArray['ORDER_REF'] = $this->order->getOrderRef();
        $this->internalArray['ORDER_DATE'] = $this->order->getOrderDate();

        $cnt = 0;
        /**
         * @var Product $product
         */
        foreach ($this->order->getProducts() as $product) {
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
        $this->internalArray['ORDER_SHIPPING'] = $this->order->getShippingCost();
        $this->internalArray['PRICES_CURRENCY'] = $this->order->getCurrency();
        //removed
        $this->internalArray['DISCOUNT'] = $this->order->getDiscount();
        $this->internalArray['PAY_METHOD'] = $this->order->getPayMethod();

        if (!is_null($this->card) && is_null($this->cardToken)) {
            $this->internalArray['CC_NUMBER'] = $this->card->getCardNumber();
            $this->internalArray['EXP_MONTH'] = $this->card->getCardExpirationMonth();
            $this->internalArray['EXP_YEAR'] = $this->card->getCardExpirationYear();
            $this->internalArray['CC_CVV'] = $this->card->getCardCVV();
            $this->internalArray['CC_OWNER'] = $this->card->getCardOwnerName();
            if ($this->card->isEnableTokenCreation()) {
                $this->internalArray['LU_ENABLE_TOKEN'] = '1';
            }
        }

        if (!is_null($this->storedCredentials)) {
            if (!is_null($this->storedCredentials->getStoredCredentialsConsentType())) {
                $this->internalArray[StoredCredentials::STORED_CREDENTIALS_CONSENT_TYPE] =
                    $this->storedCredentials->getStoredCredentialsConsentType();
            }

            if (!is_null($this->storedCredentials->getStoredCredentialsUseType())) {
                $this->internalArray[StoredCredentials::STORED_CREDENTIALS_USE_TYPE] =
                    $this->storedCredentials->getStoredCredentialsUseType();

                if (!is_null($this->storedCredentials->getStoredCredentialsUseId())) {
                    $this->internalArray[StoredCredentials::STORED_CREDENTIALS_USE_ID] =
                        $this->storedCredentials->getStoredCredentialsUseId();
                }
            }
        }


        $this->internalArray['SELECTED_INSTALLMENTS_NUMBER'] = $this->order->getInstallmentsNumber();
        //remove
        $this->internalArray['CARD_PROGRAM_NAME'] = $this->order->getCardProgramName();

        if (is_null($this->card) && !is_null($this->cardToken)) {
            $this->internalArray['CC_TOKEN'] = $this->cardToken->getToken();
            if ($this->cardToken->hasCvv()) {
                $this->internalArray['CC_CVV'] = $this->cardToken->getCvv();
            } else {
                $this->internalArray['CC_CVV'] = '';
            }
        }

        $this->internalArray['BACK_REF'] = $this->order->getBackRef();
        //removed
        $this->internalArray['ALIAS'] = $this->order->getAlias();

        if (!empty($this->user)) {
            $this->internalArray['CLIENT_IP'] = $this->user->getUserIPAddress();
            $this->internalArray['CLIENT_TIME'] = $this->user->getClientTime();
        }

        $this->internalArray['BILL_LNAME'] = $this->billingData->getLastName();
        $this->internalArray['BILL_FNAME'] = $this->billingData->getFirstName();
        //removed
        $this->internalArray['BILL_CISERIAL'] = $this->billingData->getIdentityCardSeries();
        //removed
        $this->internalArray['BILL_CINUMBER'] = $this->billingData->getIdentityCardNumber();
        //removed
        $this->internalArray['BILL_CIISSUER'] = $this->billingData->getIdentityCardIssuer();
        //removed
        $this->internalArray['BILL_CITYPE'] = $this->billingData->getIdentityCardType();
        //removed
        $this->internalArray['BILL_CNP'] = $this->billingData->getPersonalNumericCode();
        $this->internalArray['BILL_COMPANY'] = $this->billingData->getCompany();
        $this->internalArray['BILL_FISCALCODE'] = $this->billingData->getCompanyFiscalCode();
        //removed
        $this->internalArray['BILL_REGNUMBER'] = $this->billingData->getCompanyRegistrationNumber();
        //removed
        $this->internalArray['BILL_BANK'] = $this->billingData->getCompanyBank();
        //removed
        $this->internalArray['BILL_BANKACCOUNT'] = $this->billingData->getCompanyBankAccountNumber();
        $this->internalArray['BILL_EMAIL'] = $this->billingData->getEmail();
        $this->internalArray['BILL_PHONE'] = $this->billingData->getPhoneNumber();
        //removed
        $this->internalArray['BILL_FAX'] = $this->billingData->getFaxNumber();
        $this->internalArray['BILL_ADDRESS'] = $this->billingData->getAddressLine1();
        $this->internalArray['BILL_ADDRESS2'] = $this->billingData->getAddressLine2();
        $this->internalArray['BILL_ZIPCODE'] = $this->billingData->getZipCode();
        $this->internalArray['BILL_CITY'] = $this->billingData->getCity();
        $this->internalArray['BILL_STATE'] = $this->billingData->getState();
        $this->internalArray['BILL_COUNTRYCODE'] = $this->billingData->getCountryCode();

        if (!empty($this->deliveryData)) {
            $this->internalArray['DELIVERY_LNAME'] = $this->deliveryData->getLastName();
            $this->internalArray['DELIVERY_FNAME'] = $this->deliveryData->getFirstName();
            $this->internalArray['DELIVERY_COMPANY'] = $this->deliveryData->getCompany();
            $this->internalArray['DELIVERY_PHONE'] = $this->deliveryData->getPhoneNumber();
            $this->internalArray['DELIVERY_ADDRESS'] = $this->deliveryData->getAddressLine1();
            $this->internalArray['DELIVERY_ADDRESS2'] = $this->deliveryData->getAddressLine2();
            $this->internalArray['DELIVERY_ZIPCODE'] = $this->deliveryData->getZipCode();
            $this->internalArray['DELIVERY_CITY'] = $this->deliveryData->getCity();
            $this->internalArray['DELIVERY_STATE'] = $this->deliveryData->getState();
            $this->internalArray['DELIVERY_COUNTRYCODE'] = $this->deliveryData->getCountryCode();
            $this->internalArray['DELIVERY_EMAIL'] = $this->deliveryData->getEmail();
        }


        $this->internalArray['CC_NUMBER_RECIPIENT'] = $this->order->getCcNumberRecipient();

        $this->internalArray['USE_LOYALTY_POINTS'] = $this->order->getUseLoyaltyPoints();
        $this->internalArray['LOYALTY_POINTS_AMOUNT'] = $this->order->getLoyaltyPointsAmount();

        $this->internalArray['CAMPAIGN_TYPE'] = $this->order->getCampaignType();

        $airlineInfoInstance = $this->order->getAirlineInfo();

        if ($airlineInfoInstance instanceof AirlineInfo) {
            $this->internalArray['AIRLINE_INFO'] = array(
                'PASSENGER_NAME' => $this->order->getAirlineInfo()->getPassengerName(),
                'TICKET_NUMBER' => $this->order->getAirlineInfo()->getTicketNumber(),
                'RESTRICTED_REFUND' => $this->order->getAirlineInfo()->getRestrictedRefund(),
                'RESERVATION_SYSTEM' => $this->order->getAirlineInfo()->getReservationSystem(),
                'TRAVEL_AGENCY_CODE' => $this->order->getAirlineInfo()->getTravelAgencyCode(),
                'TRAVEL_AGENCY_NAME' => $this->order->getAirlineInfo()->getTravelAgencyName(),
                'FLIGHT_SEGMENTS' => $this->order->getAirlineInfo()->getFlightSegments(),
            );
        }

        if (isset($this->fx)) {
            $this->internalArray['AUTHORIZATION_CURRENCY'] = $this->fx->getAuthorizationCurrency();
            $this->internalArray['AUTHORIZATION_EXCHANGE_RATE'] = $this->fx->getAuthorizationExchangeRate();
        }


        if (is_array($this->order->getCustomParams())) {
            foreach ($this->order->getCustomParams() as $paramName => $paramValue) {
                $this->internalArray[$paramName] = $paramValue;
            }
        }

        $threeDsTwoZeroParams = $this->strongCustomerAuthentication;
        if ($threeDsTwoZeroParams instanceof StrongCustomerAuthentication) {
            $this->internalArray = array_merge($this->internalArray, $this->threeDsTwoParams());
        }

        ksort($this->internalArray);
        return $this->internalArray;
    }

    private function threeDsTwoParams()
    {
        return array(
            'STRONG_CUSTOMER_AUTHENTICATION' => $this->strongCustomerAuthentication->getStrongCustomerAuthentication(),
            'ADDRESS_MATCH' => $this->strongCustomerAuthentication->getAddressMatch(),
            'BROWSER_ACCEPT_HEADER' => $this->strongCustomerAuthentication->getBrowserAcceptHeaders(),
            'BROWSER_IP' => $this->strongCustomerAuthentication->getBrowserIP(),
            'BROWSER_JAVA_ENABLED' => $this->strongCustomerAuthentication->getBrowserJavaEnabled(),
            'BROWSER_LANGUAGE' => $this->strongCustomerAuthentication->getBrowserLanguage(),
            'BROWSER_COLOR_DEPTH' => $this->strongCustomerAuthentication->getBrowserColorDepth(),
            'BROWSER_SCREEN_HEIGHT' => $this->strongCustomerAuthentication->getBrowserScreenHeight(),
            'BROWSER_SCREEN_WIDTH' => $this->strongCustomerAuthentication->getBrowserScreenWidth(),
            'BROWSER_TIMEZONE' => $this->strongCustomerAuthentication->getBrowserTimezone(),
            'BROWSER_USER_AGENT' => $this->strongCustomerAuthentication->getBrowserUserAgent(),
            'BILL_ADDRESS3' => $this->strongCustomerAuthentication->getBillAddress3(),
            'BILL_STATE_CODE' => $this->strongCustomerAuthentication->getBillStateCode(),
            'HOME_PHONE_COUNTRY_PREFIX' => $this->strongCustomerAuthentication->getHomePhoneCountryPrefix(),
            'HOME_PHONE_SUBSCRIBER' => $this->strongCustomerAuthentication->getHomePhoneSubscriber(),
            'MOBILE_PHONE_COUNTRY_PREFIX' => $this->strongCustomerAuthentication->getMobilePhoneCountryPrefix(),
            'MOBILE_PHONE_SUBSCRIBER' => $this->strongCustomerAuthentication->getMobilePhoneSubscriber(),
            'WORK_PHONE_COUNTRY_PREFIX' => $this->strongCustomerAuthentication->getWorkPhoneCountryPrefix(),
            'WORK_PHONE_SUBSCRIBER' => $this->strongCustomerAuthentication->getWorkPhoneSubscriber(),
            'DELIVERY_ADDRESS3' => $this->strongCustomerAuthentication->getDeliveryAddress3(),
            'DELIVERY_STATE_CODE' => $this->strongCustomerAuthentication->getDeliveryStateCode(),
            'CARDHOLDER_FRAUD_ACTIVITY' => $this->strongCustomerAuthentication->getCardHolderFraudActivity(),
            'DEVICE_CHANNEL' => $this->strongCustomerAuthentication->getDeviceChannel(),
            'CHALLENGE_INDICATOR' => $this->strongCustomerAuthentication->getChallengeIndicator(),
            'CHALLENGE_WINDOW_SIZE' => $this->strongCustomerAuthentication->getChallengeWindowSize(),
            'ACCOUNT_ADDITIONAL_INFORMATION' => $this->strongCustomerAuthentication->getAccountAdditionalInformation(),
            'SDK_REFERENCE_NUMBER' => $this->strongCustomerAuthentication->getSdkReferenceNumber(),
            'SDK_MAXIMUM_TIMEOUT' => $this->strongCustomerAuthentication->getSdkMaximumTimeout(),
            'SDK_APPLICATION_ID' => $this->strongCustomerAuthentication->getSdkApplicationId(),
            'SDK_ENC_DATA' => $this->strongCustomerAuthentication->getSdkEncData(),
            'SDK_TRANS_ID' => $this->strongCustomerAuthentication->getSdkTransId(),
            'SDK_EPHEMERAL_PUB_KEY' => $this->strongCustomerAuthentication->getSdkEphemeralPubKey(),
            'SDK_UI_TYPE' => $this->strongCustomerAuthentication->getSdkUiType(),
            'SDK_INTERFACE' => $this->strongCustomerAuthentication->getSdkInterface(),
            'TRANSACTION_TYPE' => $this->strongCustomerAuthentication->getTransactionType(),
            'SHIPPING_INDICATOR' => $this->strongCustomerAuthentication->getShippingIndicator(),
            'PREORDER_INDICATOR' => $this->strongCustomerAuthentication->getPreOrderIndicator(),
            'PREORDER_DATE' => $this->strongCustomerAuthentication->getPreOrderDate(),
            'DELIVERY_TIME_FRAME' => $this->strongCustomerAuthentication->getDeliveryTimeFrame(),
            'REORDER_INDICATOR' => $this->strongCustomerAuthentication->getReOrderIndicator(),
            'MERCHANT_FUNDS_AMOUNT' => $this->strongCustomerAuthentication->getMerchantFundsAmount(),
            'MERCHANT_FUNDS_CURRENCY' => $this->strongCustomerAuthentication->getMerchantFundsCurrency(),
            'RECURRING_FREQUENCY_DAYS' => $this->strongCustomerAuthentication->getRecurringFrequencyDays(),
            'RECURRING_EXPIRY_DATE' => $this->strongCustomerAuthentication->getRecurringExpiryDate(),
            'ACCOUNT_CREATE_DATE' => $this->strongCustomerAuthentication->getAccountCreateDate(),
            'ACCOUNT_DELIVERY_ADDRESS_FIRST_USED_DATE' =>
                $this->strongCustomerAuthentication->getAccountCreateDate(),
            'ACCOUNT_DELIVERY_ADDRESS_USAGE_INDICATOR' =>
                $this->strongCustomerAuthentication->getAccountDeliveryAddressUsageIndicator(),
            'ACCOUNT_NUMBER_OF_TRANSACTIONS_LAST_YEAR' =>
                $this->strongCustomerAuthentication->getAccountNumberOfTransactionsLastYear(),
            'ACCOUNT_NUMBER_OF_TRANSACTIONS_LAST_DAY' =>
                $this->strongCustomerAuthentication->getAccountNumberOfTransactionsLastDay(),
            'ACCOUNT_NUMBER_OF_PURCHASES_LAST_SIX_MONTHS' =>
                $this->strongCustomerAuthentication->getAccountNumberOfPurchasesLastSixMonths(),
            'ACCOUNT_CHANGE_DATE' => $this->strongCustomerAuthentication->getAccountChangeDate(),
            'ACCOUNT_CHANGE_INDICATOR' =>
                $this->strongCustomerAuthentication->getAccountChangeIndicator(),
            'ACCOUNT_AGE_INDICATOR' => $this->strongCustomerAuthentication->getAccountAgeIndicator(),
            'ACCOUNT_PASSWORD_CHANGED_DATE' => $this->strongCustomerAuthentication->getAccountPasswordChangedDate(),
            'ACCOUNT_PASSWORD_CHANGED_INDICATOR' =>
                $this->strongCustomerAuthentication->getAccountPasswordChangedIndicator(),
            'ACCOUNT_NAME_TO_RECIPIENT_MATCH' => $this->strongCustomerAuthentication->getAccountNameToRecipientMatch(),
            'ACCOUNT_ADD_CARD_ATTEMPTS_DAY' => $this->strongCustomerAuthentication->getAccountAddCardAttemptsDay(),
            'ACCOUNT_AUTH_METHOD' => $this->strongCustomerAuthentication->getAccountAuthMethod(),
            'ACCOUNT_AUTH_DATETIME' => $this->strongCustomerAuthentication->getAccountAuthDateTime(),
            'REQUESTOR_AUTHENTICATION_DATA' => $this->strongCustomerAuthentication->getRequestorAuthenticationData(),
            'ACCOUNT_CARD_ADDED_INDICATOR' => $this->strongCustomerAuthentication->getAccountCardAddedIndicator(),
            'ACCOUNT_CARD_ADDED_DATE' => $this->strongCustomerAuthentication->getAccountCardAddedDate()
        );
    }

    /**
     * @return array
     */
    public function getRequestParams()
    {
        if (empty($this->internalArray)) {
            return $this->transformObject2Array();
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

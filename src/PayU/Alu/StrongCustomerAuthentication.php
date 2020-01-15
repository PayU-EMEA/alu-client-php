<?php

namespace PayU\Alu;

class StrongCustomerAuthentication
{
    /**
     * @var $strongCustomerAuthentication
     */
    private $strongCustomerAuthentication;
    /**
     * @var $addressMatch
     */
    private $addressMatch;
    /**
     * @var $browserAcceptHeaders
     */
    private $browserAcceptHeaders;
    /**
     * @var $browserIP
     */
    private $browserIP;
    /**
     * @var $browserJavaEnabled
     */
    private $browserJavaEnabled;
    /**
     * @var $browserLanguage
     */
    private $browserLanguage;
    /**
     * @var $browserColorDepth
     */
    private $browserColorDepth;
    /**
     * @var $browserScreenHeight
     */
    private $browserScreenHeight;
    /**
     * @var $browserScreenWidth
     */
    private $browserScreenWidth;
    /**
     * @var $browserTimezone
     */
    private $browserTimezone;
    /**
     * @var $browserUserAgent
     */
    private $browserUserAgent;
    /**
     * @var $billAddress3
     */
    private $billAddress3;
    /**
     * @var $billStateCode
     */
    private $billStateCode;
    /**
     * @var $homePhoneCountryPrefix
     */
    private $homePhoneCountryPrefix;
    /**
     * @var $homePhoneSubscriber
     */
    private $homePhoneSubscriber;
    /**
     * @var $mobilePhoneCountryPrefix
     */
    private $mobilePhoneCountryPrefix;
    /**
     * @var $mobilePhoneSubscriber
     */
    private $mobilePhoneSubscriber;
    /**
     * @var $workPhoneCountryPrefix
     */
    private $workPhoneCountryPrefix;
    /**
     * @var $workPhoneSubscriber
     */
    private $workPhoneSubscriber;

    /** @var $deliveryAddress3 */
    private $deliveryAddress3;
    /**
     * @var $deliveryStateCode
     */
    private $deliveryStateCode;
    /**
     * @var $cardHolderFraudActivity
     */
    private $cardHolderFraudActivity;
    /**
     * @var $deviceChannel
     */
    private $deviceChannel;
    /**
     * @var $challengeIndicator
     */
    private $challengeIndicator;
    /**
     * @var $challengeWindowSize
     */
    private $challengeWindowSize;
    /**
     * @var $accountAdditionalInformation
     */
    private $accountAdditionalInformation;
    /**
     * @var $sdkReferenceNumber
     */
    private $sdkReferenceNumber;
    /**
     * @var $sdkMaximumTimeout
     */
    private $sdkMaximumTimeout;
    /**
     * @var $sdkApplicationId
     */
    private $sdkApplicationId;
    /**
     * @var $sdkEncData
     */
    private $sdkEncData;
    /**
     * @var $sdkTransId
     */
    private $sdkTransId;
    /**
     * @var $sdkEphemeralPubKey
     */
    private $sdkEphemeralPubKey;
    /**
     * @var $sdkUiType
     */
    private $sdkUiType;
    /**
     * @var $sdkInterface
     */
    private $sdkInterface;
    /**
     * @var $transactionType
     */
    private $transactionType;
    /**
     * @var $shippingIndicator
     */
    private $shippingIndicator;
    /**
     * @var $preOrderIndicator
     */
    private $preOrderIndicator;
    /**
     * @var $preOrderDate
     */
    private $preOrderDate;
    /**
     * @var $deliveryTimeFrame
     */
    private $deliveryTimeFrame;
    /**
     * @var $reOrderIndicator
     */
    private $reOrderIndicator;
    /**
     * @var $merchantFundsAmount
     */
    private $merchantFundsAmount;
    /**
     * @var $merchantFundsCurrency
     */
    private $merchantFundsCurrency;
    /**
     * @var $recurringFrequencyDays
     */
    private $recurringFrequencyDays;
    /**
     * @var $recurringExpiryDate
     */
    private $recurringExpiryDate;
    /**
     * @var $accountCreateDate
     */
    private $accountCreateDate;
    /**
     * @var $accountDeliveryAddressFirstUsedDate
     */
    private $accountDeliveryAddressFirstUsedDate;
    /**
     * @var $accountDeliveryAddressUsageIndicator
     */
    private $accountDeliveryAddressUsageIndicator;
    /**
     * @var $accountNumberOfTransactionsLastYear
     */
    private $accountNumberOfTransactionsLastYear;
    /**
     * @var $accountNumberOfTransactionsLastDay
     */
    private $accountNumberOfTransactionsLastDay;
    /**
     * @var $accountNumberOfPurchasesLastSixMonths
     */
    private $accountNumberOfPurchasesLastSixMonths;
    /**
     * @var $accountChangeDate
     */
    private $accountChangeDate;
    /**
     * @var $accountChangeIndicator
     */
    private $accountChangeIndicator;
    /**
     * @var $accountAgeIndicator
     */
    private $accountAgeIndicator;
    /**
     * @var $accountPasswordChangedDate
     */
    private $accountPasswordChangedDate;
    /**
     * @var $accountPasswordChangedIndicator
     */
    private $accountPasswordChangedIndicator;
    /**
     * @var $accountNameToRecipientMatch
     */
    private $accountNameToRecipientMatch;
    /**
     * @var $accountAddCardAttemptsDay
     */
    private $accountAddCardAttemptsDay;
    /**
     * @var $accountAuthMethod
     */
    private $accountAuthMethod;
    /**
     * @var $accountAuthDateTime
     */
    private $accountAuthDateTime;
    /**
     * @var $requestorAuthenticationData
     */
    private $requestorAuthenticationData;
    /**
     * @var $accountCardAddedIndicator
     */
    private $accountCardAddedIndicator;
    /**
     * @var $accountCardAddedDate
     */
    private $accountCardAddedDate;

    /**
     * @return mixed
     */
    public function getStrongCustomerAuthentication()
    {
        return $this->strongCustomerAuthentication;
    }

    /**
     * @param mixed $strongCustomerAuthentication
     * @return StrongCustomerAuthentication
     */
    public function setStrongCustomerAuthentication($strongCustomerAuthentication)
    {
        $this->strongCustomerAuthentication = $strongCustomerAuthentication;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getAddressMatch()
    {
        return $this->addressMatch;
    }

    /**
     * @param mixed $addressMatch
     * @return StrongCustomerAuthentication
     */
    public function setAddressMatch($addressMatch)
    {
        $this->addressMatch = $addressMatch;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getBrowserAcceptHeaders()
    {
        return $this->browserAcceptHeaders;
    }

    /**
     * @param mixed $browserAcceptHeaders
     * @return StrongCustomerAuthentication
     */
    public function setBrowserAcceptHeaders($browserAcceptHeaders)
    {
        $this->browserAcceptHeaders = $browserAcceptHeaders;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getBrowserIP()
    {
        return $this->browserIP;
    }

    /**
     * @param mixed $browserIP
     * @return StrongCustomerAuthentication
     */
    public function setBrowserIP($browserIP)
    {
        $this->browserIP = $browserIP;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getBrowserJavaEnabled()
    {
        return $this->browserJavaEnabled;
    }

    /**
     * @return mixed
     */
    public function getDeliveryAddress3()
    {
        return $this->deliveryAddress3;
    }

    /**
     * @param mixed $deliveryAddress3
     * @return StrongCustomerAuthentication
     */
    public function setDeliveryAddress3($deliveryAddress3)
    {
        $this->deliveryAddress3 = $deliveryAddress3;

        return $this;
    }

    /**
     * @param mixed $browserJavaEnabled
     * @return StrongCustomerAuthentication
     */
    public function setBrowserJavaEnabled($browserJavaEnabled)
    {
        $this->browserJavaEnabled = $browserJavaEnabled;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getBrowserLanguage()
    {
        return $this->browserLanguage;
    }

    /**
     * @param mixed $browserLanguage
     * @return StrongCustomerAuthentication
     */
    public function setBrowserLanguage($browserLanguage)
    {
        $this->browserLanguage = $browserLanguage;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getBrowserColorDepth()
    {
        return $this->browserColorDepth;
    }

    /**
     * @param mixed $browserColorDepth
     * @return StrongCustomerAuthentication
     */
    public function setBrowserColorDepth($browserColorDepth)
    {
        $this->browserColorDepth = $browserColorDepth;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getBrowserScreenHeight()
    {
        return $this->browserScreenHeight;
    }

    /**
     * @param mixed $browserScreenHeight
     * @return StrongCustomerAuthentication
     */
    public function setBrowserScreenHeight($browserScreenHeight)
    {
        $this->browserScreenHeight = $browserScreenHeight;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getBrowserScreenWidth()
    {
        return $this->browserScreenWidth;
    }

    /**
     * @param mixed $browserScreenWidth
     * @return StrongCustomerAuthentication
     */
    public function setBrowserScreenWidth($browserScreenWidth)
    {
        $this->browserScreenWidth = $browserScreenWidth;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getBrowserTimezone()
    {
        return $this->browserTimezone;
    }

    /**
     * @param mixed $browserTimezone
     * @return StrongCustomerAuthentication
     */
    public function setBrowserTimezone($browserTimezone)
    {
        $this->browserTimezone = $browserTimezone;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getBrowserUserAgent()
    {
        return $this->browserUserAgent;
    }

    /**
     * @param mixed $browserUserAgent
     * @return StrongCustomerAuthentication
     */
    public function setBrowserUserAgent($browserUserAgent)
    {
        $this->browserUserAgent = $browserUserAgent;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getBillAddress3()
    {
        return $this->billAddress3;
    }

    /**
     * @param mixed $billAddress3
     * @return StrongCustomerAuthentication
     */
    public function setBillAddress3($billAddress3)
    {
        $this->billAddress3 = $billAddress3;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getBillStateCode()
    {
        return $this->billStateCode;
    }

    /**
     * @param mixed $billStateCode
     * @return StrongCustomerAuthentication
     */
    public function setBillStateCode($billStateCode)
    {
        $this->billStateCode = $billStateCode;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getHomePhoneCountryPrefix()
    {
        return $this->homePhoneCountryPrefix;
    }

    /**
     * @param mixed $homePhoneCountryPrefix
     * @return StrongCustomerAuthentication
     */
    public function setHomePhoneCountryPrefix($homePhoneCountryPrefix)
    {
        $this->homePhoneCountryPrefix = $homePhoneCountryPrefix;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getHomePhoneSubscriber()
    {
        return $this->homePhoneSubscriber;
    }

    /**
     * @param mixed $homePhoneSubscriber
     * @return StrongCustomerAuthentication
     */
    public function setHomePhoneSubscriber($homePhoneSubscriber)
    {
        $this->homePhoneSubscriber = $homePhoneSubscriber;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getMobilePhoneCountryPrefix()
    {
        return $this->mobilePhoneCountryPrefix;
    }

    /**
     * @param mixed $mobilePhoneCountryPrefix
     * @return StrongCustomerAuthentication
     */
    public function setMobilePhoneCountryPrefix($mobilePhoneCountryPrefix)
    {
        $this->mobilePhoneCountryPrefix = $mobilePhoneCountryPrefix;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getMobilePhoneSubscriber()
    {
        return $this->mobilePhoneSubscriber;
    }

    /**
     * @param mixed $mobilePhoneSubscriber
     * @return StrongCustomerAuthentication
     */
    public function setMobilePhoneSubscriber($mobilePhoneSubscriber)
    {
        $this->mobilePhoneSubscriber = $mobilePhoneSubscriber;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getWorkPhoneCountryPrefix()
    {
        return $this->workPhoneCountryPrefix;
    }

    /**
     * @param mixed $workPhoneCountryPrefix
     * @return StrongCustomerAuthentication
     */
    public function setWorkPhoneCountryPrefix($workPhoneCountryPrefix)
    {
        $this->workPhoneCountryPrefix = $workPhoneCountryPrefix;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getWorkPhoneSubscriber()
    {
        return $this->workPhoneSubscriber;
    }

    /**
     * @param mixed $workPhoneSubscriber
     * @return StrongCustomerAuthentication
     */
    public function setWorkPhoneSubscriber($workPhoneSubscriber)
    {
        $this->workPhoneSubscriber = $workPhoneSubscriber;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDeliveryStateCode()
    {
        return $this->deliveryStateCode;
    }

    /**
     * @param mixed $deliveryStateCode
     * @return StrongCustomerAuthentication
     */
    public function setDeliveryStateCode($deliveryStateCode)
    {
        $this->deliveryStateCode = $deliveryStateCode;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCardHolderFraudActivity()
    {
        return $this->cardHolderFraudActivity;
    }

    /**
     * @param mixed $cardHolderFraudActivity
     * @return StrongCustomerAuthentication
     */
    public function setCardHolderFraudActivity($cardHolderFraudActivity)
    {
        $this->cardHolderFraudActivity = $cardHolderFraudActivity;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDeviceChannel()
    {
        return $this->deviceChannel;
    }

    /**
     * @param mixed $deviceChannel
     * @return StrongCustomerAuthentication
     */
    public function setDeviceChannel($deviceChannel)
    {
        $this->deviceChannel = $deviceChannel;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getChallengeIndicator()
    {
        return $this->challengeIndicator;
    }

    /**
     * @param mixed $challengeIndicator
     * @return StrongCustomerAuthentication
     */
    public function setChallengeIndicator($challengeIndicator)
    {
        $this->challengeIndicator = $challengeIndicator;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getChallengeWindowSize()
    {
        return $this->challengeWindowSize;
    }

    /**
     * @param mixed $challengeWindowSize
     * @return StrongCustomerAuthentication
     */
    public function setChallengeWindowSize($challengeWindowSize)
    {
        $this->challengeWindowSize = $challengeWindowSize;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getAccountAdditionalInformation()
    {
        return $this->accountAdditionalInformation;
    }

    /**
     * @param mixed $accountAdditionalInformation
     * @return StrongCustomerAuthentication
     */
    public function setAccountAdditionalInformation($accountAdditionalInformation)
    {
        $this->accountAdditionalInformation = $accountAdditionalInformation;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getSdkReferenceNumber()
    {
        return $this->sdkReferenceNumber;
    }

    /**
     * @param mixed $sdkReferenceNumber
     * @return StrongCustomerAuthentication
     */
    public function setSdkReferenceNumber($sdkReferenceNumber)
    {
        $this->sdkReferenceNumber = $sdkReferenceNumber;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getSdkMaximumTimeout()
    {
        return $this->sdkMaximumTimeout;
    }

    /**
     * @param mixed $sdkMaximumTimeout
     * @return StrongCustomerAuthentication
     */
    public function setSdkMaximumTimeout($sdkMaximumTimeout)
    {
        $this->sdkMaximumTimeout = $sdkMaximumTimeout;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getSdkApplicationId()
    {
        return $this->sdkApplicationId;
    }

    /**
     * @param mixed $sdkApplicationId
     * @return StrongCustomerAuthentication
     */
    public function setSdkApplicationId($sdkApplicationId)
    {
        $this->sdkApplicationId = $sdkApplicationId;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getSdkEncData()
    {
        return $this->sdkEncData;
    }

    /**
     * @param mixed $sdkEncData
     * @return StrongCustomerAuthentication
     */
    public function setSdkEncData($sdkEncData)
    {
        $this->sdkEncData = $sdkEncData;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getSdkTransId()
    {
        return $this->sdkTransId;
    }

    /**
     * @param mixed $sdkTransId
     * @return StrongCustomerAuthentication
     */
    public function setSdkTransId($sdkTransId)
    {
        $this->sdkTransId = $sdkTransId;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getSdkEphemeralPubKey()
    {
        return $this->sdkEphemeralPubKey;
    }

    /**
     * @param mixed $sdkEphemeralPubKey
     * @return StrongCustomerAuthentication
     */
    public function setSdkEphemeralPubKey($sdkEphemeralPubKey)
    {
        $this->sdkEphemeralPubKey = $sdkEphemeralPubKey;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getSdkUiType()
    {
        return $this->sdkUiType;
    }

    /**
     * @param mixed $sdkUiType
     * @return StrongCustomerAuthentication
     */
    public function setSdkUiType($sdkUiType)
    {
        $this->sdkUiType = $sdkUiType;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getSdkInterface()
    {
        return $this->sdkInterface;
    }

    /**
     * @param mixed $sdkInterface
     * @return StrongCustomerAuthentication
     */
    public function setSdkInterface($sdkInterface)
    {
        $this->sdkInterface = $sdkInterface;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getTransactionType()
    {
        return $this->transactionType;
    }

    /**
     * @param mixed $transactionType
     * @return StrongCustomerAuthentication
     */
    public function setTransactionType($transactionType)
    {
        $this->transactionType = $transactionType;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getShippingIndicator()
    {
        return $this->shippingIndicator;
    }

    /**
     * @param mixed $shippingIndicator
     * @return StrongCustomerAuthentication
     */
    public function setShippingIndicator($shippingIndicator)
    {
        $this->shippingIndicator = $shippingIndicator;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPreOrderIndicator()
    {
        return $this->preOrderIndicator;
    }

    /**
     * @param mixed $preOrderIndicator
     * @return StrongCustomerAuthentication
     */
    public function setPreOrderIndicator($preOrderIndicator)
    {
        $this->preOrderIndicator = $preOrderIndicator;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPreOrderDate()
    {
        return $this->preOrderDate;
    }

    /**
     * @param mixed $preOrderDate
     * @return StrongCustomerAuthentication
     */
    public function setPreOrderDate($preOrderDate)
    {
        $this->preOrderDate = $preOrderDate;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDeliveryTimeFrame()
    {
        return $this->deliveryTimeFrame;
    }

    /**
     * @param mixed $deliveryTimeFrame
     * @return StrongCustomerAuthentication
     */
    public function setDeliveryTimeFrame($deliveryTimeFrame)
    {
        $this->deliveryTimeFrame = $deliveryTimeFrame;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getReOrderIndicator()
    {
        return $this->reOrderIndicator;
    }

    /**
     * @param mixed $reOrderIndicator
     * @return StrongCustomerAuthentication
     */
    public function setReOrderIndicator($reOrderIndicator)
    {
        $this->reOrderIndicator = $reOrderIndicator;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getMerchantFundsAmount()
    {
        return $this->merchantFundsAmount;
    }

    /**
     * @param mixed $merchantFundsAmount
     * @return StrongCustomerAuthentication
     */
    public function setMerchantFundsAmount($merchantFundsAmount)
    {
        $this->merchantFundsAmount = $merchantFundsAmount;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getMerchantFundsCurrency()
    {
        return $this->merchantFundsCurrency;
    }

    /**
     * @param $merchantFundsCurrency
     * @return $this
     */
    public function setMerchantFundsCurrency($merchantFundsCurrency)
    {
        $this->merchantFundsCurrency = $merchantFundsCurrency;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getRecurringFrequencyDays()
    {
        return $this->recurringFrequencyDays;
    }

    /**
     * @param mixed $recurringFrequencyDays
     * @return StrongCustomerAuthentication
     */
    public function setRecurringFrequencyDays($recurringFrequencyDays)
    {
        $this->recurringFrequencyDays = $recurringFrequencyDays;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getRecurringExpiryDate()
    {
        return $this->recurringExpiryDate;
    }

    /**
     * @param mixed $recurringExpiryDate
     * @return StrongCustomerAuthentication
     */
    public function setRecurringExpiryDate($recurringExpiryDate)
    {
        $this->recurringExpiryDate = $recurringExpiryDate;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getAccountCreateDate()
    {
        return $this->accountCreateDate;
    }

    /**
     * @param mixed $accountCreateDate
     * @return StrongCustomerAuthentication
     */
    public function setAccountCreateDate($accountCreateDate)
    {
        $this->accountCreateDate = $accountCreateDate;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getAccountDeliveryAddressFirstUsedDate()
    {
        return $this->accountDeliveryAddressFirstUsedDate;
    }

    /**
     * @param mixed $accountDeliveryAddressFirstUsedDate
     * @return StrongCustomerAuthentication
     */
    public function setAccountDeliveryAddressFirstUsedDate($accountDeliveryAddressFirstUsedDate)
    {
        $this->accountDeliveryAddressFirstUsedDate = $accountDeliveryAddressFirstUsedDate;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getAccountDeliveryAddressUsageIndicator()
    {
        return $this->accountDeliveryAddressUsageIndicator;
    }

    /**
     * @param mixed $accountDeliveryAddressUsageIndicator
     * @return StrongCustomerAuthentication
     */
    public function setAccountDeliveryAddressUsageIndicator($accountDeliveryAddressUsageIndicator)
    {
        $this->accountDeliveryAddressUsageIndicator = $accountDeliveryAddressUsageIndicator;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getAccountNumberOfTransactionsLastYear()
    {
        return $this->accountNumberOfTransactionsLastYear;
    }

    /**
     * @param mixed $accountNumberOfTransactionsLastYear
     * @return StrongCustomerAuthentication
     */
    public function setAccountNumberOfTransactionsLastYear($accountNumberOfTransactionsLastYear)
    {
        $this->accountNumberOfTransactionsLastYear = $accountNumberOfTransactionsLastYear;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getAccountNumberOfTransactionsLastDay()
    {
        return $this->accountNumberOfTransactionsLastDay;
    }

    /**
     * @param mixed $accountNumberOfTransactionsLastDay
     * @return StrongCustomerAuthentication
     */
    public function setAccountNumberOfTransactionsLastDay($accountNumberOfTransactionsLastDay)
    {
        $this->accountNumberOfTransactionsLastDay = $accountNumberOfTransactionsLastDay;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getAccountNumberOfPurchasesLastSixMonths()
    {
        return $this->accountNumberOfPurchasesLastSixMonths;
    }

    /**
     * @param mixed $accountNumberOfPurchasesLastSixMonths
     * @return StrongCustomerAuthentication
     */
    public function setAccountNumberOfPurchasesLastSixMonths($accountNumberOfPurchasesLastSixMonths)
    {
        $this->accountNumberOfPurchasesLastSixMonths = $accountNumberOfPurchasesLastSixMonths;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getAccountChangeDate()
    {
        return $this->accountChangeDate;
    }

    /**
     * @param $accountChangeDate
     * @return StrongCustomerAuthentication
     */
    public function setAccountChangeDate($accountChangeDate)
    {
        $this->accountChangeDate = $accountChangeDate;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getAccountChangeIndicator()
    {
        return $this->accountChangeIndicator;
    }

    /**
     * @param mixed $accountChangeIndicator
     * @return StrongCustomerAuthentication
     */
    public function setAccountChangeIndicator($accountChangeIndicator)
    {
        $this->accountChangeIndicator = $accountChangeIndicator;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getAccountAgeIndicator()
    {
        return $this->accountAgeIndicator;
    }

    /**
     * @param mixed $accountAgeIndicator
     * @return StrongCustomerAuthentication
     */
    public function setAccountAgeIndicator($accountAgeIndicator)
    {
        $this->accountAgeIndicator = $accountAgeIndicator;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getAccountPasswordChangedDate()
    {
        return $this->accountPasswordChangedDate;
    }

    /**
     * @param mixed $accountPasswordChangedDate
     * @return StrongCustomerAuthentication
     */
    public function setAccountPasswordChangedDate($accountPasswordChangedDate)
    {
        $this->accountPasswordChangedDate = $accountPasswordChangedDate;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getAccountPasswordChangedIndicator()
    {
        return $this->accountPasswordChangedIndicator;
    }

    /**
     * @param mixed $accountPasswordChangedIndicator
     * @return StrongCustomerAuthentication
     */
    public function setAccountPasswordChangedIndicator($accountPasswordChangedIndicator)
    {
        $this->accountPasswordChangedIndicator = $accountPasswordChangedIndicator;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getAccountNameToRecipientMatch()
    {
        return $this->accountNameToRecipientMatch;
    }

    /**
     * @param mixed $accountNameToRecipientMatch
     * @return StrongCustomerAuthentication
     */
    public function setAccountNameToRecipientMatch($accountNameToRecipientMatch)
    {
        $this->accountNameToRecipientMatch = $accountNameToRecipientMatch;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getAccountAddCardAttemptsDay()
    {
        return $this->accountAddCardAttemptsDay;
    }

    /**
     * @param mixed $accountAddCardAttemptsDay
     * @return StrongCustomerAuthentication
     */
    public function setAccountAddCardAttemptsDay($accountAddCardAttemptsDay)
    {
        $this->accountAddCardAttemptsDay = $accountAddCardAttemptsDay;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getAccountAuthMethod()
    {
        return $this->accountAuthMethod;
    }

    /**
     * @param mixed $accountAuthMethod
     * @return StrongCustomerAuthentication
     */
    public function setAccountAuthMethod($accountAuthMethod)
    {
        $this->accountAuthMethod = $accountAuthMethod;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getAccountAuthDateTime()
    {
        return $this->accountAuthDateTime;
    }

    /**
     * @param mixed $accountAuthDateTime
     * @return StrongCustomerAuthentication
     */
    public function setAccountAuthDateTime($accountAuthDateTime)
    {
        $this->accountAuthDateTime = $accountAuthDateTime;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getRequestorAuthenticationData()
    {
        return $this->requestorAuthenticationData;
    }

    /**
     * @param mixed $requestorAuthenticationData
     * @return StrongCustomerAuthentication
     */
    public function setRequestorAuthenticationData($requestorAuthenticationData)
    {
        $this->requestorAuthenticationData = $requestorAuthenticationData;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getAccountCardAddedIndicator()
    {
        return $this->accountCardAddedIndicator;
    }

    /**
     * @param mixed $accountCardAddedIndicator
     * @return StrongCustomerAuthentication
     */
    public function setAccountCardAddedIndicator($accountCardAddedIndicator)
    {
        $this->accountCardAddedIndicator = $accountCardAddedIndicator;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getAccountCardAddedDate()
    {
        return $this->accountCardAddedDate;
    }

    /**
     * @param mixed $accountCardAddedDate
     * @return StrongCustomerAuthentication
     */
    public function setAccountCardAddedDate($accountCardAddedDate)
    {
        $this->accountCardAddedDate = $accountCardAddedDate;

        return $this;
    }
}

<?php


namespace PaymentsApi\Entities;


class AuthorizationData implements \JsonSerializable
{
    /**
     * @var string
     */
    private $paymentMethod;

    /**
     * @var CardDetails
     */
    private $cardDetails;

    /**
     * @var MerchantTokenData
     */
    private $merchantToken;

    private $applePayToken;

    /**
     * @var string
     */
    private $userPaymentPage;

    /**
     * @var string
     */
    private $installmentsNumber;

    /**
     * @var string
     */
    private $useLoyaltyPoints;

    /**
     * @var int
     */
    private $loyaltyPointsAmount;

    /** @var string */
    private $campaignType;

    /**
     * @var FxData
     */
    private $fx;

    /**
     * AuthorizationData constructor.
     *
     * @param string $paymentMethod
     */
    public function __construct($paymentMethod)
    {
        $this->paymentMethod = $paymentMethod;
    }


    /**
     * @return string
     */
    public function getPaymentMethod()
    {
        return $this->paymentMethod;
    }

    /**
     * @param string $paymentMethod
     */
    public function setPaymentMethod($paymentMethod)
    {
        $this->paymentMethod = $paymentMethod;
    }

    /**
     * @return CardDetails
     */
    public function getCardDetails()
    {
        return $this->cardDetails;
    }

    /**
     * @param CardDetails $cardDetails
     */
    public function setCardDetails($cardDetails)
    {
        $this->cardDetails = $cardDetails;
    }

    /**
     * @return MerchantTokenData
     */
    public function getMerchantToken()
    {
        return $this->merchantToken;
    }

    /**
     * @param MerchantTokenData $merchantToken
     */
    public function setMerchantToken($merchantToken)
    {
        $this->merchantToken = $merchantToken;
    }

    /**
     * @return mixed
     */
    public function getApplePayToken()
    {
        return $this->applePayToken;
    }

    /**
     * @param mixed $applePayToken
     */
    public function setApplePayToken($applePayToken)
    {
        $this->applePayToken = $applePayToken;
    }

    /**
     * @return string
     */
    public function getUserPaymentPage()
    {
        return $this->userPaymentPage;
    }

    /**
     * @param string $userPaymentPage
     */
    public function setUserPaymentPage($userPaymentPage)
    {
        $this->userPaymentPage = $userPaymentPage;
    }

    /**
     * @return string
     */
    public function getInstallmentsNumber()
    {
        return $this->installmentsNumber;
    }

    /**
     * @param string $installmentsNumber
     */
    public function setInstallmentsNumber($installmentsNumber)
    {
        $this->installmentsNumber = $installmentsNumber;
    }

    /**
     * @return string
     */
    public function getUseLoyaltyPoints()
    {
        return $this->useLoyaltyPoints;
    }

    /**
     * @param string $useLoyaltyPoints
     */
    public function setUseLoyaltyPoints($useLoyaltyPoints)
    {
        $this->useLoyaltyPoints = $useLoyaltyPoints;
    }

    /**
     * @return int
     */
    public function getLoyaltyPointsAmount()
    {
        return $this->loyaltyPointsAmount;
    }

    /**
     * @param int $loyaltyPointsAmount
     */
    public function setLoyaltyPointsAmount($loyaltyPointsAmount)
    {
        $this->loyaltyPointsAmount = $loyaltyPointsAmount;
    }

    /**
     * @return string
     */
    public function getCampaignType()
    {
        return $this->campaignType;
    }

    /**
     * @param string $campaignType
     */
    public function setCampaignType($campaignType)
    {
        $this->campaignType = $campaignType;
    }

    /**
     * @return FxData
     */
    public function getFx()
    {
        return $this->fx;
    }

    /**
     * @param FxData $fx
     */
    public function setFx($fx)
    {
        $this->fx = $fx;
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize()
    {
        return [
            'paymentMethod' => $this->paymentMethod,
            'cardDetails' => $this->cardDetails,
            'merchantToken' => $this->merchantToken,
            //'applePayToken' => json_encode($this->applePayToken),
            'usePaymentPage' => $this->userPaymentPage,
            'installmentsNumber' => $this->installmentsNumber,
            'useLoyaltyPoints' => $this->useLoyaltyPoints,
            'loyaltyPointsAmount' => $this->loyaltyPointsAmount,
            'campaignType' => $this->campaignType,
            'fx' => $this->fx
        ];
    }
}
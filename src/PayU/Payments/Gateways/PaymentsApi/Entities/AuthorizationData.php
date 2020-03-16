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

    /**
     * @var ApplePayTokenData
     * todo create class
     */
    private $applePayToken;

    /**
     * @var string
     */
    private $usePaymentPage;

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
     * @param CardDetails $cardDetails
     */
    public function setCardDetails($cardDetails)
    {
        $this->cardDetails = $cardDetails;
    }

    /**
     * @param MerchantTokenData $merchantToken
     */
    public function setMerchantToken($merchantToken)
    {
        $this->merchantToken = $merchantToken;
    }

    /**
     * @param mixed $applePayToken
     */
    public function setApplePayToken($applePayToken)
    {
        $this->applePayToken = $applePayToken;
    }

    /**
     * @param string $usePaymentPage
     */
    public function setUsePaymentPage($usePaymentPage)
    {
        $this->usePaymentPage = $usePaymentPage;
    }

    /**
     * @param string $installmentsNumber
     */
    public function setInstallmentsNumber($installmentsNumber)
    {
        $this->installmentsNumber = $installmentsNumber;
    }

    /**
     * @param string $useLoyaltyPoints
     */
    public function setUseLoyaltyPoints($useLoyaltyPoints)
    {
        $this->useLoyaltyPoints = $useLoyaltyPoints;
    }

    /**
     * @param int $loyaltyPointsAmount
     */
    public function setLoyaltyPointsAmount($loyaltyPointsAmount)
    {
        $this->loyaltyPointsAmount = $loyaltyPointsAmount;
    }

    /**
     * @param string $campaignType
     */
    public function setCampaignType($campaignType)
    {
        $this->campaignType = $campaignType;
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
            'usePaymentPage' => $this->usePaymentPage,
            'installmentsNumber' => $this->installmentsNumber,
            'useLoyaltyPoints' => $this->useLoyaltyPoints,
            'loyaltyPointsAmount' => $this->loyaltyPointsAmount,
            'campaignType' => $this->campaignType,
            'fx' => $this->fx
        ];
    }
}

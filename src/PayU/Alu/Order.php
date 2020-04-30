<?php

namespace PayU\Alu;

class Order
{
    /**
     * @var Product[]
     */
    private $products = array();

    /**
     * @var string
     */
    private $orderRef;

    /**
     * @var string
     */
    private $alias;

    /**
     * @var string
     */
    private $orderDate;

    /**
     * @var float
     */
    private $shippingCost;

    /**
     * @var string
     */
    private $currency;

    /**
     * @var float
     */
    private $discount;

    /**
     * @var string
     */
    private $payMethod;

    /**
     * @var string
     */
    private $backRef;

    /**
     * @var integer
     */
    private $installmentsNumber;

    /**
     * @var string
     */
    private $cardProgramName;

    /**
     * @var boolean
     */
    private $useLoyaltyPoints;

    /**
     * @var integer
     */
    private $orderTimeout;

    /**
     * @var string[]
     */
    private $customParams = array();

    /**
     * @var boolean
     */
    private $luEnabledToken;

    /**
     * @var string
     */
    private $luTokenType;

    /**
     * @var string
     */
    private $ccNumberRecipient;

    /**
     * @var float
     */
    private $loyaltyPointsAmount;

    /**
     * @var string
     */
    private $campaignType;

    /**
     * @var AirlineInfo
     */
    private $airlineInfo;

    /** @var string */
    private $usePaymentPage;

    /**
     * @param $usePaymentPage
     * @return $this
     */
    public function withUsePaymentPage($usePaymentPage)
    {
        $this->usePaymentPage = $usePaymentPage;
        return $this;
    }

    /**
     * @return string
     */
    public function getUsePaymentPage()
    {
        return $this->usePaymentPage;
    }

    /**
     * @param string $name
     * @param string $value
     * @return $this
     */
    public function withCustomParam($name, $value)
    {
        $this->customParams[(string)$name] = (string)$value;
        return $this;
    }

    /**
     * @param string $alias
     * @return $this
     */
    public function withAlias($alias)
    {
        $this->alias = $alias;
        return $this;
    }

    /**
     * @return string
     */
    public function getAlias()
    {
        return $this->alias;
    }

    /**
     * @param string $backRef
     * @return $this
     */
    public function withBackRef($backRef)
    {
        $this->backRef = $backRef;
        return $this;
    }

    /**
     * @return string
     */
    public function getBackRef()
    {
        return $this->backRef;
    }

    /**
     * @param string $cardProgramName
     * @return $this
     */
    public function withCardProgramName($cardProgramName)
    {
        $this->cardProgramName = $cardProgramName;
        return $this;
    }

    /**
     * @return string
     */
    public function getCardProgramName()
    {
        return $this->cardProgramName;
    }

    /**
     * @param string $currency
     * @return $this
     */
    public function withCurrency($currency)
    {
        $this->currency = $currency;
        return $this;
    }

    /**
     * @return string
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @param array $customParams
     * @return $this
     */
    public function withCustomParams($customParams)
    {
        $this->customParams = $customParams;
        return $this;
    }

    /**
     * @return array
     */
    public function getCustomParams()
    {
        return $this->customParams;
    }

    /**
     * @param float $discount
     * @return $this
     */
    public function withDiscount($discount)
    {
        $this->discount = $discount;
        return $this;
    }

    /**
     * @return float
     */
    public function getDiscount()
    {
        return $this->discount;
    }

    /**
     * @param int $installmentsNumber
     * @return $this
     */
    public function withInstallmentsNumber($installmentsNumber)
    {
        $this->installmentsNumber = $installmentsNumber;
        return $this;
    }

    /**
     * @return int
     */
    public function getInstallmentsNumber()
    {
        return $this->installmentsNumber;
    }

    /**
     * @deprecated This method will be removed in a later version.
     * @deprecated You should use Card::enableTokenCreation() to enable token creation.
     * @param boolean $luEnabledToken
     * @return $this
     */
    public function withLuEnabledToken($luEnabledToken)
    {
        $this->luEnabledToken = $luEnabledToken;
        return $this;
    }

    /**
     * @deprecated see withLuEnabledToken()
     * @return boolean
     */
    public function getLuEnabledToken()
    {
        return $this->luEnabledToken;
    }

    /**
     * @deprecated see withLuEnabledToken()
     * @param string $luTokenType
     * @return $this
     */
    public function withLuTokenType($luTokenType)
    {
        $this->luTokenType = $luTokenType;
        return $this;
    }

    /**
     * @deprecated see withLuEnabledToken()
     * @return string
     */
    public function getLuTokenType()
    {
        return $this->luTokenType;
    }

    /**
     * @param string $orderDate
     * @return $this
     */
    public function withOrderDate($orderDate)
    {
        $this->orderDate = $orderDate;
        return $this;
    }

    /**
     * @return string
     */
    public function getOrderDate()
    {
        return $this->orderDate;
    }

    /**
     * @param string $orderRef
     * @return $this
     */
    public function withOrderRef($orderRef)
    {
        $this->orderRef = $orderRef;
        return $this;
    }

    /**
     * @return string
     */
    public function getOrderRef()
    {
        return $this->orderRef;
    }

    /**
     * @param int $orderTimeout
     * @return $this
     */
    public function withOrderTimeout($orderTimeout)
    {
        $this->orderTimeout = $orderTimeout;
        return $this;
    }

    /**
     * @return int
     */
    public function getOrderTimeout()
    {
        return $this->orderTimeout;
    }

    /**
     * @param string $payMethod
     * @return $this
     */
    public function withPayMethod($payMethod)
    {
        $this->payMethod = $payMethod;
        return $this;
    }

    /**
     * @return string
     */
    public function getPayMethod()
    {
        return $this->payMethod;
    }

    /**
     * @param Product $product
     * @return $this
     */
    public function addProduct(Product $product)
    {
        $this->products[] = $product;
        return $this;
    }

    /**
     * @return array
     */
    public function getProducts()
    {
        return $this->products;
    }

    /**
     * @param float $shippingCost
     * @return $this
     */
    public function withShippingCost($shippingCost)
    {
        $this->shippingCost = $shippingCost;
        return $this;
    }

    /**
     * @return float
     */
    public function getShippingCost()
    {
        return $this->shippingCost;
    }

    /**
     * @param boolean $useLoyaltyPoints
     * @return $this
     */
    public function withUseLoyaltyPoints($useLoyaltyPoints)
    {
        $this->useLoyaltyPoints = $useLoyaltyPoints;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getUseLoyaltyPoints()
    {
        return $this->useLoyaltyPoints;
    }

    /**
     * @param string $ccNumberRecipient
     * @return $this
     */
    public function withCcNumberRecipient($ccNumberRecipient)
    {
        $this->ccNumberRecipient = $ccNumberRecipient;
        return $this;
    }

    /**
     * @return string
     */
    public function getCcNumberRecipient()
    {
        return $this->ccNumberRecipient;
    }

    /**
     * @see Order::withMultipleLoyaltyPointsAmount() for multiple loyalty points.
     * @param float $loyaltyPointsAmount
     * @return $this
     */
    public function withLoyaltyPointsAmount($loyaltyPointsAmount)
    {
        $this->loyaltyPointsAmount = $loyaltyPointsAmount;
        return $this;
    }

    /**
     * @param [] $loyaltyPointsAmounts - can be used in case of card support multiple Loyalty Points Types.
     * For Example Garanti on Turkey supports BNS and FBB.
     * So you can use like ['BNS' =>34,'FBB'=>20] or ['FBB'=>20] or ['BNS'=>10].
     * @return $this
     */
    public function withMultipleLoyaltyPointsAmount($loyaltyPointsAmounts)
    {
        foreach ($loyaltyPointsAmounts as $key => $value) {
            $this->loyaltyPointsAmount[$key] = $value;
        }
        return $this;
    }

    /**
     * @return float|array
     */
    public function getLoyaltyPointsAmount()
    {
        return $this->loyaltyPointsAmount;
    }

    /**
     * @param string $campaignType
     * @return $this
     */
    public function withCampaignType($campaignType)
    {
        $this->campaignType = $campaignType;
        return $this;
    }

    /**
     * @return string
     */
    public function getCampaignType()
    {
        return $this->campaignType;
    }

    /**
     * Sets the AIRLINE_INFO parameter information
     *
     * @param AirlineInfo $airlineInfo
     * @return $this
     */
    public function withAirlineInfo(AirlineInfo $airlineInfo)
    {
        $this->airlineInfo = $airlineInfo;

        return $this;
    }

    /**
     * @return AirlineInfo
     */
    public function getAirlineInfo()
    {
        return $this->airlineInfo;
    }
}

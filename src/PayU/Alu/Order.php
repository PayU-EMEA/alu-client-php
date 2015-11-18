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
     * @param float $loyaltyPointsAmount
     * @return $this
     */
    public function withLoyaltyPointsAmount($loyaltyPointsAmount)
    {
        $this->loyaltyPointsAmount = $loyaltyPointsAmount;
        return $this;
    }

    /**
     * @return float
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
    public function getCampaignType() {
        return $this->campaignType;
    }

}

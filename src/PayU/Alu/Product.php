<?php

namespace PayU\Alu;


class Product
{

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $code;

    /**
     * @var string
     */
    private $info;

    /**
     * @var float
     */
    private $price;

    /**
     * @var int
     */
    private $quantity;

    /**
     * @var float
     */
    private $VAT;

    /**
     * @var string
     */
    private $marketPlaceMerchantCode;

    /**
     * @var string
     */
    private $productGroup;

    /**
     * @var string
     */
    private $productVersion;

    /**
     * @param float $VAT
     * @return $this
     */
    public function withVAT($VAT)
    {
        $this->VAT = $VAT;
        return $this;
    }

    /**
     * @param string $code
     * @return $this
     */
    public function withCode($code)
    {
        $this->code = $code;
        return $this;
    }

    /**
     * @param string $info
     * @return $this
     */
    public function withInfo($info)
    {
        $this->info = $info;
        return $this;
    }

    /**
     * @param string $marketPlaceMerchantCode
     * @return $this
     */
    public function withMarketPlaceMerchantCode($marketPlaceMerchantCode)
    {
        $this->marketPlaceMerchantCode = $marketPlaceMerchantCode;
        return $this;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function withName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @param float $price
     * @return $this
     */
    public function withPrice($price)
    {
        $this->price = $price;
        return $this;
    }

    /**
     * @param string $productGroup
     * @return $this
     */
    public function withProductGroup($productGroup)
    {
        $this->productGroup = $productGroup;
        return $this;
    }

    /**
     * @param int $quantity
     * @return $this
     */
    public function withQuantity($quantity)
    {
        $this->quantity = $quantity;
        return $this;
    }

    /**
     * @param int $productVersion
     * @return $this
     */
    public function withProductVersion($productVersion)
    {
        $this->productVersion = $productVersion;
        return $this;
    }

    /**
     * @return float
     */
    public function getVAT()
    {
        return $this->VAT;
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @return string
     */
    public function getInfo()
    {
        return $this->info;
    }

    /**
     * @return string
     */
    public function getMarketPlaceMerchantCode()
    {
        return $this->marketPlaceMerchantCode;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @return string
     */
    public function getProductGroup()
    {
        return $this->productGroup;
    }

    /**
     * @return int
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * @return string
     */
    public function getProductVersion()
    {
        return $this->productVersion;
    }


}

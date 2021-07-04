<?php


namespace PayU\Alu;

class Marketplace
{
    /** @var string */
    private $id;

    /** @var string */
    private $sellerId;

    /** @var double */
    private $commissionAmount;

    /** @var string */
    private $commissionCurrency;

    public function __construct(
        $id,
        $sellerId,
        $commissionAmount,
        $commissionCurrency
    ) {
        $this->id = $id;
        $this->sellerId = $sellerId;
        $this->commissionAmount = $commissionAmount;
        $this->commissionCurrency = $commissionCurrency;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getSellerId()
    {
        return $this->sellerId;
    }

    /**
     * @return float
     */
    public function getCommissionAmount()
    {
        return $this->commissionAmount;
    }

    /**
     * @return string
     */
    public function getCommissionCurrency()
    {
        return $this->commissionCurrency;
    }
}

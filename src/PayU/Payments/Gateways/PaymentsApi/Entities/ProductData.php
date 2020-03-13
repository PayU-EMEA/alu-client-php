<?php


namespace PaymentsApi\Entities;


class ProductData implements \JsonSerializable
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $sku;

    /**
     * @var string
     */
    private $unitPrice;

    /**
     * @var float
     */
    private $quantity;

    /**
     * @var int
     */
    private $additionalDetails;

    /**
     * @var float
     */
    private $vat;

    /**
     * ProductData constructor.
     * @param string $name
     * @param string $sku
     * @param string $unitPrice
     * @param double $quantity
     */
    public function __construct(
        $name,
        $sku,
        $unitPrice,
        $quantity
    )
    {
        $this->name = $name;
        $this->sku = $sku;
        $this->unitPrice = $unitPrice;
        $this->quantity = $quantity;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getSku()
    {
        return $this->sku;
    }

    /**
     * @return string
     */
    public function getUnitPrice()
    {
        return $this->unitPrice;
    }

    /**
     * @return float
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * @return int
     */
    public function getAdditionalDetails()
    {
        return $this->additionalDetails;
    }

    /**
     * @param int $additionalDetails
     */
    public function setAdditionalDetails($additionalDetails)
    {
        $this->additionalDetails = $additionalDetails;
    }

    /**
     * @return float
     */
    public function getVat()
    {
        return $this->vat;
    }

    /**
     * @param float $vat
     */
    public function setVat($vat)
    {
        $this->vat = $vat;
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize()
    {
        return [
            'name' => $this->name,
            'sku' => $this->sku,
            'additionalDetails' => $this->additionalDetails,
            'unitPrice' => $this->unitPrice,
            'quantity' => $this->quantity,
            'vat' => $this->vat
        ];
    }
}

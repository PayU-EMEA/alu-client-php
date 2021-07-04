<?php


namespace PayU\PaymentsApi\PaymentsV4\Entities\Request;

use PayU\PaymentsApi\PaymentsV4\Entities\Request\Product\Marketplace;

final class ProductData implements \JsonSerializable
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

    /** @var Marketplace */
    private $marketplace;

    /**
     * ProductData constructor.
     *
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
    ) {
        $this->name = $name;
        $this->sku = $sku;
        $this->unitPrice = $unitPrice;
        $this->quantity = $quantity;
    }

    /**
     * @param Marketplace $marketplace
     */
    public function setMarketplace($marketplace)
    {
        $this->marketplace = $marketplace;
    }

    /**
     * @param int $additionalDetails
     */
    public function setAdditionalDetails($additionalDetails)
    {
        $this->additionalDetails = $additionalDetails;
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
            'vat' => $this->vat,
            'marketplace' => $this->marketplace
        ];
    }
}

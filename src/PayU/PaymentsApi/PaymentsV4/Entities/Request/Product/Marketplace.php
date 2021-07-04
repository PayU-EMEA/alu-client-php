<?php


namespace PayU\PaymentsApi\PaymentsV4\Entities\Request\Product;

final class Marketplace implements \JsonSerializable
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
     * @inheritDoc
     */
    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'sellerId' => $this->sellerId,
            'commissionAmount' => $this->commissionAmount,
            'commissionCurrency' => $this->commissionCurrency
        ];
    }
}

<?php


namespace PayU\PaymentsApi\PaymentsV4\Entities;

final class MerchantData implements \JsonSerializable
{
    /**
     * @var string
     */
    private $posCode;

    /**
     * MerchantData constructor.
     *
     * @param string $posCode
     */
    public function __construct($posCode)
    {
        $this->posCode = $posCode;
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize()
    {
        return [
            'posCode' => $this->posCode
        ];
    }
}

<?php


namespace PaymentsApi\Entities;


class MerchantData implements \JsonSerializable
{
    /**
     * @var string
     */
    private $posCode;

    /**
     * MerchantData constructor.
     * @param string $posCode
     */
    public function __construct($posCode)
    {
        $this->posCode = $posCode;
    }

    /**
     * @return string
     */
    public function getPosCode()
    {
        return $this->posCode;
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
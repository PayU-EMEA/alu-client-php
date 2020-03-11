<?php


namespace PayU\Payments\Gateways\PaymentsApi\Entities;


class IdentityDocumentData implements \JsonSerializable
{
    /**
     * @var string
     */
    private $number;

    /**
     * @var string
     */
    private $type;

    /**
     * @return string
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * @param string $number
     */
    public function setNumber($number)
    {
        $this->number = $number;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize()
    {
        return [
            'number' => $this->number,
            'type' => $this->type
        ];
    }
}
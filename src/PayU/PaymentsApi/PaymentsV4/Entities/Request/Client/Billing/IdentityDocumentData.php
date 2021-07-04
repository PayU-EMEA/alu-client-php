<?php


namespace PayU\PaymentsApi\PaymentsV4\Entities\Request\Client\Billing;

final class IdentityDocumentData implements \JsonSerializable
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
     * @param string $number
     */
    public function setNumber($number)
    {
        $this->number = $number;
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

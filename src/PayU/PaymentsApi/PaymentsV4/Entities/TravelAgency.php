<?php


namespace PayU\PaymentsApi\PaymentsV4\Entities;

final class TravelAgency implements \JsonSerializable
{
    /** @var string */
    private $code;

    /** @var string */
    private $name;

    /**
     * @param string $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize()
    {
        return [
            'code' => $this->code,
            'name' => $this->name
        ];
    }
}

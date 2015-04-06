<?php

namespace PayU\Alu;

/**
 * Class ResponseWireRecipient
 * @package PayU\Alu
 * @codeCoverageIgnore
 */
class ResponseWireRecipient
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $vatId;

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getVatId()
    {
        return $this->vatId;
    }

    /**
     * @param string $vatId
     */
    public function setVatId($vatId)
    {
        $this->vatId = $vatId;
    }
}

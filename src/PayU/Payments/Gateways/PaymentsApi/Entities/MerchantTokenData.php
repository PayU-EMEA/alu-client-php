<?php


namespace PaymentsApi\Entities;

class MerchantTokenData implements \JsonSerializable
{
    /**
     * @var string
     */
    private $tokenHash;

    /**
     * @var int
     */
    private $cvv;

    /**
     * @var string
     */
    private $owner;

    /**
     * MerchantTokenData constructor.
     *
     * @param string $tokenHash
     */
    public function __construct($tokenHash)
    {
        $this->tokenHash = $tokenHash;
    }

    /**
     * @param int $cvv
     */
    public function setCvv($cvv)
    {
        $this->cvv = $cvv;
    }

    /**
     * @param string $owner
     */
    public function setOwner($owner)
    {
        $this->owner = $owner;
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize()
    {
        return [
            'tokenHash' => $this->tokenHash,
            'cvv' => $this->cvv,
            'owner' => $this->owner
        ];
    }
}

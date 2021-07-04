<?php


namespace PayU\PaymentsApi\PaymentsV4\Entities\Request\Authorization;

final class MerchantTokenData implements \JsonSerializable
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
     * @inheritDoc
     */
    public function jsonSerialize()
    {
        return [
            'tokenHash' => $this->tokenHash,
            'cvv' => $this->cvv
        ];
    }
}

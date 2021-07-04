<?php


namespace PayU\Alu;

class ApplePayTokenHeader
{
    /** @var string */
    private $applicationData;

    /** @var string */
    private $ephemeralPublicKey;

    /** @var string */
    private $wrappedKey;

    /** @var string */
    private $publicKeyHash;

    /** @var string */
    private $transactionId;

    public function __construct(
        $applicationData,
        $ephemeralPublicKey,
        $wrappedKey,
        $publicKeyHash,
        $transactionId
    ) {
        $this->applicationData = $applicationData;
        $this->ephemeralPublicKey = $ephemeralPublicKey;
        $this->wrappedKey = $wrappedKey;
        $this->publicKeyHash = $publicKeyHash;
        $this->transactionId = $transactionId;
    }

    /**
     * @return string
     */
    public function getApplicationData()
    {
        return $this->applicationData;
    }

    /**
     * @return string
     */
    public function getEphemeralPublicKey()
    {
        return $this->ephemeralPublicKey;
    }

    /**
     * @return string
     */
    public function getWrappedKey()
    {
        return $this->wrappedKey;
    }

    /**
     * @return string
     */
    public function getPublicKeyHash()
    {
        return $this->publicKeyHash;
    }

    /**
     * @return string
     */
    public function getTransactionId()
    {
        return $this->transactionId;
    }
}

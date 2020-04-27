<?php


namespace PayU\PaymentsApi\PaymentsV4\Entities;

class ApplePayTokenHeader implements \JsonSerializable
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

    public function jsonSerialize()
    {
        return [
            'applicationData' => $this->applicationData,
            'ephemeralPublicKey' => $this->ephemeralPublicKey,
            'wrappedKey' => $this->wrappedKey,
            'publicKeyHash' => $this->publicKeyHash,
            'transactionId' => $this->transactionId
        ];
    }
}

<?php


namespace PayU\PaymentsApi\PaymentsV4\Entities;

final class StoredCredentialsData implements \JsonSerializable
{
    /** @var string */
    private $consentType;

    /** @var string */
    private $useType;

    /** @var string */
    private $useId;

    /**
     * @param string $consentType
     */
    public function setConsentType($consentType)
    {
        $this->consentType = $consentType;
    }

    /**
     * @param string $useType
     */
    public function setUseType($useType)
    {
        $this->useType = $useType;
    }

    /**
     * @param string $useId
     */
    public function setUseId($useId)
    {
        $this->useId = $useId;
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize()
    {
        return [
            'consentType' => $this->consentType,
            'useType' => $this->useType,
            'useId' => $this->useId
        ];
    }
}

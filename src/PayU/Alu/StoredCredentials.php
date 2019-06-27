<?php

namespace PayU\Alu;

/**
 * @package PayU\Alu
 */
class StoredCredentials
{
    const STORED_CREDENTIALS_USE_TYPE = 'STORED_CREDENTIALS_USE_TYPE';
    const STORED_CREDENTIALS_CONSENT_TYPE = 'STORED_CREDENTIALS_CONSENT_TYPE';
    const STORED_CREDENTIALS_USE_ID = 'STORED_CREDENTIALS_USE_ID';

    const CONSENT_TYPE_RECURRING = 'recurring';
    const CONSENT_TYPE_ON_DEMAND = 'onDemand';

    const USE_TYPE_CARDHOLDER = 'cardholder';
    const USE_TYPE_MERCHANT = 'merchant';
    const USE_TYPE_RECURRING = 'recurring';


    /** @var string Can be 'recurring' or 'onDemand' */
    private $storedCredentialsConsentType;

    /** @var string Can be 'cardholder' , 'merchant' or 'recurring' */
    private $storedCredentialsUseType;

    /** @var string any alphanumeric value */
    private $storedCredentialsUseId;

    /**
     * @return string
     */
    public function getStoredCredentialsConsentType()
    {
        return $this->storedCredentialsConsentType;
    }

    /**
     * @param string $storedCredentialsConsentType
     */
    public function setStoredCredentialsConsentType($storedCredentialsConsentType)
    {
        $this->storedCredentialsConsentType = $storedCredentialsConsentType;
    }

    /**
     * @return string
     */
    public function getStoredCredentialsUseType()
    {
        return $this->storedCredentialsUseType;
    }

    /**
     * @param string $storedCredentialsUseType
     */
    public function setStoredCredentialsUseType($storedCredentialsUseType)
    {
        $this->storedCredentialsUseType = $storedCredentialsUseType;
    }

    /**
     * @return string
     */
    public function getStoredCredentialsUseId()
    {
        return $this->storedCredentialsUseId;
    }

    /**
     * @param string $storedCredentialsUseId
     */
    public function setStoredCredentialsUseId($storedCredentialsUseId)
    {
        $this->storedCredentialsUseId = $storedCredentialsUseId;
    }
}

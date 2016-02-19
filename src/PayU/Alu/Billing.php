<?php

namespace PayU\Alu;

/**
 * Class Billing
 * @package PayU\Alu
 */
class Billing extends AbstractCommonAddress
{
    /**
     * @var string
     */
    private $identityCardSeries;

    /**
     * @var string
     */
    private $identityCardNumber;

    /**
     * @var string
     */
    private $identityCardIssuer;

    /**
     * @var string
     */
    private $identityCardType;

    /**
     * @var string
     */
    private $personalNumericCode;

    /**
     * @var string
     */
    private $companyFiscalCode;

    /**
     * @var string
     */
    private $companyRegistrationNumber;

    /**
     * @var string
     */
    private $companyBankAccountNumber;

    /**
     * @var string
     */
    private $companyBank;

    /**
     * @param string $companyBank
     * @return $this
     */
    public function withCompanyBank($companyBank)
    {
        $this->companyBank = $companyBank;
        return $this;
    }

    /**
     * @param string $companyBankAccountNumber
     * @return $this
     */
    public function withCompanyBankAccountNumber($companyBankAccountNumber)
    {
        $this->companyBankAccountNumber = $companyBankAccountNumber;
        return $this;
    }

    /**
     * @param string $companyFiscalCode
     * @return $this
     */
    public function withCompanyFiscalCode($companyFiscalCode)
    {
        $this->companyFiscalCode = $companyFiscalCode;
        return $this;
    }

    /**
     * @param string $companyRegistrationNumber
     * @return $this
     */
    public function withCompanyRegistrationNumber($companyRegistrationNumber)
    {
        $this->companyRegistrationNumber = $companyRegistrationNumber;
        return $this;
    }

    /**
     * @param string $identityCardIssuer
     * @return $this
     */
    public function withIdentityCardIssuer($identityCardIssuer)
    {
        $this->identityCardIssuer = $identityCardIssuer;
        return $this;
    }

    /**
     * @param string $identityCardNumber
     * @return $this
     */
    public function withIdentityCardNumber($identityCardNumber)
    {
        $this->identityCardNumber = $identityCardNumber;
        return $this;
    }

    /**
     * @param string $identityCardSeries
     * @return $this
     */
    public function withIdentityCardSeries($identityCardSeries)
    {
        $this->identityCardSeries = $identityCardSeries;
        return $this;
    }

    /**
     * @param string $identityCardType
     * @return $this
     */
    public function withIdentityCardType($identityCardType)
    {
        $this->identityCardType = $identityCardType;
        return $this;
    }

    /**
     * @param string $personalNumericCode
     * @return $this
     */
    public function withPersonalNumericCode($personalNumericCode)
    {
        $this->personalNumericCode = $personalNumericCode;
        return $this;
    }

    /**
     * @return string
     */
    public function getCompanyBank()
    {
        return $this->companyBank;
    }

    /**
     * @return string
     */
    public function getCompanyBankAccountNumber()
    {
        return $this->companyBankAccountNumber;
    }

    /**
     * @return string
     */
    public function getCompanyFiscalCode()
    {
        return $this->companyFiscalCode;
    }

    /**
     * @return string
     */
    public function getCompanyRegistrationNumber()
    {
        return $this->companyRegistrationNumber;
    }

    /**
     * @return string
     */
    public function getIdentityCardIssuer()
    {
        return $this->identityCardIssuer;
    }

    /**
     * @return string
     */
    public function getIdentityCardNumber()
    {
        return $this->identityCardNumber;
    }

    /**
     * @return string
     */
    public function getIdentityCardSeries()
    {
        return $this->identityCardSeries;
    }

    /**
     * @return string
     */
    public function getIdentityCardType()
    {
        return $this->identityCardType;
    }

    /**
     * @return string
     */
    public function getPersonalNumericCode()
    {
        return $this->personalNumericCode;
    }
}

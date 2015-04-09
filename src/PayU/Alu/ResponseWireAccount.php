<?php

namespace PayU\Alu;

/**
 * Class ResponseWireAccount
 * @package PayU\Alu
 * @codeCoverageIgnore
 */
class ResponseWireAccount
{
    /**
     * @var string
     */
    private $bankIdentifier;

    /**
     * @var string
     */
    private $bankAccount;

    /**
     * @var string
     */
    private $routingNumber;

    /**
     * @var string
     */
    private $ibanAccount;

    /**
     * @var string
     */
    private $bankSwift;

    /**
     * @var string
     */
    private $country;

    /**
     * @var string
     */
    private $wireRecipientName;

    /**
     * @var string
     */
    private $wireRecipientVatId;

    /**
     * @return string
     */
    public function getWireRecipientName()
    {
        return $this->wireRecipientName;
    }

    /**
     * @param string $wireRecipientName
     */
    public function setWireRecipientName($wireRecipientName)
    {
        $this->wireRecipientName = $wireRecipientName;
    }

    /**
     * @return string
     */
    public function getWireRecipientVatId()
    {
        return $this->wireRecipientVatId;
    }

    /**
     * @param string $wireRecipientVatId
     */
    public function setWireRecipientVatId($wireRecipientVatId)
    {
        $this->wireRecipientVatId = $wireRecipientVatId;
    }

    /**
     * @return string
     */
    public function getBankIdentifier()
    {
        return $this->bankIdentifier;
    }

    /**
     * @param string $bankIdentifier
     */
    public function setBankIdentifier($bankIdentifier)
    {
        $this->bankIdentifier = $bankIdentifier;
    }

    /**
     * @return string
     */
    public function getBankAccount()
    {
        return $this->bankAccount;
    }

    /**
     * @param string $bankAccount
     */
    public function setBankAccount($bankAccount)
    {
        $this->bankAccount = $bankAccount;
    }

    /**
     * @return string
     */
    public function getRoutingNumber()
    {
        return $this->routingNumber;
    }

    /**
     * @param string $routingNumber
     */
    public function setRoutingNumber($routingNumber)
    {
        $this->routingNumber = $routingNumber;
    }

    /**
     * @return string
     */
    public function getIbanAccount()
    {
        return $this->ibanAccount;
    }

    /**
     * @param string $ibanAccount
     */
    public function setIbanAccount($ibanAccount)
    {
        $this->ibanAccount = $ibanAccount;
    }

    /**
     * @return string
     */
    public function getBankSwift()
    {
        return $this->bankSwift;
    }

    /**
     * @param string $bankSwift
     */
    public function setBankSwift($bankSwift)
    {
        $this->bankSwift = $bankSwift;
    }

    /**
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param string $country
     */
    public function setCountry($country)
    {
        $this->country = $country;
    }
}

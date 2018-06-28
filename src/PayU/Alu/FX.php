<?php

namespace PayU\Alu;

class FX
{
    /** @var string */
    private $authorizationCurrency;

    /** @var float */
    private $authorizationExchangeRate;

    /**
     * FX constructor.
     * @param string $authorizationCurrency
     * @param float $authorizationExchangeRate
     */
    public function __construct($authorizationCurrency, $authorizationExchangeRate)
    {
        $this->authorizationCurrency = $authorizationCurrency;
        $this->authorizationExchangeRate = $authorizationExchangeRate;
    }

    /**
     * @return string
     */
    public function getAuthorizationCurrency()
    {
        return $this->authorizationCurrency;
    }

    /**
     * @param $authorizationCurrency
     * @return $this
     */
    public function setAuthorizationCurrency($authorizationCurrency)
    {
        $this->authorizationCurrency = $authorizationCurrency;

        return $this;
    }

    /**
     * @return float
     */
    public function getAuthorizationExchangeRate()
    {
        return $this->authorizationExchangeRate;
    }

    /**
     * @param $authorizationExchangeRate
     * @return $this
     */
    public function setAuthorizationExchangeRate($authorizationExchangeRate)
    {
        $this->authorizationExchangeRate = $authorizationExchangeRate;

        return $this;
    }
}
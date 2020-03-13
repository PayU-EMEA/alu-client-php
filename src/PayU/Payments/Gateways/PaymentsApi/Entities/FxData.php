<?php

namespace PaymentsApi\Entities;

class FxData implements \JsonSerializable
{
    /** @var string */
    private $currency;

    /** @var float */
    private $exchangeRate;

    /**
     * FX constructor.
     * @param string $currency
     * @param double $exchangeRate
     */
    public function __construct($currency, $exchangeRate)
    {
        $this->currency = $currency;
        $this->exchangeRate = $exchangeRate;
    }

    /**
     * @return string
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @return float
     */
    public function getExchangeRate()
    {
        return $this->exchangeRate;
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize()
    {
        return [
            'currency' => $this->currency,
            'exchangeRate' => $this->exchangeRate
        ];
    }
}

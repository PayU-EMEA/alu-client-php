<?php

namespace PayU\Alu;

/**
 * Class Card
 * @package PayU\Alu
 */
class Card
{
    /**
     * @var string
     */
    private $cardNumber;

    /**
     * @var int
     */
    private $cardExpirationMonth;

    /**
     * @var int
     */
    private $cardExpirationYear;

    /**
     * @var int
     */
    private $cardCVV;

    /**
     * @var string
     */
    private $cardOwnerName;

    /** @var bool */
    private $enableTokenCreation;

    /**
     * @param string $cardNumber
     * @param int $cardExpirationMonth
     * @param int $cardExpirationYear
     * @param int $cardCVV
     * @param string $cardOwnerName
     */
    public function __construct($cardNumber, $cardExpirationMonth, $cardExpirationYear, $cardCVV, $cardOwnerName)
    {
        $this->cardNumber           = $cardNumber;
        $this->cardExpirationMonth  = $cardExpirationMonth;
        $this->cardExpirationYear   = $cardExpirationYear;
        $this->cardCVV              = $cardCVV;
        $this->cardOwnerName        = $cardOwnerName;
    }

    /**
     * @return int
     */
    public function getCardCVV()
    {
        return $this->cardCVV;
    }


    /**
     * @return int
     */
    public function getCardExpirationMonth()
    {
        return $this->cardExpirationMonth;
    }

    /**
     * @return int
     */
    public function getCardExpirationYear()
    {
        return $this->cardExpirationYear;
    }


    /**
     * @return string
     */
    public function getCardNumber()
    {
        return $this->cardNumber;
    }


    /**
     * @return string
     */
    public function getCardOwnerName()
    {
        return $this->cardOwnerName;
    }

    public function enableTokenCreation()
    {
        $this->enableTokenCreation = true;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isEnableTokenCreation()
    {
        return $this->enableTokenCreation;
    }
}

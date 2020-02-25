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
     * @param int|null $timeSpentTypingNumber
     * @param int|null $timeSpentTypingOwner
     */
    public function __construct(
        $cardNumber,
        $cardExpirationMonth,
        $cardExpirationYear,
        $cardCVV,
        $cardOwnerName,
        $timeSpentTypingNumber = null,
        $timeSpentTypingOwner = null)
    {
        $this->cardNumber = $cardNumber;
        $this->cardExpirationMonth = $cardExpirationMonth;
        $this->cardExpirationYear = $cardExpirationYear;
        $this->cardCVV = $cardCVV;
        $this->cardOwnerName = $cardOwnerName;
        $this->timeSpentTypingNumber = $timeSpentTypingNumber;
        $this->timeSpentTypingOwner = $timeSpentTypingOwner;
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
     * @return int|null
     */
    public function getTimeSpentTypingNumber()
    {
        return $this->timeSpentTypingNumber;
    }

    /**
     * @return int|null
     */
    public function getTimeSpentTypingOwner()
    {
        return $this->timeSpentTypingOwner;
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

    /**
     * @return bool
     */
    public function hasTimeSpentTypingOwner()
    {
        return !empty($this->timeSpentTypingOwner);
    }

    /**
     * @return bool
     */
    public function hasTimeSpentTypingNumber()
    {
        return !empty($this->timeSpentTypingNumber);
    }
}

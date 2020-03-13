<?php


namespace PaymentsApi\Entities;


class CardDetails implements \JsonSerializable
{
    /**
     * @var string
     */
    private $number;

    /**
     * @var int
     */
    private $expiryMonth;

    /**
     * @var int
     */
    private $expiryYear;

    /**
     * @var int
     */
    private $cvv;

    /**
     * @var string
     */
    private $owner;

    /**
     * @var int
     */
    private $timeSpentTypingNumber;

    /**
     * @var int
     */
    private $timeSpentTypingOwner;

    /**
     * CardDetails constructor.
     *
     * @param string $number
     * @param int $expiryMonth
     * @param int $expiryYear
     */
    public function __construct(
        $number,
        $expiryMonth,
        $expiryYear
    ) {
        $this->number = $number;
        $this->expiryMonth = $expiryMonth;
        $this->expiryYear = $expiryYear;
    }

    /**
     * @return string
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * @return int
     */
    public function getExpiryMonth()
    {
        return $this->expiryMonth;
    }

    /**
     * @return int
     */
    public function getExpiryYear()
    {
        return $this->expiryYear;
    }

    /**
     * @return int
     */
    public function getCvv()
    {
        return $this->cvv;
    }

    /**
     * @return string
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * @return int
     */
    public function getTimeSpentTypingNumber()
    {
        return $this->timeSpentTypingNumber;
    }

    /**
     * @return int
     */
    public function getTimeSpentTypingOwner()
    {
        return $this->timeSpentTypingOwner;
    }

    /**
     * @param int $cvv
     */
    public function setCvv($cvv)
    {
        $this->cvv = $cvv;
    }

    /**
     * @param string $owner
     */
    public function setOwner($owner)
    {
        $this->owner = $owner;
    }

    /**
     * @param int $timeSpentTypingNumber
     */
    public function setTimeSpentTypingNumber($timeSpentTypingNumber)
    {
        $this->timeSpentTypingNumber = $timeSpentTypingNumber;
    }

    /**
     * @param int $timeSpentTypingOwner
     */
    public function setTimeSpentTypingOwner($timeSpentTypingOwner)
    {
        $this->timeSpentTypingOwner = $timeSpentTypingOwner;
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize()
    {
        return [
            'number' => $this->number,
            'expiryMonth' => $this->expiryMonth,
            'expiryYear' => $this->expiryYear,
            'cvv' => $this->cvv,
            'owner' => $this->owner,
            'timeSpentTypingNumber' => $this->timeSpentTypingNumber,
            'timeSpentTypingOwner' => $this->timeSpentTypingOwner
        ];
    }
}

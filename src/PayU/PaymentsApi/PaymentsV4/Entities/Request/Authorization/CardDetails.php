<?php


namespace PayU\PaymentsApi\PaymentsV4\Entities\Request\Authorization;

final class CardDetails implements \JsonSerializable
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
     * @inheritDoc
     */
    public function jsonSerialize()
    {
        return [
            'number' => $this->number,
            'expiryMonth' => $this->expiryMonth,
            'expiryYear' => $this->expiryYear,
            'cvv' => $this->cvv,
            'owner' => $this->owner
        ];
    }
}

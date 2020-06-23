<?php


namespace PayU\Alu;

class TokenResponseData
{
    /** @var int */
    private $tokenCode;

    /** @var string */
    private $tokenMessage;

    /**
     * TokenResponseData constructor.
     *
     * @param int $tokenCode
     * @param string $tokenMessage
     */
    public function __construct(
        $tokenCode,
        $tokenMessage
    ) {
        $this->tokenCode = $tokenCode;
        $this->tokenMessage = $tokenMessage;
    }

    /**
     * @return int
     */
    public function getTokenCode()
    {
        return $this->tokenCode;
    }

    /**
     * @return string
     */
    public function getTokenMessage()
    {
        return $this->tokenMessage;
    }
}

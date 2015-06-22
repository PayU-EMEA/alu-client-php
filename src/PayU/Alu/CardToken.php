<?php

namespace PayU\Alu;

/**
 * Class CardToken
 * @package PayU\Alu
 */
class CardToken
{
    /**
     * @var string
     */
    private $token;

    /**
     * @param string $token
     * @param string $cvv
     */
    public function __construct($token, $cvv = '')
    {
        $this->token = $token;
        $this->cvv = $cvv;
    }

    /**
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @return string
     */
    public function getCvv()
    {
        return $this->cvv;
    }

    /**
     * @return boolean
     */
    public function hasCvv()
    {
        return !empty($this->cvv);
    }
}

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
     * @param string $owner
     */
    public function __construct($token, $cvv = '', $owner = '')
    {
        $this->token = $token;
        $this->cvv = $cvv;
        $this->owner = $owner;
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
     * @return string
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * @return boolean
     */
    public function hasCvv()
    {
        return !empty($this->cvv);
    }

    /**
     * @return bool
     */
    public function hasOwner()
    {
        return !empty($this->owner);
    }
}

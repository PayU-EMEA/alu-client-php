<?php

namespace PayU\Alu;

/**
 * Class User
 * @package PayU\Alu
 */
class User
{
    /**
     * @var string
     */
    private $userIPAddress;

    /**
     * @var string
     */
    private $clientTime;

    /**
     * @param string $userIPAddress
     * @param string $userTime
     */
    public function __construct($userIPAddress, $userTime = '')
    {
        $this->userIPAddress = $userIPAddress;
        $this->clientTime = $userTime;
    }

    /**
     * @return string
     */
    public function getUserIPAddress()
    {
        return $this->userIPAddress;
    }

    /**
     * @return string
     */
    public function getClientTime(){
        return $this->clientTime;
    }
}


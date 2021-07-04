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

    /** @var string */
    private $communicationLanguage;

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
    public function getClientTime()
    {
        return $this->clientTime;
    }

    /**
     * @return string
     */
    public function getCommunicationLanguage()
    {
        return $this->communicationLanguage;
    }

    /**
     * @param string $communicationLanguage
     */
    public function setCommunicationLanguage($communicationLanguage)
    {
        $this->communicationLanguage = $communicationLanguage;
    }
}

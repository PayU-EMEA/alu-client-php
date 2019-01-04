<?php

namespace PayU\Alu;


class MerchantConfig
{
    /**
     * @var string
     */
    private $merchantCode;

    /**
     * @var string
     */
    private $secretKey;

    /**
     * @var string
     */
    private $platform;

    /**
     * @param string $merchantCode
     * @param string $secretKey
     * @param string $platform
     */
    public function __construct($merchantCode, $secretKey, $platform)
    {
        $this->merchantCode = $merchantCode;
        $this->secretKey = $secretKey;
        $this->platform = $platform;
    }

    /**
     * @return string
     */
    public function getMerchantCode()
    {
        return $this->merchantCode;
    }

    /**
     * @return string
     */
    public function getPlatform()
    {
        return strtolower($this->platform);
    }

    /**
     * @return string
     */
    public function getSecretKey()
    {
        return $this->secretKey;
    }
}

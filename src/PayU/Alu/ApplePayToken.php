<?php


namespace PayU\Alu;

class ApplePayToken
{
    /** @var string */
    private $data;

    /** @var ApplePayTokenHeader */
    private $header;

    /** @var string */
    private $signature;

    /** @var string */
    private $version;

    public function __construct(
        $data,
        $header,
        $signature,
        $version
    ) {
        $this->data = $data;
        $this->header = $header;
        $this->signature = $signature;
        $this->version = $version;
    }

    /**
     * @return string
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @return ApplePayTokenHeader
     */
    public function getHeader()
    {
        return $this->header;
    }

    /**
     * @return string
     */
    public function getSignature()
    {
        return $this->signature;
    }

    /**
     * @return string
     */
    public function getVersion()
    {
        return $this->version;
    }
}

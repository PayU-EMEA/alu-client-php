<?php

namespace PayU\PaymentsApi\PaymentsV4\Entities\Request\Authorization;

use PayU\PaymentsApi\PaymentsV4\Entities\Request\Authorization\ApplePayToken\ApplePayTokenHeader;

final class ApplePayToken implements \JsonSerializable
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
     * @inheritDoc
     */
    public function jsonSerialize()
    {
        return [
            'data' => $this->data,
            'header' => $this->header,
            'signature' => $this->signature,
            'version' => $this->version
        ];
    }
}

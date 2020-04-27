<?php


namespace PayU\PaymentsApi\PaymentsV4\Entities;

class MpiData implements \JsonSerializable
{
    /** @var int */
    private $eci;

    /** @var string */
    private $xid;

    /** @var string */
    private $cavv;

    /** @var string */
    private $dsTransactionId;

    /** @var int */
    private $version;

    public function __construct(
        $eci,
        $xid,
        $cavv,
        $dsTransactionId,
        $version
    ) {
        $this->eci = $eci;
        $this->xid = $xid;
        $this->cavv = $cavv;
        $this->dsTransactionId = $dsTransactionId;
        $this->version = $version;
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize()
    {
        return [
            'eci' => $this->eci,
            'xid' => $this->xid,
            'cavv' => $this->cavv,
            'dsTransactionId' => $this->dsTransactionId,
            'version' => $this->version
        ];
    }
}

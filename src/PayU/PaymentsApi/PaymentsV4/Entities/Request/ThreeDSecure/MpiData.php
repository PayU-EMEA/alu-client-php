<?php


namespace PayU\PaymentsApi\PaymentsV4\Entities\Request\ThreeDSecure;

final class MpiData implements \JsonSerializable
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

    /**
     * @param int $eci
     */
    public function setEci($eci)
    {
        $this->eci = $eci;
    }

    /**
     * @param string $xid
     */
    public function setXid($xid)
    {
        $this->xid = $xid;
    }

    /**
     * @param string $cavv
     */
    public function setCavv($cavv)
    {
        $this->cavv = $cavv;
    }

    /**
     * @param string $dsTransactionId
     */
    public function setDsTransactionId($dsTransactionId)
    {
        $this->dsTransactionId = $dsTransactionId;
    }

    /**
     * @param int $version
     */
    public function setVersion($version)
    {
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

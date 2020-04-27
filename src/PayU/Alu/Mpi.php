<?php


namespace PayU\Alu;


class Mpi
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
    private $version = 1;

    /**
     * @param string $xid
     * @return Mpi
     */
    public function withXid($xid)
    {
        $this->xid = $xid;
        return $this;
    }

    /**
     * @param string $cavv
     * @return Mpi
     */
    public function withCavv($cavv)
    {
        $this->cavv = $cavv;
        return $this;
    }

    /**
     * @param string $dsTransactionId
     * @return Mpi
     */
    public function withDsTransactionId($dsTransactionId)
    {
        $this->dsTransactionId = $dsTransactionId;
        return $this;
    }

    /**
     * @param int $version
     * @return Mpi
     */
    public function withVersion($version)
    {
        $this->version = $version;
        return $this;
    }

    /**
     * @param int $eci
     * @return $this
     */
    public function withEci($eci)
    {
        $this->eci = $eci;
        return $this;
    }

    /**
     * @return int
     */
    public function getEci()
    {
        return $this->eci;
    }

    /**
     * @return string
     */
    public function getXid()
    {
        return $this->xid;
    }

    /**
     * @return string
     */
    public function getCavv()
    {
        return $this->cavv;
    }

    /**
     * @return string
     */
    public function getDsTransactionId()
    {
        return $this->dsTransactionId;
    }

    /**
     * @return int
     */
    public function getVersion()
    {
        return $this->version;
    }
}

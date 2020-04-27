<?php


namespace PayU\Alu;


class ThreeDSecure
{
    /** @var Mpi */
    private $mpi;

    public function __construct($mpi)
    {
        $this->mpi = $mpi;
    }

    /**
     * @return Mpi
     */
    public function getMpi()
    {
        return $this->mpi;
    }
}

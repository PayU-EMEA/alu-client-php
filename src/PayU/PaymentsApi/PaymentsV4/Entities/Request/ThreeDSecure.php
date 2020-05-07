<?php


namespace PayU\PaymentsApi\PaymentsV4\Entities\Request;

use PayU\PaymentsApi\PaymentsV4\Entities\Request\ThreeDSecure\MpiData;

final class ThreeDSecure implements \JsonSerializable
{
    /** @var MpiData */
    private $mpi;

    public function __construct($mpi)
    {
        $this->mpi = $mpi;
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize()
    {
        return [
            'mpi' => $this->mpi
        ];
    }
}

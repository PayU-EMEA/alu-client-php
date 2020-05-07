<?php


namespace PayU\PaymentsApi\PaymentsV4\Entities\Request;

use PayU\PaymentsApi\PaymentsV4\Entities\Request\AirlineInfo\FlightSegments;
use PayU\PaymentsApi\PaymentsV4\Entities\Request\AirlineInfo\TravelAgency;

final class AirlineInfoData implements \JsonSerializable
{
    /** @var string */
    private $passengerName;

    /** @var string */
    private $ticketNumber;

    /** @var int */
    private $refundPolicy;

    /** @var string */
    private $reservationSystem;

    /** @var TravelAgency */
    private $travelAgency;

    /** @var FlightSegments */
    private $flightSegments;

    /**
     * AirlineInfoData constructor.
     * @param string $passengerName
     * @param FlightSegments[] $flightSegments
     */
    public function __construct($passengerName, $flightSegments)
    {
        $this->passengerName = $passengerName;
        $this->flightSegments = $flightSegments;
    }

    /**
     * @param string $ticketNumber
     */
    public function setTicketNumber($ticketNumber)
    {
        $this->ticketNumber = $ticketNumber;
    }

    /**
     * @param int $refundPolicy
     */
    public function setRefundPolicy($refundPolicy)
    {
        $this->refundPolicy = $refundPolicy;
    }

    /**
     * @param string $reservationSystem
     */
    public function setReservationSystem($reservationSystem)
    {
        $this->reservationSystem = $reservationSystem;
    }

    /**
     * @param TravelAgency $travelAgency
     */
    public function setTravelAgency($travelAgency)
    {
        $this->travelAgency = $travelAgency;
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize()
    {
        return [
            'passengerName' => $this->passengerName,
            'ticketNumber' => $this->ticketNumber,
            'refundPolicy' => $this->refundPolicy,
            'reservationSystem' => $this->reservationSystem,
            'travelAgency' => $this->travelAgency,
            'flightSegments' => $this->flightSegments
        ];
    }
}

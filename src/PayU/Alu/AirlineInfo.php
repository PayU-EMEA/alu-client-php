<?php

namespace PayU\Alu;

/**
 * AirlineInfo Class for AIRLINE_INFO parameter
 *
 * @package PayU\Alu
 */
class AirlineInfo
{
    /**
     * @var string First and last name of the passenger (max. 20 chars)
     */
    private $passengerName;

    /**
     * @var string Ticket number (max. 14 chars)
     */
    private $ticketNumber;

    /**
     * @var int Possibility of refund (0 - no restrictions, 1 - non refundable)
     */
    private $restrictedRefund;

    /**
     * @var  string DATS = Delta, SABR = Sabre, etc. (max. 4 chars)
     */
    private $reservationSystem;

    /**
     * @var string Travel agency code (max. 8 chars)
     */
    private $travelAgencyCode;

    /**
     * @var string Travel agency name (max. 25 chars)
     */
    private $travelAgencyName;

    /**
     * @see addTransit() method
     * @var array Information on flight transits
     */
    private $flightSegments = array();

    /**
     * @param string $passengerName
     * @return AirlineInfo
     */
    public function setPassengerName($passengerName)
    {
        $this->passengerName = $passengerName;

        return $this;
    }

    /**
     * @param string $ticketNumber
     * @return AirlineInfo
     */
    public function setTicketNumber($ticketNumber)
    {
        $this->ticketNumber = $ticketNumber;

        return $this;
    }

    /**
     * @param int $restrictedRefund
     * @return AirlineInfo
     */
    public function setRestrictedRefund($restrictedRefund)
    {
        $this->restrictedRefund = $restrictedRefund;

        return $this;
    }

    /**
     * @param string $reservationSystem
     * @return AirlineInfo
     */
    public function setReservationSystem($reservationSystem)
    {
        $this->reservationSystem = $reservationSystem;

        return $this;
    }

    /**
     * @param string $travelAgencyCode
     * @return AirlineInfo
     */
    public function setTravelAgencyCode($travelAgencyCode)
    {
        $this->travelAgencyCode = $travelAgencyCode;

        return $this;
    }

    /**
     * @param string $travelAgencyName
     * @return AirlineInfo
     */
    public function setTravelAgencyName($travelAgencyName)
    {
        $this->travelAgencyName = $travelAgencyName;

        return $this;
    }

    /**
     * Adds a new transit segment for this flight.
     * Note: Only the first 3 parameters are required. See ALU docs for more information.
     *
     * @param string $departureDate Departure date in the format YYYY-MM-DD
     * @param string $departureAirport Departure airport code (max. 3 chars)
     * @param string $destinationAirport Destination airport code (max. 3 chars)
     * @param string $airlineCode Airline 2-letters code
     * @param string $serviceClass Ticket type (class) (economy, business class, etc.) (1 char)
     * @param int $stopover Displays the possibility of stop-over for the given ticket; 1 = Stop-over is permitted, 0 otherwise
     * @param string $fareCode Tariff code (max. 6 chars)
     * @param string $flightNumber Flight number (max. 5 chars)
     *
     * @return AirlineInfo
     */
    public function addFlightSegment(
        $departureDate,
        $departureAirport,
        $destinationAirport,
        $airlineCode = null,
        $serviceClass = null,
        $stopover = null,
        $fareCode = null,
        $flightNumber = null
    ) {
        $segment = array(
            'DEPARTURE_DATE' => $departureDate,
            'DEPARTURE_AIRPORT' => $departureAirport,
            'DESTINATION_AIRPORT' => $destinationAirport,
            'AIRLINE_CODE' => $airlineCode,
            'SERVICE_CLASS' => $serviceClass,
            'STOPOVER' => $stopover,
            'FARE_CODE' => $fareCode,
            'FLIGHT_NUMBER' => $flightNumber,
        );

        $this->flightSegments[] = $segment;

        return $this;
    }

    /**
     * @return string
     */
    public function getPassengerName()
    {
        return $this->passengerName;
    }

    /**
     * @return string
     */
    public function getTicketNumber()
    {
        return $this->ticketNumber;
    }

    /**
     * @return int
     */
    public function getRestrictedRefund()
    {
        return $this->restrictedRefund;
    }

    /**
     * @return string
     */
    public function getReservationSystem()
    {
        return $this->reservationSystem;
    }

    /**
     * @return string
     */
    public function getTravelAgencyCode()
    {
        return $this->travelAgencyCode;
    }

    /**
     * @return string
     */
    public function getTravelAgencyName()
    {
        return $this->travelAgencyName;
    }

    /**
     * @return array
     */
    public function getFlightSegments()
    {
        return $this->flightSegments;
    }
}

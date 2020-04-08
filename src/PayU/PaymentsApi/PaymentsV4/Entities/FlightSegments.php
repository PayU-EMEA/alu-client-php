<?php


namespace PayU\PaymentsApi\PaymentsV4\Entities;

final class FlightSegments implements \JsonSerializable
{
    /** @var string */
    private $departureDate;

    /** @var string */
    private $departureAirport;

    /** @var string */
    private $destinationAirport;

    /** @var string */
    private $airlineCode;

    /** @var string */
    private $airlineName;

    /** @var string */
    private $serviceClass;

    /** @var string */
    private $stopover;

    /** @var string */
    private $fareCode;

    /** @var string */
    private $flightNumber;

    public function __construct(
        $departureDate,
        $departureAirport,
        $destinationAirport
    ) {
        $this->departureDate = $departureDate;
        $this->departureAirport = $departureAirport;
        $this->destinationAirport = $destinationAirport;
    }

    /**
     * @param string $airlineCode
     */
    public function setAirlineCode($airlineCode)
    {
        $this->airlineCode = $airlineCode;
    }

    /**
     * @param string $airlineName
     */
    public function setAirlineName($airlineName)
    {
        $this->airlineName = $airlineName;
    }

    /**
     * @param string $serviceClass
     */
    public function setServiceClass($serviceClass)
    {
        $this->serviceClass = $serviceClass;
    }

    /**
     * @param string $stopover
     */
    public function setStopover($stopover)
    {
        $this->stopover = $stopover;
    }

    /**
     * @param string $fareCode
     */
    public function setFareCode($fareCode)
    {
        $this->fareCode = $fareCode;
    }

    /**
     * @param string $flightNumber
     */
    public function setFlightNumber($flightNumber)
    {
        $this->flightNumber = $flightNumber;
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize()
    {
        return [
            'departureDate' => $this->departureDate,
            'departureAirport' => $this->departureAirport,
            'destinationAirport' => $this->destinationAirport,
            'airlineCode' => $this->airlineCode,
            'airlineName' => $this->airlineName,
            'serviceClass' => $this->serviceClass,
            'stopover' => $this->stopover,
            'fareCode' => $this->fareCode,
            'flightNumber' => $this->flightNumber
        ];
    }
}

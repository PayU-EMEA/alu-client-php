<?php
namespace PayU\Alu\Transformer;

use PayU\Alu\Component\AirlineInfo;
use PayU\Alu\Component\Component;
use PayU\Alu\Exception\InvalidArgumentException;

class AirlineInfoTransformer extends Transformer
{
    /**
     * @param Component $component
     * @return array
     */
    public function transform(Component $component)
    {
        if (!$component instanceof AirlineInfo) {
            throw new InvalidArgumentException("Unexpected type: " . get_class($component));
        }

        /** @var AirlineInfo $airlineInfo */
        $airlineInfo = $component;

        return array(
            'PASSENGER_NAME'        => $airlineInfo->getPassengerName(),
            'TICKET_NUMBER'         => $airlineInfo->getTicketNumber(),
            'RESTRICTED_REFUND'     => $airlineInfo->getRestrictedRefund(),
            'RESERVATION_SYSTEM'    => $airlineInfo->getReservationSystem(),
            'TRAVEL_AGENCY_CODE'    => $airlineInfo->getTravelAgencyCode(),
            'TRAVEL_AGENCY_NAME'    => $airlineInfo->getTravelAgencyName(),
            'FLIGHT_SEGMENTS'       => $airlineInfo->getFlightSegments(),
        );
    }
}

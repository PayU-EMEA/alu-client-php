<?php
namespace PayU\Alu\Test\Transformer;

use PayU\Alu\Component\AirlineInfo;
use PayU\Alu\Component\Billing;
use PayU\Alu\Transformer\AirlineInfoTransformer;

class AirlineInfoTransformerTest extends BaseTransformerTestCase
{
    public function testTransformSuccess()
    {
        $transformer = new AirlineInfoTransformer($this->config);

        $airlineInfo = new AirlineInfo();
        $airlineInfo->setPassengerName("John Doe");
        $airlineInfo->setTicketNumber("TEST123");
        $airlineInfo->setRestrictedRefund(1);
        $airlineInfo->setReservationSystem("SOME_SYSTEM");
        $airlineInfo->setTravelAgencyCode("TEST_AGC");
        $airlineInfo->setTravelAgencyName("Test Travel Agency");
        $airlineInfo->addFlightSegment("2019-01-31", "OTP", "IST", "TK", "SOME_CODE", 1, "SOME_CODE2", "TK1043");

        $expected = array(
            'PASSENGER_NAME'        => "John Doe",
            'TICKET_NUMBER'         => "TEST123",
            'RESTRICTED_REFUND'     => 1,
            'RESERVATION_SYSTEM'    => "SOME_SYSTEM",
            'TRAVEL_AGENCY_CODE'    => "TEST_AGC",
            'TRAVEL_AGENCY_NAME'    => "Test Travel Agency",
            'FLIGHT_SEGMENTS'       => array(
                array(
                    'DEPARTURE_DATE' => "2019-01-31",
                    'DEPARTURE_AIRPORT' => "OTP",
                    'DESTINATION_AIRPORT' => "IST",
                    'AIRLINE_CODE' => "TK",
                    'SERVICE_CLASS' => "SOME_CODE",
                    'STOPOVER' => 1,
                    'FARE_CODE' => "SOME_CODE2",
                    'FLIGHT_NUMBER' => "TK1043",
                )
            ),
        );

        $this->assertEquals($expected, $transformer->transform($airlineInfo));
    }

    /**
     * @expectedException \PayU\Alu\Exception\InvalidArgumentException
     */
    public function testTransformFailWithBadInput()
    {
        $transformer = new AirlineInfoTransformer($this->config);
        $transformer->transform(new Billing());
    }
}

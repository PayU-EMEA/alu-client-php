<?php


namespace PayU\PaymentsApi\PaymentsV4\Entities\Request;

use PayU\PaymentsApi\PaymentsV4\Entities\Request\Client\BillingData;
use PayU\PaymentsApi\PaymentsV4\Entities\Request\Client\DeliveryData;

final class ClientData implements \JsonSerializable
{
    /**
     * @var BillingData
     */
    private $billingData;

    /**
     * @var DeliveryData
     */
    private $deliveryData;

    /**
     * @var string
     */
    private $ip;

    /**
     * @var string
     */
    private $time;

    /**
     * @var string
     */
    private $communicationLanguage;

    /**
     * ClientData constructor.
     *
     * @param BillingData $billingData
     */
    public function __construct($billingData)
    {
        $this->billingData = $billingData;
    }

    /**
     * @param DeliveryData $deliveryData
     */
    public function setDeliveryData($deliveryData)
    {
        $this->deliveryData = $deliveryData;
    }

    /**
     * @param string $ip
     */
    public function setIp($ip)
    {
        $this->ip = $ip;
    }

    /**
     * @param string $time
     */
    public function setTime($time)
    {
        $this->time = $time;
    }

    /**
     * @param string $communicationLanguage
     */
    public function setCommunicationLanguage($communicationLanguage)
    {
        $this->communicationLanguage = $communicationLanguage;
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize()
    {
        return [
            'billing' => $this->billingData,
            'delivery' => $this->deliveryData,
            'ip' => $this->ip,
            'time' => $this->time,
            'communicationLanguage' => $this->communicationLanguage
        ];
    }
}

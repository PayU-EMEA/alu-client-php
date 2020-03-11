<?php


namespace PayU\Payments\Gateways\PaymentsApi\Entities;


class ClientData implements \JsonSerializable
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
     * @return string
     */
    public function getCommunicationLanguage()
    {
        return $this->communicationLanguage;
    }

    /**
     * @param string $communicationLanguage
     */
    public function setCommunicationLanguage($communicationLanguage)
    {
        $this->communicationLanguage = $communicationLanguage;
    }

    /**
     * ClientData constructor.
     * @param BillingData $billingData
     */
    public function __construct($billingData)
    {
        $this->billingData = $billingData;
    }

    /**
     * @return BillingData
     */
    public function getBillingData()
    {
        return $this->billingData;
    }

    /**
     * @return DeliveryData
     */
    public function getDeliveryData()
    {
        return $this->deliveryData;
    }

    /**
     * @return string
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * @return string
     */
    public function getTime()
    {
        return $this->time;
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

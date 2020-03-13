<?php

namespace PayU\Alu;

/**
 * Class Request
 * @package PayU\Alu
 */
class Request
{
    /**
     * @var MerchantConfig
     */
    private $merchantConfig;

    /**
     * @var Order
     */
    private $order;

    /**
     * @var Card
     */
    private $card = null;

    /**
     * @var CardToken
     */
    private $cardToken = null;

    /**
     * @var StoredCredentials
     */
    private $storedCredentials = null;


    /** @var $threeDSTwoZero StrongCustomerAuthentication */
    private $strongCustomerAuthentication;

    /**
     * @var Billing
     */
    private $billingData;

    /**
     * @var AbstractCommonAddress
     */
    private $deliveryData = null;

    /**
     * @var User
     */
    private $user;

    /**
     * @var FX
     */
    private $fx;

    /**
     * @var array
     */
    private $internalArray;
    /**
     * @var String
     */
    private $paymentsApiVersion;

    /**
     * @param MerchantConfig $merchantConfig
     * @param Order $order
     * @param Billing $billing
     * @param AbstractCommonAddress $delivery
     * @param User $user
     * @param String $paymentsApiVersion
     */
    public function __construct(
        MerchantConfig $merchantConfig,
        Order $order,
        Billing $billing,
        AbstractCommonAddress $delivery = null,
        User $user = null,
        $paymentsApiVersion = 'v3'
    ) {
        $this->merchantConfig = $merchantConfig;
        $this->order = $order;
        $this->billingData = $billing;
        $this->deliveryData = $delivery;
        $this->user = $user;
        $this->paymentsApiVersion = $paymentsApiVersion;
    }

    /**
     * @return Order
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * @return Card
     */
    public function getCard()
    {
        return $this->card;
    }

    /**
     * @return CardToken
     */
    public function getCardToken()
    {
        return $this->cardToken;
    }

    /**
     * @return StoredCredentials
     */
    public function getStoredCredentials()
    {
        return $this->storedCredentials;
    }

    /**
     * @return StrongCustomerAuthentication
     */
    public function getStrongCustomerAuthentication()
    {
        return $this->strongCustomerAuthentication;
    }

    /**
     * @return Billing
     */
    public function getBillingData()
    {
        return $this->billingData;
    }

    /**
     * @return AbstractCommonAddress
     */
    public function getDeliveryData()
    {
        return $this->deliveryData;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @return FX
     */
    public function getFx()
    {
        return $this->fx;
    }

    /**
     * @return MerchantConfig
     */
    public function getMerchantConfig()
    {
        return $this->merchantConfig;
    }

    /**
     * @return String
     */
    public function getPaymentsApiVersion()
    {
        return $this->paymentsApiVersion;
    }

    /**
     * @param Card $card
     */
    public function setCard(Card $card)
    {
        $this->card = $card;
    }

    /**
     * @param CardToken $cardToken
     */
    public function setCardToken(CardToken $cardToken)
    {
        $this->cardToken = $cardToken;
    }

    /**
     * @param StoredCredentials $storedCredentials
     */
    public function setStoredCredentials(StoredCredentials $storedCredentials)
    {
        $this->storedCredentials = $storedCredentials;
    }

    /**
     * @param StrongCustomerAuthentication $strongCustomerAuthentication
     */
    public function setStrongCustomerAuthentication(StrongCustomerAuthentication $strongCustomerAuthentication)
    {
        $this->strongCustomerAuthentication = $strongCustomerAuthentication;
    }

    public function setFx(FX $fx)
    {
        $this->fx = $fx;
    }

}

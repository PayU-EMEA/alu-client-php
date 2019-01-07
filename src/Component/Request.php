<?php

namespace PayU\Alu\Component;

/**
 * Class Request
 * @package PayU\Alu
 */
class Request implements Component
{
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
     * @var Billing
     */
    private $billingData;

    /**
     * @var AbstractAddress
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
     * Request constructor.
     * @param Order $order
     * @param Billing $billing
     * @param Delivery|null $delivery
     * @param User|null $user
     */
    public function __construct(Order $order, Billing $billing, Delivery $delivery = null, User $user = null)
    {
        $this->order = $order;
        $this->billingData = $billing;
        $this->deliveryData = $delivery;
        $this->user = $user;
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
     * @return Billing
     */
    public function getBillingData()
    {
        return $this->billingData;
    }

    /**
     * @return Delivery
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

    public function setFx(Fx $fx)
    {
        $this->fx = $fx;
    }
}

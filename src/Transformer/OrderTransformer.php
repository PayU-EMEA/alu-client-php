<?php
namespace PayU\Alu\Transformer;

use PayU\Alu\Component\Component;
use PayU\Alu\Component\Order;
use PayU\Alu\Exception\InvalidArgumentException;
use PayU\Alu\MerchantConfig;

class OrderTransformer extends Transformer
{
    /** @var AirlineInfoTransformer */
    private $airlineInfoTransformer;

    public function __construct(MerchantConfig $config)
    {
        parent::__construct($config);
        $this->airlineInfoTransformer = new AirlineInfoTransformer($config);
    }

    /**
     * @param Component $component
     * @return array
     */
    public function transform(Component $component)
    {
        if (!$component instanceof Order) {
            throw new InvalidArgumentException("Unexpected type: " . get_class($component));
        }

        /** @var Order $order */
        $order = $component;

        $data = array(
            'ORDER_REF' => $order->getOrderRef(),
            'ORDER_DATE' => $order->getOrderDate(),
            'ORDER_SHIPPING' => $order->getShippingCost(),
            'PRICES_CURRENCY' => $order->getCurrency(),
            'DISCOUNT' => $order->getDiscount(),
            'PAY_METHOD' => $order->getPayMethod(),
            'SELECTED_INSTALLMENTS_NUMBER' => $order->getInstallmentsNumber(),
            'CARD_PROGRAM_NAME' => $order->getCardProgramName(),
            'BACK_REF' => $order->getBackRef(),
            'ALIAS' => $order->getAlias(),
            'CC_NUMBER_RECIPIENT' => $order->getCcNumberRecipient(),
            'USE_LOYALTY_POINTS' => $order->getUseLoyaltyPoints(),
            'LOYALTY_POINTS_AMOUNT' => $order->getLoyaltyPointsAmount(),
            'CAMPAIGN_TYPE' => $order->getCampaignType(),
        );

        if (is_array($order->getCustomParams())) {
            foreach ($order->getCustomParams() as $paramName => $paramValue) {
                $data[$paramName] = $paramValue;
            }
        }

        if($order->getAirlineInfo()) {
            $data["AIRLINE_INFO"] = $this->airlineInfoTransformer->transform($order->getAirlineInfo());
        }

        return $data;
    }
}
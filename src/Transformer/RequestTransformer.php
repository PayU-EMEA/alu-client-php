<?php
namespace PayU\Alu\Transformer;

use PayU\Alu\Component\Component;
use PayU\Alu\Exception\InvalidArgumentException;
use PayU\Alu\MerchantConfig;
use PayU\Alu\Component\Request;

class RequestTransformer extends Transformer
{
    /** @var BillingTransformer */
    private $billingTransformer;

    /** @var  DeliveryTransformer */
    private $deliveryTransformer;

    /** @var OrderTransformer */
    private $orderTransformer;

    /** @var CardTransformer */
    private $cardTransformer;

    /** @var CardTokenTransformer */
    private $cardTokenTransformer;

    /** @var ProductCollectionTransformer */
    private $productCollectionTransformer;

    /** @var FxTransformer */
    private $fxTransformer;

    /** @var UserTransformer */
    private $userTransformer;

    public function __construct(MerchantConfig $config)
    {
        parent::__construct($config);
        $this->billingTransformer = new BillingTransformer($config);
        $this->deliveryTransformer = new DeliveryTransformer($config);
        $this->orderTransformer = new OrderTransformer($config);
        $this->cardTransformer = new CardTransformer($config);
        $this->cardTokenTransformer = new CardTokenTransformer($config);
        $this->productCollectionTransformer = new ProductCollectionTransformer($config);
        $this->fxTransformer = new FxTransformer($config);
        $this->userTransformer = new UserTransformer($config);
    }

    /**
     * @param Component $component
     * @return array
     */
    public function transform(Component $component)
    {
        if (!$component instanceof Request) {
            throw new InvalidArgumentException("Unexpected type: " . get_class($component));
        }

        /** @var Request $request */
        $request = $component;

        if ((!is_null($request->getCard()) && !is_null($request->getCardToken())) ||
                (is_null($request->getCard()) && is_null($request->getCardToken()))) {
            throw new InvalidArgumentException("You must choose either card or card token for payment.");
        }

        $data = array();
        $data['MERCHANT'] = $this->config->getMerchantCode();
        $data = array_merge(
            $data,
            $this->orderTransformer->transform($request->getOrder()),
            $this->billingTransformer->transform($request->getBillingData()),
            $this->productCollectionTransformer->transform($request->getOrder())
        );

        $data = $this->addOptionalParameters($request, $data);

        ksort($data);

        return $data;
    }

    /**
     * @param $request
     * @param $data
     * @return array
     */
    protected function addOptionalParameters(Request $request, $data)
    {
        if (!is_null($request->getDeliveryData())) {
            $data = array_merge($data, $this->deliveryTransformer->transform($request->getDeliveryData()));
        }

        if (!is_null($request->getCard())) {
            $data = array_merge($data, $this->cardTransformer->transform($request->getCard()));
        }

        if (!is_null($request->getCardToken())) {
            $data = array_merge($data, $this->cardTokenTransformer->transform($request->getCardToken()));
        }

        if (!is_null($request->getUser())) {
            $data = array_merge($data, $this->userTransformer->transform($request->getUser()));
        }

        if (!is_null($request->getFx())) {
            $data = array_merge($data, $this->fxTransformer->transform($request->getFx()));
            return $data;
        }
        return $data;
    }
}

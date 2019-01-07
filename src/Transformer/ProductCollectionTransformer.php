<?php
namespace PayU\Alu\Transformer;

use PayU\Alu\Component\Component;
use PayU\Alu\Component\Order;
use PayU\Alu\Component\Product;
use PayU\Alu\Exception\InvalidArgumentException;

class ProductCollectionTransformer extends Transformer
{
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

        $data = array();
        /**
         * @var int $index
         * @var Product $product
         */
        foreach ($order->getProducts() as $index => $product) {
            $data['ORDER_PNAME'][$index] = $product->getName();
            $data['ORDER_PGROUP'][$index] = $product->getProductGroup();
            $data['ORDER_PCODE'][$index] = $product->getCode();
            $data['ORDER_PINFO'][$index] = $product->getInfo();
            $data['ORDER_PRICE'][$index] = $product->getPrice();
            $data['ORDER_VAT'][$index] = $product->getVAT();
            $data['ORDER_PRICE_TYPE'][$index] = $product->getPriceType();
            $data['ORDER_QTY'][$index] = $product->getQuantity();
            $data['ORDER_MPLACE_MERCHANT'][$index] = $product->getMarketPlaceMerchantCode();
            $data['ORDER_VER'][$index] = $product->getProductVersion();
        }
        return $data;
    }
}

<?php
namespace PayU\Alu\Test\Transformer;

use PayU\Alu\Component\Billing;
use PayU\Alu\Component\Order;
use PayU\Alu\Component\Product;
use PayU\Alu\Transformer\ProductCollectionTransformer;

class ProductCollectionTransformerTest extends BaseTransformerTestCase
{
    public function testTransformSuccess()
    {
        $transformer = new ProductCollectionTransformer($this->config);
        $order = new Order();
        for ($i = 0; $i < 3; $i++) {
            $product = new Product();
            $product->withCode("P" . $i);
            $product->withName("Some product #" . $i);
            $product->withInfo("Some product Information #" . $i);
            $product->withMarketPlaceMerchantCode("MC" . $i);
            $product->withPrice(100+$i);
            $product->withPriceType(Product::PRICE_TYPE_NET);
            $product->withVAT(10+$i);
            $product->withQuantity(1 + $i);
            $product->withProductVersion("1.0." . $i);
            $product->withProductGroup("G" . $i);
            $order->addProduct($product);
        }
        $expected = array();
        for ($i = 0; $i < 3; $i++) {
            $expected['ORDER_PNAME'][$i] = "Some product #" . $i;
            $expected['ORDER_PGROUP'][$i] = "G" . $i;
            $expected['ORDER_PCODE'][$i] = "P" . $i;
            $expected['ORDER_PINFO'][$i] = "Some product Information #" . $i;
            $expected['ORDER_PRICE'][$i] = 100 + $i;
            $expected['ORDER_VAT'][$i] = 10 + $i;
            $expected['ORDER_PRICE_TYPE'][$i] = Product::PRICE_TYPE_NET;
            $expected['ORDER_QTY'][$i] = 1 + $i;
            $expected['ORDER_MPLACE_MERCHANT'][$i] = "MC" . $i;
            $expected['ORDER_VER'][$i] = "1.0." . $i;
        }
        $this->assertEquals($expected, $transformer->transform($order));
    }

    /**
     * @expectedException \PayU\Alu\Exception\InvalidArgumentException
     */
    public function testTransformFailWithBadInput()
    {
        $transformer = new ProductCollectionTransformer($this->config);
        $transformer->transform(new Billing());
    }
}
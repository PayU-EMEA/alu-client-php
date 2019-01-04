<?php
namespace PayU\Alu\Test\Transformer;

use PayU\Alu\Component\Billing;
use PayU\Alu\Component\CardToken;
use PayU\Alu\Transformer\CardTokenTransformer;

class CardTokenTransformerTest extends BaseTransformerTestCase
{
    public function testTransformSuccess()
    {
        $transformer = new CardTokenTransformer($this->config);
        $cardToken = new CardToken("SOME_TOKEN", "000");
        $expected = array(
            "CC_TOKEN"  => $cardToken->getToken(),
            "CC_CVV"    => $cardToken->getCvv()
        );
        $this->assertEquals($expected, $transformer->transform($cardToken));
    }

    public function testTransformSuccessWithoutCvv()
    {
        $transformer = new CardTokenTransformer($this->config);
        $cardToken = new CardToken("SOME_TOKEN");
        $expected = array(
            "CC_TOKEN"  => $cardToken->getToken(),
            "CC_CVV"    => ""
        );

        $this->assertEquals($expected, $transformer->transform($cardToken));
    }

    /**
     * @expectedException \PayU\Alu\Exception\InvalidArgumentException
     */
    public function testTransformFailWithBadInput()
    {
        $transformer = new CardTokenTransformer($this->config);
        $transformer->transform(new Billing());
    }
}
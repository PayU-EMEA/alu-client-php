<?php
namespace PayU\Alu\Test\Transformer;

use PayU\Alu\Component\Billing;
use PayU\Alu\Component\Fx;
use PayU\Alu\Transformer\FxTransformer;

class FxTransformerTest extends BaseTransformerTestCase
{
    public function testTransformSuccess()
    {
        $transformer = new FxTransformer($this->config);
        $fx = new Fx("EUR", 4.56);
        $expected = array(
            'AUTHORIZATION_CURRENCY'      => "EUR",
            'AUTHORIZATION_EXCHANGE_RATE' => 4.56,
        );
        $this->assertEquals($expected, $transformer->transform($fx));
    }

    /**
     * @expectedException \PayU\Alu\Exception\InvalidArgumentException
     */
    public function testTransformFailWithBadInput()
    {
        $transformer = new FxTransformer($this->config);
        $transformer->transform(new Billing());
    }
}

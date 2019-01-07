<?php
namespace PayU\Alu\Test\Transformer;

use PayU\Alu\Component\Billing;
use PayU\Alu\Component\Card;
use PayU\Alu\Transformer\CardTransformer;

class CardTransformerTest extends BaseTransformerTestCase
{
    public function testTransformSuccess()
    {
        $transformer = new CardTransformer($this->config);
        $card = new Card("5105105105105100", 1, 2018, "000", "John Doe");
        $expected = array(
            'CC_NUMBER' => $card->getCardNumber(),
            'EXP_MONTH' => $card->getCardExpirationMonth(),
            'EXP_YEAR'  => $card->getCardExpirationYear(),
            'CC_CVV'    => $card->getCardCVV(),
            'CC_OWNER'  => $card->getCardOwnerName(),
        );

        $this->assertEquals($expected, $transformer->transform($card));
    }

    public function testTransformSuccessWithEnableStore()
    {
        $transformer = new CardTransformer($this->config);
        $card = new Card("5105105105105100", 1, 2018, "000", "John Doe");
        $card->enableTokenCreation();
        $expected = array(
            'CC_NUMBER' => $card->getCardNumber(),
            'EXP_MONTH' => $card->getCardExpirationMonth(),
            'EXP_YEAR'  => $card->getCardExpirationYear(),
            'CC_CVV'    => $card->getCardCVV(),
            'CC_OWNER'  => $card->getCardOwnerName(),
            'LU_ENABLE_TOKEN' => "1"
        );

        $this->assertEquals($expected, $transformer->transform($card));
    }

    /**
     * @expectedException \PayU\Alu\Exception\InvalidArgumentException
     */
    public function testTransformFailWithBadInput()
    {
        $transformer = new CardTransformer($this->config);
        $transformer->transform(new Billing());
    }
}

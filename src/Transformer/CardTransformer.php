<?php
namespace PayU\Alu\Transformer;

use PayU\Alu\Component\Card;
use PayU\Alu\Component\Component;
use PayU\Alu\Exception\InvalidArgumentException;

class CardTransformer extends Transformer
{
    /**
     * @param Component $component
     * @return array
     */
    public function transform(Component $component)
    {
        if (!$component instanceof Card) {
            throw new InvalidArgumentException("Unexpected type: " . get_class($component));
        }

        /** @var Card $card */
        $card = $component;

        $data =  array(
            'CC_NUMBER' => $card->getCardNumber(),
            'EXP_MONTH' => $card->getCardExpirationMonth(),
            'EXP_YEAR'  => $card->getCardExpirationYear(),
            'CC_CVV'    => $card->getCardCVV(),
            'CC_OWNER'  => $card->getCardOwnerName(),
        );

        if ($card->isEnableTokenCreation()) {
            $data['LU_ENABLE_TOKEN'] = '1';
        }

        return $data;
    }
}

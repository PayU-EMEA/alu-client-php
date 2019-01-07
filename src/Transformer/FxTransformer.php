<?php
namespace PayU\Alu\Transformer;

use PayU\Alu\Component\Component;
use PayU\Alu\Component\Fx;
use PayU\Alu\Exception\InvalidArgumentException;

class FxTransformer extends Transformer
{
    /**
     * @param Component $component
     * @return array
     */
    public function transform(Component $component)
    {
        if (!$component instanceof Fx) {
            throw new InvalidArgumentException("Unexpected type: " . get_class($component));
        }

        /** @var Fx $fx */
        $fx = $component;

        return array(
            'AUTHORIZATION_CURRENCY'      => $fx->getAuthorizationCurrency(),
            'AUTHORIZATION_EXCHANGE_RATE' => $fx->getAuthorizationExchangeRate(),
        );
    }
}

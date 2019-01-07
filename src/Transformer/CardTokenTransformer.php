<?php
namespace PayU\Alu\Transformer;

use PayU\Alu\Component\CardToken;
use PayU\Alu\Component\Component;
use PayU\Alu\Exception\InvalidArgumentException;

class CardTokenTransformer extends Transformer
{
    /**
     * @param Component $component
     * @return array
     */
    public function transform(Component $component)
    {
        if (!$component instanceof CardToken) {
            throw new InvalidArgumentException("Unexpected type: " . get_class($component));
        }

        /** @var CardToken $cardToken */
        $cardToken = $component;

        $data = array();

        $data['CC_TOKEN'] = $cardToken->getToken();
        if ($cardToken->hasCvv()) {
            $data['CC_CVV'] = $cardToken->getCvv();
        } else {
            $data['CC_CVV'] = '';
        }

        return $data;
    }
}

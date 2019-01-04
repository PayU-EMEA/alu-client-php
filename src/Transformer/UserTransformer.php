<?php
namespace PayU\Alu\Transformer;

use PayU\Alu\Component\Component;
use PayU\Alu\Component\User;
use PayU\Alu\Exception\InvalidArgumentException;

class UserTransformer extends Transformer
{
    /**
     * @param Component $component
     * @return array
     */
    public function transform(Component $component)
    {
        if (!$component instanceof User) {
            throw new InvalidArgumentException("Unexpected type: " . get_class($component));
        }

        /** @var User $user */
        $user = $component;

        return array(
            'CLIENT_IP'   => $user->getUserIPAddress(),
            'CLIENT_TIME' => $user->getClientTime()
        );
    }
}
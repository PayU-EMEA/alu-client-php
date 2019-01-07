<?php
namespace PayU\Alu\Test\Transformer;

use PayU\Alu\Component\Billing;
use PayU\Alu\Component\User;
use PayU\Alu\Transformer\UserTransformer;

class UserTransformerTest extends BaseTransformerTestCase
{
    public function testTransformSuccess()
    {
        $clientTime = "2019-01-01 23:00:00";
        $clientIp = "127.0.0.1";
        $user = new User($clientIp, $clientTime);
        $transformer = new UserTransformer($this->config);
        $expected = array(
            'CLIENT_IP' => $clientIp,
            'CLIENT_TIME' => $clientTime
        );
        $this->assertEquals($expected, $transformer->transform($user));
    }

    /**
     * @expectedException \PayU\Alu\Exception\InvalidArgumentException
     */
    public function testTransformFailWithBadInput()
    {
        $transformer = new UserTransformer($this->config);
        $transformer->transform(new Billing());
    }
}

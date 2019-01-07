<?php
namespace PayU\Alu\Test\Transformer;

use PayU\Alu\MerchantConfig;
use PayU\Alu\Platform;
use PHPUnit\Framework\TestCase;

class BaseTransformerTestCase extends TestCase
{
    /** @var MerchantConfig */
    protected $config;

    public function setUp()
    {
        $this->config = new MerchantConfig('CC5857', 'SECRET_KEY', Platform::ROMANIA);
    }
}

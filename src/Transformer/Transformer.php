<?php
namespace PayU\Alu\Transformer;

use PayU\Alu\Component\Component;
use PayU\Alu\MerchantConfig;

abstract class Transformer
{
    /** @var MerchantConfig */
    protected $config;

    /**
     * Transformer constructor.
     * @param MerchantConfig $config
     */
    public function __construct(MerchantConfig $config)
    {
        $this->config = $config;
    }

    /**
     * @param Component $component
     * @return array
     */
    abstract public function transform(Component $component);
}
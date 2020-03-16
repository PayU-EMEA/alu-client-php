<?php


namespace PaymentsApi\Entities;

class AuthorizationRequest implements \JsonSerializable
{
    /**
     * @var string
     */
    private $merchantPaymentReference;

    /**
     * @var string
     */
    private $currency;

    /**
     * @var string
     */
    private $returnUrl;

    /**
     * @var AuthorizationData
     */
    private $authorization;

    /**
     * @var ClientData
     */
    private $client;

    /**
     * @var ProductData[]
     */
    private $products;

    /**
     * @var MerchantData
     */
    private $merchant;

    /**
     * AuthorizationRequest constructor.
     *
     * @param string $merchantPaymentReference
     * @param string $currency
     * @param string $returnUrl
     * @param AuthorizationData $authorization
     * @param ClientData $client
     * @param ProductData[] $products
     */
    public function __construct(
        $merchantPaymentReference,
        $currency,
        $returnUrl,
        $authorization,
        $client,
        $products
    ) {
        $this->merchantPaymentReference = $merchantPaymentReference;
        $this->currency = $currency;
        $this->returnUrl = $returnUrl;
        $this->authorization = $authorization;
        $this->client = $client;
        $this->products = $products;
    }

    /**
     * @param MerchantData $merchant
     */
    public function setMerchant($merchant)
    {
        $this->merchant = $merchant;
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize()
    {
        return [
            'merchantPaymentReference' => $this->merchantPaymentReference,
            'currency' => $this->currency,
            'returnUrl' => $this->returnUrl,
            'authorization' => $this->authorization,
            'client' => $this->client,
            'merchant' => $this->merchant,
            'products' => $this->products
        ];
    }
}

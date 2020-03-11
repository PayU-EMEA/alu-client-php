<?php


namespace PayU\Payments\Gateways\PaymentsApi\Entities;

;

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
     * @return MerchantData
     */
    public function getMerchant()
    {
        return $this->merchant;
    }

    /**
     * @param MerchantData $merchant
     */
    public function setMerchant($merchant)
    {
        $this->merchant = $merchant;
    }

    /**
     * @return string
     */
    public function getMerchantPaymentReference()
    {
        return $this->merchantPaymentReference;
    }

    /**
     * @param string $merchantPaymentReference
     */
    public function setMerchantPaymentReference($merchantPaymentReference)
    {
        $this->merchantPaymentReference = $merchantPaymentReference;
    }

    /**
     * @return string
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @param string $currency
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;
    }

    /**
     * @return string
     */
    public function getReturnUrl()
    {
        return $this->returnUrl;
    }

    /**
     * @param string $returnUrl
     */
    public function setReturnUrl($returnUrl)
    {
        $this->returnUrl = $returnUrl;
    }

    /**
     * @return AuthorizationData
     */
    public function getAuthorization()
    {
        return $this->authorization;
    }

    /**
     * @param AuthorizationData $authorization
     */
    public function setAuthorization($authorization)
    {
        $this->authorization = $authorization;
    }

    /**
     * @return ClientData
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @param ClientData $client
     */
    public function setClient($client)
    {
        $this->client = $client;
    }

    /**
     * @return ProductData[]
     */
    public function getProducts()
    {
        return $this->products;
    }

    /**
     * @param ProductData[] $products
     */
    public function setProducts($products)
    {
        $this->products = $products;
    }


    /**
     * AuthorizationRequest constructor.
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
    )
    {
        $this->merchantPaymentReference = $merchantPaymentReference;
        $this->currency = $currency;
        $this->returnUrl = $returnUrl;
        $this->authorization = $authorization;
        $this->client = $client;
        $this->products = $products;
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize()
    {
        $arr = [
            'merchantPaymentReference' => $this->merchantPaymentReference,
            'currency' => $this->currency,
            'returnUrl' => $this->returnUrl,
            'authorization' => $this->authorization,
            'client' => $this->client,
            'merchant' => $this->merchant,
            'products' => $this->products
        ];

//        $productsArray = [];
//        for($i = 0; $i< count($this->products); $i++) {
//            $productsArray[$i] = json_encode($this->products[$i]);
//        }
//
//        $arr['products'] = $productsArray;

        return $arr;
    }
}
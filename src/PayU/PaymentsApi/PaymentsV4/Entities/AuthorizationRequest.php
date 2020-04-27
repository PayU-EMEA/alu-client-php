<?php


namespace PayU\PaymentsApi\PaymentsV4\Entities;

final class AuthorizationRequest implements \JsonSerializable
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

    /** @var AirlineInfoData */
    private $airlineInfoData;

    /** @var StoredCredentialsData */
    private $storedCredentialsData;

    /** @var ThreeDSecure */
    private $threeDSecure;

    /**
     * @param StoredCredentialsData $storedCredentialsData
     */
    public function setStoredCredentialsData($storedCredentialsData)
    {
        $this->storedCredentialsData = $storedCredentialsData;
    }

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
     * @param AirlineInfoData $airlineInfoData
     */
    public function setAirlineInfoData($airlineInfoData)
    {
        $this->airlineInfoData = $airlineInfoData;
    }

    /**
     * @param ThreeDSecure $threeDSecure
     */
    public function setThreeDSecure($threeDSecure)
    {
        $this->threeDSecure = $threeDSecure;
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
            'products' => $this->products,
            'airlineInfo' => $this->airlineInfoData,
            'threeDSecure' => $this->threeDSecure,
            'storedCredentials' => $this->storedCredentialsData
        ];
    }
}

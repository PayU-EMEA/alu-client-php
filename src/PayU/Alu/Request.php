<?php

namespace PayU\Alu;

/**
 * Class Request
 * @package PayU\Alu
 */
class Request
{
    /**
     * @var MerchantConfig
     */
    private $merchantConfig;

    /**
     * @var Order
     */
    private $order;

    /**
     * @var Card
     */
    private $card = null;

    /**
     * @var CardToken
     */
    private $cardToken = null;

    /**
     * @var Billing
     */
    private $billingData;

    /**
     * @var AbstractCommonAddress
     */
    private $deliveryData = null;

    /**
     * @var User
     */
    private $user;

    /**
     * @var array
     */
    private $internalArray;

    /**
     * @param MerchantConfig $merchantConfig
     * @param Order $order
     * @param Billing $billing
     * @param AbstractCommonAddress $delivery
     * @param User $user
     */
    public function  __construct(MerchantConfig $merchantConfig, Order $order, Billing $billing,
                                 AbstractCommonAddress $delivery = null, User $user = null
    ) {
        $this->merchantConfig = $merchantConfig;
        $this->order = $order;
        $this->billingData = $billing;
        $this->deliveryData = $delivery;
        $this->user = $user;
    }

    /**
     * @param Card $card
     */
    public function setCard(Card $card)
    {
        $this->card = $card;
    }

    /**
     * @param CardToken $cardToken
     */
    public function setCardToken(CardToken $cardToken)
    {
        $this->cardToken = $cardToken;
    }

    /**
     * @return array
     */
    private function transformObject2Array()
    {
        $this->internalArray = array();
        $this->internalArray['MERCHANT'] = $this->merchantConfig->getMerchantCode();
        $this->internalArray['ORDER_REF'] = $this->order->getOrderRef();
        $this->internalArray['ORDER_DATE'] = $this->order->getOrderDate();

        $cnt = 0;
        /**
         * @var Product $product
         */
        foreach ($this->order->getProducts() as $product) {
            $this->internalArray['ORDER_PNAME'][$cnt] = $product->getName();
            $this->internalArray['ORDER_PGROUP'][$cnt] = $product->getProductGroup();
            $this->internalArray['ORDER_PCODE'][$cnt] = $product->getCode();
            $this->internalArray['ORDER_PINFO'][$cnt] = $product->getInfo();
            $this->internalArray['ORDER_PRICE'][$cnt] = $product->getPrice();
            $this->internalArray['ORDER_QTY'][$cnt] = $product->getQuantity();
            $this->internalArray['ORDER_MPLACE_MERCHANT'][$cnt] = $product->getMarketPlaceMerchantCode();
            $this->internalArray['ORDER_VER'][$cnt] = $product->getProductVersion();
            $cnt++;
        }

        $this->internalArray['ORDER_SHIPPING'] = $this->order->getShippingCost();
        $this->internalArray['PRICES_CURRENCY'] = $this->order->getCurrency();
        $this->internalArray['DISCOUNT'] = $this->order->getDiscount();
        $this->internalArray['PAY_METHOD'] = $this->order->getPayMethod();

        if (!is_null($this->card) && is_null($this->cardToken)) {
            $this->internalArray['CC_NUMBER'] = $this->card->getCardNumber();
            $this->internalArray['EXP_MONTH'] = $this->card->getCardExpirationMonth();
            $this->internalArray['EXP_YEAR'] = $this->card->getCardExpirationYear();
            $this->internalArray['CC_CVV'] = $this->card->getCardCVV();
            $this->internalArray['CC_OWNER'] = $this->card->getCardOwnerName();
        }

        $this->internalArray['SELECTED_INSTALLMENTS_NUMBER'] = $this->order->getInstallmentsNumber();
        $this->internalArray['CARD_PROGRAM_NAME'] = $this->order->getCardProgramName();

        if (is_null($this->card) && !is_null($this->cardToken)) {
            $this->internalArray['CC_TOKEN'] = $this->cardToken->getToken();
            if ($this->cardToken->hasCvv()) {
                $this->internalArray['CC_CVV'] = $this->cardToken->getCvv();
            }
        }

        $this->internalArray['BACK_REF'] = $this->order->getBackRef();
        $this->internalArray['ALIAS'] = $this->order->getAlias();

        if (!empty($this->user)) {
            $this->internalArray['CLIENT_IP'] = $this->user->getUserIPAddress();
            $this->internalArray['CLIENT_TIME'] = $this->user->getClientTime();
        }

        $this->internalArray['BILL_LNAME'] = $this->billingData->getLastName();
        $this->internalArray['BILL_FNAME'] = $this->billingData->getFirstName();
        $this->internalArray['BILL_CISERIAL'] = $this->billingData->getIdentityCardSeries();
        $this->internalArray['BILL_CINUMBER'] = $this->billingData->getIdentityCardNumber();
        $this->internalArray['BILL_CIISSUER'] = $this->billingData->getIdentityCardIssuer();
        $this->internalArray['BILL_CNP'] = $this->billingData->getPersonalNumericCode();
        $this->internalArray['BILL_COMPANY'] = $this->billingData->getCompany();
        $this->internalArray['BILL_FISCALCODE'] = $this->billingData->getCompanyFiscalCode();
        $this->internalArray['BILL_REGNUMBER'] = $this->billingData->getCompanyRegistrationNumber();
        $this->internalArray['BILL_BANK'] = $this->billingData->getCompanyBank();
        $this->internalArray['BILL_BANKACCOUNT'] = $this->billingData->getCompanyBankAccountNumber();
        $this->internalArray['BILL_EMAIL'] = $this->billingData->getEmail();
        $this->internalArray['BILL_PHONE'] = $this->billingData->getPhoneNumber();
        $this->internalArray['BILL_FAX'] = $this->billingData->getFaxNumber();
        $this->internalArray['BILL_ADDRESS'] = $this->billingData->getAddressLine1();
        $this->internalArray['BILL_ADDRESS2'] = $this->billingData->getAddressLine2();
        $this->internalArray['BILL_ZIPCODE'] = $this->billingData->getZipCode();
        $this->internalArray['BILL_CITY'] = $this->billingData->getCity();
        $this->internalArray['BILL_STATE'] = $this->billingData->getState();
        $this->internalArray['BILL_COUNTRYCODE'] = $this->billingData->getCountryCode();

        if (!empty($this->deliveryData)) {
            $this->internalArray['DELIVERY_LNAME'] = $this->deliveryData->getLastName();
            $this->internalArray['DELIVERY_FNAME'] = $this->deliveryData->getFirstName();
            $this->internalArray['DELIVERY_COMPANY'] = $this->deliveryData->getCompany();
            $this->internalArray['DELIVERY_PHONE'] = $this->deliveryData->getPhoneNumber();
            $this->internalArray['DELIVERY_ADDRESS'] = $this->deliveryData->getAddressLine1();
            $this->internalArray['DELIVERY_ADDRESS2'] = $this->deliveryData->getAddressLine2();
            $this->internalArray['DELIVERY_ZIPCODE'] = $this->deliveryData->getZipCode();
            $this->internalArray['DELIVERY_CITY'] = $this->deliveryData->getState();
            $this->internalArray['DELIVERY_STATE'] = $this->deliveryData->getState();
            $this->internalArray['DELIVERY_COUNTRYCODE'] = $this->deliveryData->getCountryCode();
            $this->internalArray['DELIVERY_EMAIL'] = $this->deliveryData->getEmail();
        }

        if ($this->order->getLuEnabledToken() == 1) {
            $this->internalArray['LU_ENABLE_TOKEN'] = 1;
            $this->internalArray['LU_TOKEN_TYPE'] = $this->order->getLuTokenType();
            $this->internalArray['TOKEN_EXPIRATION_TIME'] = $this->order->getLuTokenExpirationTime();
        }

        $this->internalArray['CC_NUMBER_RECIPIENT'] = $this->order->getCcNumberRecipient();

        if (is_array($this->order->getCustomParams())) {
            foreach ($this->order->getCustomParams() as $paramName => $paramValue) {
                $this->internalArray[$paramName] = $paramValue;
            }
        }

        ksort($this->internalArray);
        return $this->internalArray;
    }

    /**
     * @return array
     */
    public function getRequestParams()
    {
        if (empty($this->internalArray)) {
            return $this->transformObject2Array();
        }
        return $this->internalArray;
    }

    /**
     * @param string $hash
     */
    public function setOrderHash($hash)
    {
        $this->internalArray['ORDER_HASH'] = $hash;
    }
}

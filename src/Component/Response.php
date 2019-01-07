<?php

namespace PayU\Alu\Component;

/**
 * Class Response
 * @package PayU\Alu
 */
class Response implements Component
{
    /**
     * @var string
     */
    private $refno;

    /**
     * @var string
     */
    private $alias;

    /**
     * @var string
     */
    private $status;

    /**
     * @var string
     */
    private $returnCode;

    /**
     * @var string
     */
    private $returnMessage;

    /**
     * @var string
     */
    private $date;

    /**
     * @var string
     */
    private $threeDsUrl;

    /**
     * @var string
     */
    private $hash;

    /**
     * @var string
     */
    private $amount;

    /**
     * @var string
     */
    private $currency;

    /**
     * @var string
     */
    private $installmentsNo;

    /**
     * @var string
     */
    private $cardProgramName;

    /**
     * @var string
     */
    private $orderRef;

    /**
     * @var string
     */
    private $authCode;

    /**
     * @var string
     */
    private $rrn;

    /**
     * @var string[]
     */
    private $additionalResponseParameters = array();

    /**
     * @var array
     */
    private $internalArray = array();

    /**
     * @var ResponseWireAccount[]
     */
    private $wireAccounts = array();

    /**
     * @var string
     */
    private $tokenHash;

    /**
     * @var string
     */
    private $urlRedirect;

    private $isThreeD;

    /**
     * @return mixed
     */
    public function isThreeDs()
    {
        return $this->isThreeD;
    }

    /**
     * @param mixed $isThreeD
     * @return Response
     */
    public function setIsThreeDs($isThreeD)
    {
        $this->isThreeD = $isThreeD;
        return $this;
    }

    /**
     * @return ResponseWireAccount[]
     */
    public function getWireAccounts()
    {
        return $this->wireAccounts;
    }

    /**
     * @param ResponseWireAccount[] $wireAccounts
     */
    public function setWireAccounts(array $wireAccounts)
    {
        $this->wireAccounts = $wireAccounts;
    }

    /**
     * @param ResponseWireAccount $account
     */
    public function addWireAccount(ResponseWireAccount $account)
    {
        $this->wireAccounts[] = $account;
    }

    /**
     * @param string $alias
     */
    public function setAlias($alias)
    {
        $this->alias = $alias;
    }

    /**
     * @return string
     */
    public function getAlias()
    {
        return $this->alias;
    }

    /**
     * @param string $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

    /**
     * @return string
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param string $hash
     */
    public function setHash($hash)
    {
        $this->hash = $hash;
    }

    /**
     * @return string
     */
    public function getHash()
    {
        return $this->hash;
    }

    /**
     * @param string $refno
     */
    public function setRefno($refno)
    {
        $this->refno = $refno;
    }

    /**
     * @return string
     */
    public function getRefno()
    {
        return $this->refno;
    }

    /**
     * @param string $returnCode
     */
    public function setReturnCode($returnCode)
    {
        $this->returnCode = $returnCode;
    }

    /**
     * @return string
     */
    public function getReturnCode()
    {
        return $this->returnCode;
    }

    /**
     * @param string $returnMessage
     */
    public function setReturnMessage($returnMessage)
    {
        $this->returnMessage = $returnMessage;
    }

    /**
     * @return string
     */
    public function getReturnMessage()
    {
        return $this->returnMessage;
    }

    /**
     * @param string $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param string $threeDsUrl
     */
    public function setThreeDsUrl($threeDsUrl)
    {
        $this->threeDsUrl = $threeDsUrl;
    }

    /**
     * @return string
     */
    public function getThreeDsUrl()
    {
        return $this->threeDsUrl;
    }

    /**
     * @param string $amount
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    /**
     * @return string
     */
    public function getAmount()
    {
        return $this->amount;
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
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @param string $installmentsNo
     */
    public function setInstallmentsNo($installmentsNo)
    {
        $this->installmentsNo = $installmentsNo;
    }

    /**
     * @return string
     */
    public function getInstallmentsNo()
    {
        return $this->installmentsNo;
    }

    /**
     * @param string $cardProgramName
     */
    public function setCardProgramName($cardProgramName)
    {
        $this->cardProgramName = $cardProgramName;
    }

    /**
     * @return string
     */
    public function getCardProgramName()
    {
        return $this->cardProgramName;
    }

    /**
     * @return string
     */
    public function getOrderRef()
    {
        return $this->orderRef;
    }

    /**
     * @param string $orderRef
     */
    public function setOrderRef($orderRef)
    {
        $this->orderRef = $orderRef;
    }

    /**
     * @return string
     */
    public function getAuthCode()
    {
        return $this->authCode;
    }

    /**
     * @param string $authCode
     */
    public function setAuthCode($authCode)
    {
        $this->authCode = $authCode;
    }

    /**
     * @return string
     */
    public function getRrn()
    {
        return $this->rrn;
    }

    /**
     * @param string $rrn
     */
    public function setRrn($rrn)
    {
        $this->rrn = $rrn;
    }

    /**
     * @return string
     */
    public function getUrlRedirect()
    {
        return $this->urlRedirect;
    }

    /**
     * @param string $urlRedirect
     */
    public function setUrlRedirect($urlRedirect)
    {
        $this->urlRedirect = $urlRedirect;
    }

    public function getAdditionalParameterValue($name)
    {
        $name = (string)$name;
        if (array_key_exists($name, $this->additionalResponseParameters)) {
            return $this->additionalResponseParameters[$name];
        }
        return null;
    }

    /**
     * @return string
     */
    public function getTokenHash()
    {
        return $this->tokenHash;
    }

    /**
     * @param string $tokenHash
     */
    public function setTokenHash($tokenHash)
    {
        $this->tokenHash = $tokenHash;
    }

    /**
     * @param \string[] $additionalResponseParameters
     * @return Response
     */
    public function setAdditionalResponseParameters($additionalResponseParameters)
    {
        $this->additionalResponseParameters = $additionalResponseParameters;
    }

    /**
     * @return \string[]
     */
    public function getAdditionalResponseParameters()
    {
        return $this->additionalResponseParameters;
    }
}

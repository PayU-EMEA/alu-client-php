<?php

namespace PayU\Alu;

/**
 * Class Response
 * @package PayU\Alu
 */
class Response
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
     * @var array
     */
    private $internalArray = array();

    /**
     * @var ResponseWireAccount[]
     */
    private $wireAccounts = array();


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
     * @return bool
     */
    public function isThreeDs()
    {
        if (
            $this->status == 'SUCCESS' &&
            $this->returnCode == '3DS_ENROLLED' &&
            !empty($this->threeDsUrl)
        ) {
            return true;
        }
        return false;
    }

    /**
     * @return array
     */
    private function computeInternalArray()
    {
        $this->internalArray['REFNO'] = $this->refno;
        $this->internalArray['ALIAS'] = $this->alias;
        $this->internalArray['STATUS'] = $this->status;
        $this->internalArray['RETURN_CODE'] = $this->returnCode;
        $this->internalArray['RETURN_MESSAGE'] = $this->returnMessage;
        $this->internalArray['DATE'] = $this->date;

        if (!is_null($this->orderRef)) {
            $this->internalArray['ORDER_REF'] = $this->orderRef;
        }
        if (!is_null($this->authCode)) {
            $this->internalArray['AUTH_CODE'] = $this->authCode;
        }
        if (!is_null($this->rrn)) {
            $this->internalArray['RRN'] = $this->rrn;
        }

        if (is_array($this->getWireAccounts())) {
            foreach ($this->getWireAccounts() as $account) {
                $this->internalArray['WIRE_ACCOUNTS'][] = array(
                    'BANK_IDENTIFIER' => $account->getBankIdentifier(),
                    'BANK_ACCOUNT' => $account->getBankAccount(),
                    'ROUTING_NUMBER' => $account->getRoutingNumber(),
                    'IBAN_ACCOUNT' => $account->getIbanAccount(),
                    'BANK_SWIFT' => $account->getBankSwift(),
                    'COUNTRY' => $account->getCountry(),
                    'WIRE_RECIPIENT_NAME' => $account->getWireRecipientName(),
                    'WIRE_RECIPIENT_VAT_ID' => $account->getWireRecipientVatId(),
                );
            }
        }

        return $this->internalArray;
    }

    /**
     * @return array
     */
    public function getResponseParams()
    {
        if (empty($this->internalArray)) {
            return $this->computeInternalArray();
        }
        return $this->internalArray;
    }
}

<?php
namespace PayU\Alu\Parser;

use PayU\Alu\Component\ResponseWireAccount;
use PayU\Alu\HashService;

/**
 * Class AbstractParser
 * @package PayU\Alu\Parser
 */
abstract class AbstractParser
{
    /**
     * @var HashService
     */
    protected $hashService;

    /**
     * AbstractParser constructor.
     * @param HashService $hashService
     */
    public function __construct(HashService $hashService)
    {
        $this->hashService = $hashService;
    }

    /**
     * @param array $parameters
     * @return array
     */
    protected function parseAdditionalParameters(array $parameters)
    {
        $possibleParameters = array(
            'PROCRETURNCODE',
            'ERRORMESSAGE',
            'BANK_MERCHANT_ID',
            'PAN',
            'EXPYEAR',
            'EXPMONTH',
            'CLIENTID',
            'HOSTREFNUM',
            'OID',
            'RESPONSE',
            'TERMINAL_BANK',
            'MDSTATUS',
            'MDERRORMSG',
            'TXSTATUS',
            'XID',
            'ECI',
            'CAVV',
            'TRANSID',
        );

        $additionalParameters = array();
        foreach ($parameters as $parameterKey => $value) {
            if (in_array((string)$parameterKey, $possibleParameters)) {
                $additionalParameters[(string)$parameterKey] = (string)$value;
            }
        }
        return $additionalParameters;
    }

    /**
     * @param array $wireAccount
     * @return \PayU\Alu\Component\ResponseWireAccount
     */
    protected function createWiredAccount(array $wireAccount)
    {
        $responseWireAccount = new ResponseWireAccount();
        if (array_key_exists('BANK_IDENTIFIER', $wireAccount)) {
            $responseWireAccount->setBankIdentifier($wireAccount['BANK_IDENTIFIER']);
        }
        if (array_key_exists('BANK_ACCOUNT', $wireAccount)) {
            $responseWireAccount->setBankAccount($wireAccount['BANK_ACCOUNT']);
        }
        if (array_key_exists('ROUTING_NUMBER', $wireAccount)) {
            $responseWireAccount->setRoutingNumber($wireAccount['ROUTING_NUMBER']);
        }
        if (array_key_exists('IBAN_ACCOUNT', $wireAccount)) {
            $responseWireAccount->setIbanAccount($wireAccount['IBAN_ACCOUNT']);
        }
        if (array_key_exists('BANK_SWIFT', $wireAccount)) {
            $responseWireAccount->setBankSwift($wireAccount['BANK_SWIFT']);
        }
        if (array_key_exists('COUNTRY', $wireAccount)) {
            $responseWireAccount->setCountry($wireAccount['COUNTRY']);
        }
        if (array_key_exists('WIRE_RECIPIENT_NAME', $wireAccount)) {
            $responseWireAccount->setWireRecipientName($wireAccount['WIRE_RECIPIENT_NAME']);
        }
        if (array_key_exists('WIRE_RECIPIENT_VAT_ID', $wireAccount)) {
            $responseWireAccount->setWireRecipientVatId($wireAccount['WIRE_RECIPIENT_VAT_ID']);
        }

        return $responseWireAccount;
    }

    /**
     * @param $data
     * @return mixed
     */
    abstract public function parse($data);
}
<?php

namespace PayU\PaymentsApi\PaymentsV4\Services;

use PayU\PaymentsApi\PaymentsV4\Exceptions\ResponseBuilderException;

class AluResponseMapper
{
    const CODE = 'code';
    const PAYU_PAYMENT_REFERENCE = 'payuPaymentReference';
    const MERCHANT_PAYMENT_REFERENCE = 'merchantPaymentReference';
    const AMOUNT = 'amount';
    const CURRENCY = 'currency';
    const RETURN_MESSAGE = 'message';

    const PAYMENT_RESULT = 'paymentResult';
    const NODE_CARD_DETAILS = 'cardDetails';
    const NODE_3DS = '3dsDetails';
    const NODE_BANK = 'bankResponseDetails';
    const NODE_BANK_RESPONSE = 'response';

    const MAP = [
        self::CODE => 'CODE',
        self::PAYU_PAYMENT_REFERENCE => 'REFNO',
        self::MERCHANT_PAYMENT_REFERENCE => 'ORDER_REF',
        self::AMOUNT => 'AMOUNT',
        self::CURRENCY => 'CURRENCY',
        self::RETURN_MESSAGE => 'RETURN_MESSAGE',
        self::PAYMENT_RESULT . '.payuResponseCode' => 'RETURN_CODE',

        /* Authorization node => paymentResult.rrn */
        self::PAYMENT_RESULT . '.authCode' => 'AUTH_CODE',
        self::PAYMENT_RESULT . '.rrn' => 'RRN',
        self::PAYMENT_RESULT . '.installmentsNumber' => 'INSTALLMENTS_NO',
        self::PAYMENT_RESULT . '.cardProgramName' => 'CARD_PROGRAM_NAME',

        /* CardDetails plugin => paymentResult.cardDetails.pan */
        self::PAYMENT_RESULT . '.' . self::NODE_CARD_DETAILS . '.pan' => 'PAN',
        self::PAYMENT_RESULT . '.' . self::NODE_CARD_DETAILS . '.expiryYear' => 'EXPYEAR',
        self::PAYMENT_RESULT . '.' . self::NODE_CARD_DETAILS . '.expiryMonth' => 'EXPMONTH',

        /* ThreeDSParams plugin => paymentResult.3dsDetails.mdStatus */
        self::PAYMENT_RESULT . '.' . self::NODE_3DS . '.mdStatus' => 'MDSTATUS',
        self::PAYMENT_RESULT . '.' . self::NODE_3DS . '.errorMessage' => 'MDERRORMSG',
        self::PAYMENT_RESULT . '.' . self::NODE_3DS . '.txStatus' => 'TXSTATUS',
        self::PAYMENT_RESULT . '.' . self::NODE_3DS . '.xid' => 'XID',
        self::PAYMENT_RESULT . '.' . self::NODE_3DS . '.eci' => 'ECI',
        self::PAYMENT_RESULT . '.' . self::NODE_3DS . '.cavv' => 'CAVV',

        /* Bank information => paymentResult.bankResponseDetails.terminalId */
        self::PAYMENT_RESULT . '.' . self::NODE_BANK . '.terminalId' => 'CLIENTID',

        /* Bank information => paymentResult.bankResponseDetails.response.code */
        self::PAYMENT_RESULT . '.' . self::NODE_BANK . '.' . self::NODE_BANK_RESPONSE . '.code' => 'PROCRETURNCODE',
        self::PAYMENT_RESULT . '.' . self::NODE_BANK . '.' . self::NODE_BANK_RESPONSE . '.message' => 'ERRORMESSAGE',
        self::PAYMENT_RESULT . '.' . self::NODE_BANK . '.' . self::NODE_BANK_RESPONSE . '.status' => 'RESPONSE',

        self::PAYMENT_RESULT . '.' . self::NODE_BANK . '.hostRefNum' => 'HOSTREFNUM',
        self::PAYMENT_RESULT . '.' . self::NODE_BANK . '.merchantId' => 'BANK_MERCHANT_ID',
        self::PAYMENT_RESULT . '.' . self::NODE_BANK . '.shortName' => 'TERMINAL_BANK',
        self::PAYMENT_RESULT . '.' . self::NODE_BANK . '.txRefNo' => 'TX_REFNO',
        self::PAYMENT_RESULT . '.' . self::NODE_BANK . '.oid' => 'OID',
        self::PAYMENT_RESULT . '.' . self::NODE_BANK . '.transId' => 'TRANSID',

        self::PAYMENT_RESULT . '.' . 'wireAccounts' => 'WIRE_ACCOUNTS',
        self::PAYMENT_RESULT . '.' . 'type' => 'TYPE',
        self::PAYMENT_RESULT . '.' . 'url' => 'URL_3DS',
        self::PAYMENT_RESULT . '.' . 'url' => 'URL_REDIRECT',

        /* used only for some decisions in AluResponseTransformation; won't be in the final response */
        'status' => 'STATUS',
    ];

    /** @var FieldMapper */
    private $fieldMapper;

    /** @var AluResponseTransformation */
    private $aluResponseTransformation;

    public function __construct()
    {
        $this->fieldMapper = new FieldMapper();
        $this->aluResponseTransformation = new AluResponseTransformation();
    }

    /**
     * @param array $aluResponseBody
     * @return array
     * @throws ResponseBuilderException
     */
    public function processResponse($aluResponseBody)
    {
        /*Remove empty fields | $data passed by reference*/
        $this->removeEmptyValuesFromArray($aluResponseBody);

        $mappedData = $this->fieldMapper->map($aluResponseBody, self::MAP);

        return $this->aluResponseTransformation->process($mappedData);
    }

    /**
     * @param $array
     * @return void
     * todo see if can use array_walk_recursive
     */
    private function removeEmptyValuesFromArray(array &$array)
    {
        foreach ($array as $key => &$value) {
            if (is_array($value)) {
                $this->removeEmptyValuesFromArray($value);
            }

            if ($value === '' || $value === null || $value === []) {
                unset($array[$key]);
            }
        }
    }
}

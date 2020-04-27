<?php

namespace PayU\PaymentsApi\AluV3\Services;

use PayU\Alu\Exceptions\ClientException;
use PayU\Alu\Request;
use PayU\Alu\Response;

/**
 * Class HashService
 * @package PayU\Alu
 */
class HashService
{
    /**
     * @var string
     */
    private $secretKey;

    /**
     * HashService constructor.
     * @param string $secretKey
     */
    public function __construct($secretKey)
    {
        $this->secretKey = $secretKey;
    }

    /**
     * @param array $array
     * @return string
     */
    private function serializeArray(array $array)
    {
        $return = '';
        foreach ($array as $key => $val) {
            if (isset($val)) {
                if (is_array($val) && count($val) > 0) {
                    $return .= $this->serializeArray($val);
                } else {
                    $return .= mb_strlen($val, 'UTF-8') . $val;
                }
            }
        }
        return $return;
    }

    /**
     * @param array $arrayToProcess
     * @return string
     */
    public function computeHash(array $arrayToProcess)
    {
        $serialization = $this->serializeArray($arrayToProcess);
        return hash_hmac("md5", $serialization, $this->secretKey);
    }

    /**
     * @deprecated This method was used to compute request hash in PayU/Alu/Client.php and this logic was moved \PayU\PaymentsApi\ namespace     *
     * Now the request hash should be computed inside \PayU\PaymentsApi\AluV3\Services\RequestBuilder::buildAuthorizationRequest
     *
     * @param Request $request
     * @return string
     */
    public function makeRequestHash(Request $request)
    {
        $params = $request->getRequestParams();
        return $this->computeHash($params);
    }

    /**
     * @param Response $response
     * @throws ClientException
     */
    public function validateResponseHash(Response $response)
    {
        $responseParams = $response->getResponseParams();
        if ($this->computeHash($responseParams) !== $response->getHash()) {
            throw new ClientException('Response HASH mismatch');
        }
    }
}

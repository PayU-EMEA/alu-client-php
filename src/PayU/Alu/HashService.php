<?php

namespace PayU\Alu;

use PayU\Alu\Exceptions\ClientException;

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
     * @param array $process
     * @return string
     */
    private function computeHash(array $process)
    {
        $serialization = $this->serializeArray($process);
        $hash = hash_hmac("md5", $serialization, $this->secretKey);
        return $hash;
    }

    public function makeRequestHash(Request $request)
    {
        $params = $request->getRequestParams();
        return $this->computeHash($params);
    }

    public function validateResponseHash(Response $response)
    {
        $responseParams = $response->getResponseParams();
        if ($this->computeHash($responseParams) !== $response->getHash()) {
            throw new ClientException('Response HASH mismatch');
        }
    }

    /**
     * @param Request $request
     * @param string $jsonRequest
     * @return string
     */
    public function generateSignatureV4(Request $request, $jsonRequest)
    {
        $stringToBeHashed =
            $request->getMerchantConfig()->getMerchantCode().
            $request->getOrder()->getOrderDate().
            HTTPClient::POST_METHOD.
            Client::ALU_V4_AUTHORIZE_PATH.
            ''.
            md5($jsonRequest);

        return hash_hmac("sha256", $stringToBeHashed, $this->secretKey);
    }

    /**
     * @param array $requestBody
     * @param string $secretKey
     * @return string
     */
    public function generateTokenSignature(array $requestBody, $secretKey)
    {
        ksort($requestBody);
        $stringToBeHashed = '';
        foreach ($requestBody as $key => $val) {
            if ($key !== 'timestamp')
                $stringToBeHashed = $stringToBeHashed . $val;
        }
        $stringToBeHashed = $stringToBeHashed . $requestBody['timestamp'];

        return hash_hmac("sha256", $stringToBeHashed, $secretKey);
    }
}

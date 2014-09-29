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
}

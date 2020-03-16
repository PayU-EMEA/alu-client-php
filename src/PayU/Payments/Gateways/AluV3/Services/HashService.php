<?php

namespace AluV3\Services;

use PayU\Alu\Exceptions\ClientException;
use PayU\Alu\MerchantConfig;
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

    /**
     * @param array $requestArray
     * @param string $secretKey
     * @return string
     */
    public function makeRequestHash($requestArray, $secretKey)
    {
        $this->setSecretKey($secretKey);

        return $this->computeHash($requestArray);
    }

    /**
     * @param string $secretKey
     */
    public function setSecretKey($secretKey)
    {
        $this->secretKey = $secretKey;
    }

    public function validateResponseHash(Response $response)
    {
        $responseParams = $response->getResponseParams();
        if ($this->computeHash($responseParams) !== $response->getHash()) {
            throw new ClientException('Response HASH mismatch');
        }
    }

}

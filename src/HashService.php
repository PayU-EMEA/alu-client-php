<?php
namespace PayU\Alu;

use PayU\Alu\Component\Request;
use PayU\Alu\Component\Response;
use PayU\Alu\Exception\ClientException;
use PayU\Alu\Transformer\RequestTransformer;
use PayU\Alu\Transformer\ResponseTransformer;

/**
 * Class HashService
 * @package PayU\Alu
 */
class HashService
{
    /** @var MerchantConfig */
    private $config;

    /** @var RequestTransformer */
    private $requestTransformer;

    /** @var ResponseTransformer */
    private $responseTransformer;

    /**
     * HashService constructor.
     * @param MerchantConfig $config
     */
    public function __construct(MerchantConfig $config)
    {
        $this->config = $config;
        $this->requestTransformer = new RequestTransformer($config);
        $this->responseTransformer = new ResponseTransformer($config);
    }

    /**
     * @param array $array
     * @return string
     */
    private function serialize(array $array)
    {
        $return = '';
        foreach ($array as $key => $val) {
            if (isset($val)) {
                if (is_array($val) && count($val) > 0) {
                    $return .= $this->serialize($val);
                } else {
                    $return .= \mb_strlen($val, 'UTF-8') . $val;
                }
            }
        }
        return $return;
    }


    /**
     * @param array $transformedRequest
     * @return string
     */
    private function getHash(array $transformedRequest)
    {
        $serializedRequest = $this->serialize($transformedRequest);
        $hash = hash_hmac("md5", $serializedRequest, $this->config->getSecretKey());
        return $hash;
    }

    /**
     * @param Request $request
     * @return array
     */
    public function sign(Request $request)
    {
        $transformedRequest = $this->requestTransformer->transform($request);
        $hash = $this->getHash($transformedRequest);
        $transformedRequest['ORDER_HASH'] = $hash;
        return $transformedRequest;
    }


    /**
     * @param Response $response
     * @throws ClientException
     */
    public function validateResponse(Response $response)
    {
        if ($this->getHash($this->responseTransformer->transform($response)) !== $response->getHash()) {
            throw new ClientException('Response HASH mismatch');
        }
    }
}

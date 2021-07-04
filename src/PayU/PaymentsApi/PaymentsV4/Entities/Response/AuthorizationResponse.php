<?php


namespace PayU\PaymentsApi\PaymentsV4\Entities\Response;

final class AuthorizationResponse
{
    /**
     * @var array
     */
    private $response;

    /**
     * AuthorizationResponse constructor.
     *
     * @param array $response
     */
    public function __construct(array $response)
    {
        $this->response = $response;
    }

    /**
     * @return array
     */
    public function getResponse()
    {
        return $this->response;
    }
}

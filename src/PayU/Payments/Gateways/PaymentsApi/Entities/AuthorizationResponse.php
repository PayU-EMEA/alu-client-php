<?php


namespace PaymentsApi\Entities;

class AuthorizationResponse
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

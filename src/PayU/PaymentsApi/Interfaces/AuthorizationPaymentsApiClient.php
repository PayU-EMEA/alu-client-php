<?php

namespace PayU\PaymentsApi\Interfaces;

use PayU\Alu\Request;
use PayU\Alu\Response;
use PayU\PaymentsApi\Exceptions\AuthorizationException;

interface AuthorizationPaymentsApiClient
{
    /**
     *
     * Decouples the logic of \PayU\Alu\Client class from the actual authorization call performed with a different type of data
     * structure depending on the payments API implementation.

     * @param Request $request
     * @return Response
     * @throws AuthorizationException
     */
    public function authorize(Request $request);
}

<?php

namespace PayU\PaymentsApi\Interfaces;

use PayU\Alu\Request;
use PayU\Alu\Response;
use PayU\PaymentsApi\Exceptions\AuthorizationException;

interface AuthorizationInterface
{
    /**
     * @param Request $request
     * @return Response
     * @throws AuthorizationException
     */
    public function authorize(Request $request);
}

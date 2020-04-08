<?php

namespace PayU\PaymentsApi\Interfaces;

use PayU\Alu\Request;
use PayU\Alu\Response;

interface AuthorizationInterface
{
    /**
     * @param Request $request
     * @return Response
     */
    public function authorize(Request $request);
}

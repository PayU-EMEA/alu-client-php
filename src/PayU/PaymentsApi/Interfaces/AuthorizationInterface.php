<?php

namespace PayU\PaymentsApi\Interfaces;

use PayU\Alu\Request;
use PayU\Alu\Response;

interface AuthorizationInterface
{
    /**
     * @param Request $request
     * @param string $customAluUrl
     * @return Response
     */
    public function authorize(Request $request, $customAluUrl);
}

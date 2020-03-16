<?php

namespace PayU\Payments\Interfaces;

use PayU\Alu\Request;
use PayU\Alu\Response;

interface GatewayInterface
{
    /**
     * @param Request $request
     * @return Response
     */
    public function authorize(Request $request);
}

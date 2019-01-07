<?php
namespace PayU\Alu\Exception;

class ClientException extends \Exception
{
    public function getErrorMessage()
    {
        $errorMsg = 'ClientError on line ' . $this->getLine() . ' in ' . $this->getFile() . ':' . $this->getMessage();
        return $errorMsg;
    }
}

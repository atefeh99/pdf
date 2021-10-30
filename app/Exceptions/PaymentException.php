<?php

namespace App\Exceptions;

use Exception;


class PaymentException extends Exception
{
    protected $msg;
    public function __construct($msg)
    {
        $this->msg = $msg;
    }
    public function getErrorMessage()
    {
        return $this->msg;
    }

}

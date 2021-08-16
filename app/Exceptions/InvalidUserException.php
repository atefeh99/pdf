<?php

namespace App\Exceptions;

use Exception;

class InvalidUserException extends Exception
{

    public function __construct()
    {
        parent::__construct();

    }
}

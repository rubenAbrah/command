<?php

namespace App\Commands;

use Exception;

class CommandException extends Exception
{
    // You can add custom logic or properties if needed
    public function __construct($message = "Command execution failed", $code = 0)
    {
        parent::__construct($message, $code);
    }
}
<?php

namespace App\Commands;

use Exception;

abstract class Command
{
    abstract public function execute();
}
<?php

namespace App\Handlers;

use Exception;
use App\Commands\Command;
use App\Handlers\CommandQueueHandler;

abstract class ExceptionHandler
{
    protected $queueHandler;

    public function __construct(CommandQueueHandler $queueHandler)
    {
        $this->queueHandler = $queueHandler;
    }

    abstract public function handle(Exception $e, Command $command);
}
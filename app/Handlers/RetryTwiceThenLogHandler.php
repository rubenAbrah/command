<?php

namespace App\Handlers;

use Exception;
use App\Commands\Command;
use App\Handlers\ExceptionHandler;
use App\Commands\RetryTwiceThenLogCommand;

class RetryTwiceThenLogHandler extends ExceptionHandler
{
    public function handle(Exception $e, Command $command)
    {
        // Добавляем команду с стратегией "Повторить два раза, затем записать в лог"
        $this->queueHandler->addCommand(new RetryTwiceThenLogCommand($command));
    }
}
<?php

namespace App\Handlers;

use Exception;
use App\Commands\Command;
use App\Handlers\ExceptionHandler;
use Illuminate\Support\Facades\Log;

class CommandQueueHandler
{
    protected $queue = [];
    protected $exceptionHandlers = [];

    public function addCommand(Command $command)
    {
        $this->queue[] = $command;
    }

    public function addExceptionHandler($exceptionClass, ExceptionHandler $handler)
    {
        $this->exceptionHandlers[$exceptionClass] = $handler;
    }

    public function processQueue()
    {
        while (!empty($this->queue)) {
            $command = array_shift($this->queue);

            try {
                $command->execute();
            } catch (Exception $e) {
                $this->handleException($e, $command);
            }
        }
    }

    protected function handleException(Exception $e, Command $command)
    {
        foreach ($this->exceptionHandlers as $exceptionClass => $handler) {
            if ($e instanceof $exceptionClass) {
                $handler->handle($e, $command);
                return;
            }
        }

        // Если обработчик не найден, логируем ошибку
        Log::error('Unhandled exception: ' . get_class($e), [
            'command' => get_class($command),
            'exception' => $e->getMessage(),
        ]);
    }
}
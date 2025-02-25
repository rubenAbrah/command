<?php

namespace App\Commands;

use Exception;
use App\Commands\Command;
use Illuminate\Support\Facades\Log;

class LogExceptionCommand extends Command
{
    protected $exception;
    protected $command;

    public function __construct(Exception $exception, Command $command)
    {
        $this->exception = $exception;
        $this->command = $command;
    }

    public function execute()
    {
        Log::error('Command failed: ' . get_class($this->command), [
            'exception' => $this->exception->getMessage(),
        ]);
    }
}
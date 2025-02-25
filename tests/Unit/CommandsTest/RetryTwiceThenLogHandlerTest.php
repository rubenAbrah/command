<?php

namespace Tests\Unit\Handlers;

use Exception;
use Tests\TestCase;
use App\Commands\Command;
use App\Handlers\CommandQueueHandler;
use App\Commands\RetryTwiceThenLogCommand;
use App\Handlers\RetryTwiceThenLogHandler;

class RetryTwiceThenLogHandlerTest extends TestCase
{
    public function test_handle_adds_retry_twice_then_log_command()
    {
        $queueHandler = $this->createMock(CommandQueueHandler::class);
        $queueHandler->expects($this->once())
            ->method('addCommand')
            ->with($this->isInstanceOf(RetryTwiceThenLogCommand::class));

        $handler = new RetryTwiceThenLogHandler($queueHandler);

        $command = $this->createMock(Command::class);
        $exception = new Exception('Test exception');

        $handler->handle($exception, $command);
    }
}
<?php

namespace Tests\Unit\Commands;

use App\Commands\Command;
use App\Commands\RetryTwiceThenLogCommand;
use Exception;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class RetryTwiceThenLogCommandTest extends TestCase
{
    public function test_execute_retries_twice_then_logs()
    {
        Log::spy();

        $mockCommand = $this->createMock(Command::class);
        $mockCommand->expects($this->exactly(2)) // Ожидаем два вызова
            ->method('execute')
            ->willThrowException(new Exception('Test exception'));

        $command = new RetryTwiceThenLogCommand($mockCommand);
        $command->execute();

        // Проверяем, что ошибка была записана в лог
        Log::shouldHaveReceived('error')
            ->once()
            ->with('Command failed after 2 attempts: ' . get_class($mockCommand), [
                'exception' => 'Test exception',
            ]);
    }
}
<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\ThreadSafe\ThreadSafeCommandQueue;
use App\Services\CommandProcessor;
use App\Commands\Command;

class CommandProcessorTest extends TestCase
{
    private ThreadSafeCommandQueue $queue;
    private CommandProcessor $processor;

    protected function setUp(): void
    {
        parent::setUp();
        $this->queue = new ThreadSafeCommandQueue();
        $this->processor = new CommandProcessor($this->queue, 1.0);
    }

    public function test_soft_stop_completes_all_tasks()
    {
        $executed = 0;
        $command = new class ($executed) extends Command {
            private $executed;
            public function __construct(&$executed)
            {
                $this->executed = &$executed;
            }
            public function execute()
            {
                $this->executed++;
            }
        };

        $this->queue->add(clone $command);
        $this->queue->add(clone $command);
        $this->queue->add(clone $command);

        $this->queue->softStop();

        $this->processor->start();

        $this->assertEquals(3, $executed);
        $this->assertFalse($this->processor->isRunning());
    }

    public function test_hard_stop_interrupts_processing()
    {
        $executed = 0;
        $command = new class ($executed) extends Command {
            private $executed;
            public function __construct(&$executed)
            {
                $this->executed = &$executed;
            }
            public function execute()
            {
                usleep(300000);
                $this->executed++;
            }
        };

        for ($i = 0; $i < 10; $i++) {
            $this->queue->add(clone $command);
        }

        $startTime = microtime(true);
        $this->runWithTimeout(function () {
            $this->processor->start();
        }, 0.5);
        usleep(200000);
        $this->processor->requestStop();

        $this->assertLessThan(5, $executed, 'Not all commands should have executed after hard stop');
        $this->assertGreaterThan(0, $executed, 'At least one command should have started');
    }

    public function test_processor_handles_exceptions()
    {
        $executed = false;

        $this->queue->add(new class extends Command {
            public function execute()
            {
                throw new \RuntimeException("Test exception");
            }
        });

        $this->queue->add(new class ($executed) extends Command {
            private $executed;
            public function __construct(&$executed)
            {
                $this->executed = &$executed;
            }
            public function execute()
            {
                $this->executed = true;
            }
        });

        $this->queue->softStop();
        $this->processor->start();

        $this->assertTrue($executed);
    }

    private function runWithTimeout(callable $function, float $timeout): void
    {
        $start = microtime(true);
        $function();

        if ((microtime(true) - $start) > $timeout) {
            $this->processor->requestStop();
        }
    }
}
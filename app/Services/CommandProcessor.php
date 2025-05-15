<?php

namespace App\Services;

use App\ThreadSafe\ThreadSafeCommandQueue;
use App\Commands\Command;
use Illuminate\Support\Facades\Log;

class CommandProcessor
{
    private ThreadSafeCommandQueue $queue;
    private bool $shouldStop = false;
    private bool $isRunning = false;
    private float $timeoutSeconds;

    public function __construct(ThreadSafeCommandQueue $queue, float $timeoutSeconds = 5.0)
    {
        $this->queue = $queue;
        $this->timeoutSeconds = $timeoutSeconds;
    }

    public function start(): void
    {
        if ($this->isRunning) {
            throw new \RuntimeException('Processor is already running');
        }

        $this->isRunning = true;
        $this->shouldStop = false;

        $startTime = microtime(true);

        while (
            !$this->shouldStop &&
            !($this->queue->shouldStop() && $this->queue->count() === 0) &&
            (microtime(true) - $startTime) < $this->timeoutSeconds
        ) {

            if ($this->shouldStop) {
                break;
            }

            $command = $this->queue->get();

            if ($command === null) {
                usleep(10000);
                continue;
            }

            try {
                $command->execute();
            } catch (\Exception $e) {
                Log::error('Command failed: ' . get_class($command), [
                    'exception' => $e->getMessage()
                ]);
            }
        }

        $this->isRunning = false;
    }

    public function requestStop(): void
    {
        $this->shouldStop = true;
    }

    public function isRunning(): bool
    {
        return $this->isRunning;
    }
}
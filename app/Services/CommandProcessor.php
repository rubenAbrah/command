<?php

namespace App\Services;

use App\CommandProcessing\NormalState;
use App\CommandProcessing\StateInterface;
use App\Commands\Command;
use App\ThreadSafe\ThreadSafeCommandQueue;

class CommandProcessor
{
    private ThreadSafeCommandQueue $queue;
    private bool $shouldStop = false;
    private ?StateInterface $currentState;
    private bool $isRunning = false;
    private int $idleTimeoutMs;

    public function __construct(
        ThreadSafeCommandQueue $queue,
        int $idleTimeoutMs = 100
    ) {
        $this->queue = $queue;
        $this->currentState = new NormalState();
        $this->idleTimeoutMs = $idleTimeoutMs;
    }

    public function start(): void
    {
        $this->isRunning = true;
        
        while ($this->shouldContinueProcessing()) {
            $command = $this->queue->get();
            
            if ($command === null) {
                usleep($this->idleTimeoutMs * 1000);
                continue;
            }

            $this->currentState = $this->currentState->handle($command);
        }
        
        $this->isRunning = false;
    }

    private function shouldContinueProcessing(): bool
    {
        return !$this->shouldStop && 
               $this->currentState !== null &&
               !$this->queue->shouldStop();
    }

    public function requestStop(): void
    {
        $this->shouldStop = true;
    }

    public function isRunning(): bool
    {
        return $this->isRunning;
    }

    public function getCurrentState(): ?string
    {
        return $this->currentState ? get_class($this->currentState) : null;
    }
}
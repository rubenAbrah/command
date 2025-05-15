<?php

namespace App\ThreadSafe;

use App\Commands\Command;

class ThreadSafeCommandQueue
{
    private array $queue = [];
    private bool $shouldStop = false;
    private bool $softStopRequested = false;

    public function add(Command $command): void
    {
        $this->queue[] = $command;
    }

    public function get(): ?Command
    {
        if ($this->shouldStop()) {
            return null;
        }
        return array_shift($this->queue);
    }

    public function hardStop(): void
    {
        $this->shouldStop = true;
    }

    public function softStop(): void
    {
        $this->softStopRequested = true;
    }

    public function shouldStop(): bool
    {
        return $this->shouldStop || ($this->softStopRequested && empty($this->queue));
    }

    public function count(): int
    {
        return count($this->queue);
    }
}
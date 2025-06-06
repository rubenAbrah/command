<?php

namespace App\CommandProcessing;

use App\Commands\Command;
use App\ThreadSafe\ThreadSafeCommandQueue;

class MoveToState extends BaseState
{
    private ThreadSafeCommandQueue $targetQueue;

    public function __construct(ThreadSafeCommandQueue $targetQueue)
    {
        $this->targetQueue = $targetQueue;
    }

    public function process(Command $command): void
    {
        $this->targetQueue->add($command);
    }
}
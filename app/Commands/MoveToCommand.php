<?php

namespace App\Commands;

use App\ThreadSafe\ThreadSafeCommandQueue;

class MoveToCommand extends Command
{
    private ThreadSafeCommandQueue $targetQueue;

    public function __construct(ThreadSafeCommandQueue $targetQueue)
    {
        $this->targetQueue = $targetQueue;
    }

    public function getTargetQueue(): ThreadSafeCommandQueue
    {
        return $this->targetQueue;
    }

    public function execute()
    {
    }
}
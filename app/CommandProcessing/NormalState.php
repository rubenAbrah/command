<?php

namespace App\CommandProcessing;

use App\Commands\Command;
use App\ThreadSafe\ThreadSafeCommandQueue;

class NormalState extends BaseState
{
    public function process(Command $command): void
    {
        $command->execute();
    }
}
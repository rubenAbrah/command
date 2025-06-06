<?php

namespace App\CommandProcessing;

use App\Commands\Command;
use App\Commands\HardStopCommand;
use App\Commands\MoveToCommand;
use App\Commands\RunCommand;

abstract class BaseState implements StateInterface
{
    public function handle(Command $command): ?StateInterface
    {
        if ($command instanceof HardStopCommand) {
            return null;
        }

        if ($command instanceof MoveToCommand) {
            return new MoveToState($command->getTargetQueue());
        }

        if ($command instanceof RunCommand) {
            return new NormalState();
        }

        $this->process($command);
        return $this;
    }
}
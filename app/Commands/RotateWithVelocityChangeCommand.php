<?php

namespace App\Commands;

use App\Commands\MacroCommand;
use App\Game\Move\RotateCommand;
use App\Commands\ChangeVelocityCommand;

class RotateWithVelocityChangeCommand extends MacroCommand
{
    public function __construct(RotateCommand $rotateCommand, ChangeVelocityCommand $changeVelocityCommand)
    {
        parent::__construct([$rotateCommand, $changeVelocityCommand]);
    }
}
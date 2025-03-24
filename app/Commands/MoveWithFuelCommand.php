<?php

namespace App\Commands;

use App\Commands\MacroCommand;
use App\Game\Move\MoveCommand;
use App\Commands\BurnFuelCommand;
use App\Commands\CheckFuelCommand;

class MoveWithFuelCommand extends MacroCommand
{
    public function __construct(CheckFuelCommand $checkFuelCommand, MoveCommand $moveCommand, BurnFuelCommand $burnFuelCommand)
    {
        parent::__construct([$checkFuelCommand, $moveCommand, $burnFuelCommand]);
    }
}
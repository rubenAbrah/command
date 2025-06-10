<?php

namespace App\Commands;

use App\GameObjects\Ship;

class StartMoveCommand
{
    public function execute(Ship $ship, float $initialVelocity): void
    {
        $ship->setPosition([1, 0]);
        $ship->setVelocity([$initialVelocity, 0]);
    }
}
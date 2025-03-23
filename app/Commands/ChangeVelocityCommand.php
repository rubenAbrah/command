<?php

namespace App\Commands;

use App\Game\Interfaces\Movable;
use App\Game\Interfaces\Rotatable;

class ChangeVelocityCommand extends Command
{
    protected $movable;
    protected $rotatable;

    public function __construct(Movable $movable, Rotatable $rotatable)
    {
        $this->movable = $movable;
        $this->rotatable = $rotatable;
    }

    public function execute()
    {
        $velocity = $this->movable->getVelocity();
        $angle = $this->rotatable->getAngle();

        if ($velocity !== null && $angle !== null) {
            $newVelocity = [
                $velocity[0] * cos(deg2rad($angle)) - $velocity[1] * sin(deg2rad($angle)),
                $velocity[0] * sin(deg2rad($angle)) + $velocity[1] * cos(deg2rad($angle)),
            ];
            $this->movable->setVelocity($newVelocity);
        }
    }
}
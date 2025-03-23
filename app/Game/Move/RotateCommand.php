<?php

namespace App\Game\Move;

use App\Game\Interfaces\Rotatable;

class RotateCommand
{
    public function rotate(Rotatable $rotatable): void
    {
        $angle = $rotatable->getAngle();
        $angularVelocity = $rotatable->getAngularVelocity();

        if ($angle === null || $angularVelocity === null) {
            throw new \Exception("Cannot rotate object: angle or angular velocity is not set.");
        }

        $newAngle = $angle + $angularVelocity;
        $rotatable->setAngle($newAngle);
    }
}
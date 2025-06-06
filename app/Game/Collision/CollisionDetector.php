<?php

namespace App\Game\Collision;

use App\Game\Interfaces\Collidable;
use App\Game\Interfaces\Movable;

class CollisionDetector implements Collidable
{
    private float $collisionRadius;

    public function __construct(float $collisionRadius = 0.5)
    {
        $this->collisionRadius = $collisionRadius;
    }

    public function checkCollision($object1, $object2): bool
    {
        if (!$object1 instanceof Movable || !$object2 instanceof Movable) {
            return false;
        }

        $pos1 = $object1->getPosition();
        $pos2 = $object2->getPosition();

        $dx = $pos1[0] - $pos2[0];
        $dy = $pos1[1] - $pos2[1];
        $distanceSquared = $dx * $dx + $dy * $dy;

        return $distanceSquared < (4 * $this->collisionRadius * $this->collisionRadius);
    }
}
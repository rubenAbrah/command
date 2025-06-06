<?php

namespace App\Game\Interfaces;

interface Collidable
{
    public function checkCollision($object1, $object2): bool;
}